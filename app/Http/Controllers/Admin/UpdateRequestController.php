<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UpdateRequest;
use App\Models\User;
use App\Models\FamilyMember;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UpdateRequestController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of update requests
     */
    public function index(Request $request)
    {
        // Check admin permissions
        if (!Auth::user()->isAdminOrSubAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        // Separate problem requests from regular update requests
        $problemQuery = UpdateRequest::with(['user', 'reviewer'])
            ->whereIn('request_type', ['problem_creation', 'public_problem_creation'])
            ->orderBy('created_at', 'desc');

        $regularQuery = UpdateRequest::with(['user', 'reviewer'])
            ->whereNotIn('request_type', ['problem_creation', 'public_problem_creation'])
            ->orderBy('created_at', 'desc');

        // Apply filters to regular requests only
        if ($request->has('status') && $request->status !== '') {
            $regularQuery->where('status', $request->status);
        }

        if ($request->has('type') && $request->type !== '') {
            $regularQuery->where('request_type', $request->type);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $regularQuery->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Get problem requests (always show pending ones first)
        $problemRequests = $problemQuery->where('status', 'pending')->get();

        // Get regular update requests with pagination
        $updateRequests = $regularQuery->paginate(20)->withQueryString();

        // Get statistics (including all request types)
        $stats = [
            'total' => UpdateRequest::count(),
            'pending' => UpdateRequest::where('status', 'pending')->count(),
            'approved' => UpdateRequest::where('status', 'approved')->count(),
            'rejected' => UpdateRequest::where('status', 'rejected')->count(),
        ];

        // Get request types for filter (excluding problem types)
        $requestTypes = UpdateRequest::select('request_type')
            ->whereNotIn('request_type', ['problem_creation', 'public_problem_creation'])
            ->distinct()
            ->pluck('request_type')
            ->map(function ($type) {
                return [
                    'value' => $type,
                    'label' => ucfirst(str_replace('_', ' ', $type))
                ];
            });

        return view('admin.update-requests.index', compact(
            'updateRequests',
            'problemRequests',
            'stats',
            'requestTypes'
        ));
    }

    /**
     * Show the specified update request
     */
    public function show(UpdateRequest $updateRequest)
    {
        if (!Auth::user()->isAdminOrSubAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $updateRequest->load(['user', 'reviewer', 'target']);

        return view('admin.update-requests.show', compact('updateRequest'));
    }

    /**
     * Approve an update request
     */
    public function approve(Request $request, UpdateRequest $updateRequest)
    {
        if (!Auth::user()->isAdminOrSubAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $request->validate([
            'review_notes' => 'nullable|string|max:1000'
        ]);

        if ($updateRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }

        DB::beginTransaction();
        try {
            // Apply the requested changes based on request type
            $this->applyRequestedChanges($updateRequest);

            // Update the request status
            $updateRequest->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'review_notes' => $request->review_notes
            ]);

            // Send email notification
            $this->emailService->sendUpdateRequestNotification($updateRequest, 'approved');

            DB::commit();

            Log::info('Update request approved', [
                'request_id' => $updateRequest->id,
                'request_type' => $updateRequest->request_type,
                'reviewed_by' => Auth::user()->name
            ]);

            return redirect()->route('admin.update-requests.index')
                ->with('success', 'Update request approved successfully! Changes have been applied and user has been notified.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to approve update request: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to approve request. Please try again or contact support.');
        }
    }

    /**
     * Reject an update request
     */
    public function reject(Request $request, UpdateRequest $updateRequest)
    {
        if (!Auth::user()->isAdminOrSubAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $request->validate([
            'review_notes' => 'required|string|max:1000'
        ]);

        if ($updateRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }

        try {
            // Update the request status
            $updateRequest->update([
                'status' => 'rejected',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'review_notes' => $request->review_notes
            ]);

            // Send email notification
            $this->emailService->sendUpdateRequestNotification($updateRequest, 'rejected');

            Log::info('Update request rejected', [
                'request_id' => $updateRequest->id,
                'request_type' => $updateRequest->request_type,
                'reviewed_by' => Auth::user()->name,
                'reason' => $request->review_notes
            ]);

            return redirect()->route('admin.update-requests.index')
                ->with('success', 'Update request rejected successfully! User has been notified with the reason.');

        } catch (\Exception $e) {
            Log::error('Failed to reject update request: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to reject request. Please try again or contact support.');
        }
    }

    /**
     * Bulk approve multiple requests
     */
    public function bulkApprove(Request $request)
    {
        if (!Auth::user()->isAdminOrSubAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'exists:update_requests,id'
        ]);

        $successCount = 0;
        $errorCount = 0;

        foreach ($request->request_ids as $requestId) {
            try {
                $updateRequest = UpdateRequest::find($requestId);
                
                if ($updateRequest && $updateRequest->status === 'pending') {
                    DB::beginTransaction();
                    
                    $this->applyRequestedChanges($updateRequest);
                    
                    $updateRequest->update([
                        'status' => 'approved',
                        'reviewed_by' => Auth::id(),
                        'reviewed_at' => now(),
                        'review_notes' => 'Bulk approved by admin'
                    ]);

                    $this->emailService->sendUpdateRequestNotification($updateRequest, 'approved');
                    
                    DB::commit();
                    $successCount++;
                }
            } catch (\Exception $e) {
                DB::rollback();
                $errorCount++;
                Log::error('Bulk approve failed for request ' . $requestId . ': ' . $e->getMessage());
            }
        }

        $message = "Bulk approval completed. {$successCount} requests approved";
        if ($errorCount > 0) {
            $message .= ", {$errorCount} failed";
        }

        return redirect()->route('admin.update-requests.index')->with('success', $message);
    }

    /**
     * Bulk reject multiple requests
     */
    public function bulkReject(Request $request)
    {
        if (!Auth::user()->isAdminOrSubAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'exists:update_requests,id',
            'bulk_reject_reason' => 'required|string|max:1000'
        ]);

        $successCount = 0;
        $errorCount = 0;

        foreach ($request->request_ids as $requestId) {
            try {
                $updateRequest = UpdateRequest::find($requestId);
                
                if ($updateRequest && $updateRequest->status === 'pending') {
                    $updateRequest->update([
                        'status' => 'rejected',
                        'reviewed_by' => Auth::id(),
                        'reviewed_at' => now(),
                        'review_notes' => $request->bulk_reject_reason
                    ]);

                    $this->emailService->sendUpdateRequestNotification($updateRequest, 'rejected');
                    $successCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
                Log::error('Bulk reject failed for request ' . $requestId . ': ' . $e->getMessage());
            }
        }

        $message = "Bulk rejection completed. {$successCount} requests rejected";
        if ($errorCount > 0) {
            $message .= ", {$errorCount} failed";
        }

        return redirect()->route('admin.update-requests.index')->with('success', $message);
    }

    /**
     * Apply the requested changes to the target model
     */
    private function applyRequestedChanges(UpdateRequest $updateRequest)
    {
        $requestedData = $updateRequest->requested_data;

        switch ($updateRequest->request_type) {
            case 'profile':
                $this->applyProfileChanges($updateRequest->user, $requestedData);
                break;
                
            case 'family_member':
                $this->applyFamilyMemberChanges($updateRequest, $requestedData);
                break;
                
            case 'documents':
                $this->applyDocumentChanges($updateRequest, $requestedData);
                break;
                
            case 'problem_creation':
            case 'public_problem_creation':
                $this->applyProblemCreation($updateRequest, $requestedData);
                break;
                
            default:
                throw new \Exception('Unknown request type: ' . $updateRequest->request_type);
        }
    }

    /**
     * Apply profile changes
     */
    private function applyProfileChanges(User $user, array $requestedData)
    {
        // Update basic profile fields
        $allowedFields = ['name', 'email', 'phone', 'date_of_birth', 'gender'];
        $updateData = array_intersect_key($requestedData, array_flip($allowedFields));
        
        if (!empty($updateData)) {
            $user->update($updateData);
        }

        // Handle uploaded documents/images (check both keys for compatibility)
        $uploadedDocs = $requestedData['uploaded_files'] ?? $requestedData['uploaded_documents'] ?? null;
        if ($uploadedDocs) {
            
            // Update profile photo if uploaded
            if (isset($uploadedDocs['profile_photo'])) {
                $user->update(['profile_photo' => $uploadedDocs['profile_photo']['path']]);
                Log::info('Profile photo updated for user', [
                    'user_id' => $user->id,
                    'photo_path' => $uploadedDocs['profile_photo']['path']
                ]);
            }

            // Update other document photos
            $documentFields = ['aadhar_photo', 'pan_photo', 'voter_id_photo', 'ration_card_photo'];
            foreach ($documentFields as $field) {
                if (isset($uploadedDocs[$field])) {
                    $user->update([$field => $uploadedDocs[$field]['path']]);
                    Log::info("Document updated for user: {$field}", [
                        'user_id' => $user->id,
                        'document_path' => $uploadedDocs[$field]['path']
                    ]);
                }
            }
        }

        // Also update family member if exists
        if ($user->familyMember) {
            $familyMemberFields = ['name', 'email', 'phone', 'date_of_birth', 'gender'];
            $familyUpdateData = array_intersect_key($requestedData, array_flip($familyMemberFields));
            
            if (!empty($familyUpdateData)) {
                $user->familyMember->update($familyUpdateData);
            }

            // Update family member profile photo if uploaded
            if (isset($uploadedDocs['family_profile_photo'])) {
                $familyPhoto = $uploadedDocs['family_profile_photo'];
                $user->familyMember->update(['profile_photo' => $familyPhoto['path']]);
                Log::info('Family member profile photo updated', [
                    'family_member_id' => $user->familyMember->id,
                    'photo_path' => $familyPhoto['path']
                ]);
            }
        }
    }

    /**
     * Apply family member changes
     */
    private function applyFamilyMemberChanges(UpdateRequest $updateRequest, array $requestedData)
    {
        if ($updateRequest->target_type === 'App\\Models\\FamilyMember' && $updateRequest->target_id) {
            $familyMember = FamilyMember::find($updateRequest->target_id);
            if ($familyMember) {
                // Update basic family member fields
                $allowedFields = [
                    'name', 'email', 'phone', 'date_of_birth', 'gender', 
                    'relation_to_head', 'education', 'occupation', 'marital_status',
                    'monthly_income', 'medical_conditions', 'notes'
                ];
                $updateData = array_intersect_key($requestedData, array_flip($allowedFields));
                
                if (!empty($updateData)) {
                    $familyMember->update($updateData);
                    Log::info('Family member basic data updated', [
                        'family_member_id' => $familyMember->id,
                        'updated_fields' => array_keys($updateData)
                    ]);
                }

                // Handle uploaded files for family member (check both keys for compatibility)
                $uploadedDocs = $requestedData['uploaded_files'] ?? $requestedData['uploaded_documents'] ?? null;
                if ($uploadedDocs) {
                    // Update family member document photos
                    $familyDocFields = ['profile_photo', 'aadhar_photo', 'pan_photo', 'voter_id_photo'];
                    foreach ($familyDocFields as $field) {
                        if (isset($uploadedDocs[$field])) {
                            $familyMember->update([$field => $uploadedDocs[$field]['path']]);
                            Log::info("Family member document updated: {$field}", [
                                'family_member_id' => $familyMember->id,
                                'document_path' => $uploadedDocs[$field]['path']
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Apply document changes
     */
    private function applyDocumentChanges(UpdateRequest $updateRequest, array $requestedData)
    {
        $user = $updateRequest->user;
        $familyMember = $user->familyMember;

        // Ensure family member exists
        if (!$familyMember) {
            Log::warning('No family member found for user during document update', [
                'user_id' => $user->id,
                'request_id' => $updateRequest->id
            ]);
            return;
        }

        // Update document numbers - these go to family_members table
        $documentFields = ['aadhar_number', 'pan_number', 'voter_id'];
        $familyUpdateData = array_intersect_key($requestedData, array_flip($documentFields));
        
        if (!empty($familyUpdateData)) {
            $familyMember->update($familyUpdateData);
            Log::info('Family member document numbers updated', [
                'family_member_id' => $familyMember->id,
                'updated_fields' => array_keys($familyUpdateData),
                'values' => $familyUpdateData
            ]);
        }

        // Handle uploaded documents (check both keys for compatibility)
        $uploadedDocs = $requestedData['uploaded_files'] ?? $requestedData['uploaded_documents'] ?? null;
        if ($uploadedDocs) {
            
            // Update user document photos (only profile and aadhar go to users table)
            $userDocFields = ['profile_photo', 'aadhar_photo'];
            foreach ($userDocFields as $field) {
                if (isset($uploadedDocs[$field])) {
                    $user->update([$field => $uploadedDocs[$field]['path']]);
                    Log::info("User document photo updated: {$field}", [
                        'user_id' => $user->id,
                        'photo_path' => $uploadedDocs[$field]['path']
                    ]);
                }
            }

            // Update family member document photos (pan, voter_id, ration_card go to family_members table)
            $familyDocFields = ['pan_photo', 'voter_id_photo', 'ration_card_photo'];
            foreach ($familyDocFields as $field) {
                if (isset($uploadedDocs[$field])) {
                    $familyMember->update([$field => $uploadedDocs[$field]['path']]);
                    Log::info("Family member document photo updated: {$field}", [
                        'family_member_id' => $familyMember->id,
                        'photo_path' => $uploadedDocs[$field]['path']
                    ]);
                }
            }

            // Update family member profile photo if uploaded
            if (isset($uploadedDocs['family_profile_photo'])) {
                $familyMember->update(['profile_photo' => $uploadedDocs['family_profile_photo']['path']]);
                Log::info('Family member profile photo updated', [
                    'family_member_id' => $familyMember->id,
                    'photo_path' => $uploadedDocs['family_profile_photo']['path']
                ]);
            }
        }

        Log::info('Document update request approved', [
            'request_id' => $updateRequest->id,
            'user_id' => $updateRequest->user_id,
            'family_member_id' => $familyMember->id,
            'document_fields_updated' => array_keys($familyUpdateData),
            'documents_uploaded' => array_keys($uploadedDocs ?? [])
        ]);
    }

    /**
     * Apply problem creation
     */
    private function applyProblemCreation(UpdateRequest $updateRequest, array $requestedData)
    {
        // Handle both authenticated user and public problem creation requests
        $familyMemberId = null;
        $reportedBy = null;
        
        if ($updateRequest->user && $updateRequest->user->familyMember) {
            // Authenticated user with family member
            $familyMemberId = $updateRequest->user->familyMember->id;
            $reportedBy = $updateRequest->user_id;
        } elseif ($updateRequest->target_type === 'App\\Models\\FamilyMember' && $updateRequest->target_id) {
            // Public request with target family member
            $familyMemberId = $updateRequest->target_id;
            $reportedBy = null; // Public user has no user ID
        }

        // Create the problem based on the request
        $problemData = [
            'family_member_id' => $familyMemberId,
            'reported_by' => $reportedBy,
            'title' => $requestedData['title'] ?? 'Problem Report',
            'description' => $requestedData['description'] ?? 'No description provided',
            'category' => $requestedData['category'] ?? 'general',
            'priority' => $requestedData['priority'] ?? 'medium',
            'status' => 'reported',
            'reported_date' => now()->toDateString()
        ];

        // Add reporter information for public requests
        if ($updateRequest->request_type === 'public_problem_creation') {
            $reporterInfo = '';
            if (isset($requestedData['reporter_name'])) {
                $reporterInfo .= "Reporter: " . $requestedData['reporter_name'];
            }
            if (isset($requestedData['reporter_phone'])) {
                $reporterInfo .= "\nPhone: " . $requestedData['reporter_phone'];
            }
            if (isset($requestedData['reporter_email'])) {
                $reporterInfo .= "\nEmail: " . $requestedData['reporter_email'];
            }
            
            if ($reporterInfo) {
                $problemData['description'] .= "\n\n--- Reporter Information ---\n" . $reporterInfo;
            }
        }

        $problem = \App\Models\Problem::create($problemData);

        Log::info('Problem created from update request', [
            'problem_id' => $problem->id,
            'request_id' => $updateRequest->id,
            'request_type' => $updateRequest->request_type,
            'family_member_id' => $familyMemberId,
            'reported_by' => $reportedBy
        ]);

        return $problem;
    }
}