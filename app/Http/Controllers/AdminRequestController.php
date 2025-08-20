<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpdateRequest;
use App\Models\FamilyMember;
use App\Models\Area;
use App\Models\Booth;
use App\Models\House;
use App\Models\Problem;
use Illuminate\Support\Facades\Auth;

class AdminRequestController extends Controller
{
    /**
     * Show admin request dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get recent requests made by this admin
        $myRequests = UpdateRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get pending requests for review (Admin and Sub-Admin can review)
        $pendingRequests = collect();
        $problemRequests = collect();
        
        if ($user->isAdminOrSubAdmin()) {
            $pendingRequests = UpdateRequest::where('status', 'pending')
                ->whereNotIn('request_type', ['problem_creation', 'public_problem_creation'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
                
            // Get problem creation requests separately
            $problemRequests = UpdateRequest::where('status', 'pending')
                ->whereIn('request_type', ['problem_creation', 'public_problem_creation'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }
        
        return view('admin.requests.index', compact('myRequests', 'pendingRequests', 'problemRequests'));
    }

    /**
     * Show form to request family member data update
     */
    public function requestFamilyMemberUpdate()
    {
        $areas = Area::all();
        $booths = Booth::all();
        $houses = House::all();
        $members = FamilyMember::with(['house.booth.area'])->get();
        
        return view('admin.requests.family-member-update', compact('areas', 'booths', 'houses', 'members'));
    }

    /**
     * Store family member update request
     */
    public function storeFamilyMemberUpdate(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'family_member_id' => 'required|exists:family_members,id',
            'field_to_update' => 'required|string',
            'current_value' => 'required|string',
            'requested_value' => 'required|string',
            'reason' => 'required|string|max:1000',
        ]);
        
        $familyMember = FamilyMember::findOrFail($validated['family_member_id']);
        
        UpdateRequest::create([
            'user_id' => $user->id,
            'request_type' => 'family_member',
            'target_id' => $familyMember->id,
            'target_type' => FamilyMember::class,
            'current_data' => [
                'field' => $validated['field_to_update'],
                'value' => $validated['current_value']
            ],
            'requested_data' => [
                'field' => $validated['field_to_update'],
                'value' => $validated['requested_value']
            ],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);
        
        return redirect()->route('admin.requests.index')
            ->with('success', 'Update request submitted successfully. A Super Administrator will review it.');
    }

    /**
     * Show form to request area/booth/house updates
     */
    public function requestLocationUpdate()
    {
        $areas = Area::all();
        $booths = Booth::all();
        $houses = House::all();
        
        return view('admin.requests.location-update', compact('areas', 'booths', 'houses'));
    }

    /**
     * Store location update request
     */
    public function storeLocationUpdate(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'target_type' => 'required|in:area,booth,house',
            'target_id' => 'required|integer',
            'field_to_update' => 'required|string',
            'current_value' => 'required|string',
            'requested_value' => 'required|string',
            'reason' => 'required|string|max:1000',
        ]);
        
        $targetClass = match($validated['target_type']) {
            'area' => Area::class,
            'booth' => Booth::class,
            'house' => House::class,
        };
        
        $target = $targetClass::findOrFail($validated['target_id']);
        
        UpdateRequest::create([
            'user_id' => $user->id,
            'request_type' => $validated['target_type'],
            'target_id' => $target->id,
            'target_type' => $targetClass,
            'current_data' => [
                'field' => $validated['field_to_update'],
                'value' => $validated['current_value']
            ],
            'requested_data' => [
                'field' => $validated['field_to_update'],
                'value' => $validated['requested_value']
            ],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);
        
        return redirect()->route('admin.requests.index')
            ->with('success', 'Location update request submitted successfully.');
    }



    /**
     * Show specific request details
     */
    public function show(UpdateRequest $request)
    {
        $user = Auth::user();
        
        // Debug logging
        \Log::info('Request show page accessed', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'request_id' => $request->id,
            'request_type' => $request->request_type,
            'request_status' => $request->status
        ]);
        
        // Check if user can view this request
        if (!$user->isAdminOrSubAdmin() && $request->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this request.');
        }
        
        $request->load(['user', 'reviewer', 'target']);
        
        return view('admin.requests.show', compact('request'));
    }
    
    /**
     * Approve problem creation request and create the problem
     */
    public function approveProblemRequest(UpdateRequest $updateRequest)
    {
        $user = Auth::user();
        
        // Debug logging
        \Log::info('Problem approval attempt', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,
            'request_id' => $updateRequest->id,
            'request_type' => $updateRequest->request_type,
            'request_status' => $updateRequest->status,
            'request_method' => request()->method()
        ]);
        
        // Allow both admin and sub-admin
        if (!$user->isAdminOrSubAdmin()) {
            \Log::warning('Unauthorized approval attempt', ['user_role' => $user->role]);
            return redirect()->back()->with('error', 'Administrator privileges required to approve problem requests. Your role: ' . $user->role);
        }
        
        if (!in_array($updateRequest->request_type, ['problem_creation', 'public_problem_creation'])) {
            \Log::warning('Invalid request type for approval', ['request_type' => $updateRequest->request_type]);
            return redirect()->back()->with('error', 'This is not a problem creation request.');
        }
        
        if ($updateRequest->status !== 'pending') {
            \Log::warning('Request already processed', ['status' => $updateRequest->status]);
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        $requestedData = $updateRequest->requested_data;
        
        // Create the problem
        $problemData = [
            'family_member_id' => $updateRequest->target_id,
            'title' => $requestedData['title'],
            'description' => $requestedData['description'],
            'category' => $requestedData['category'],
            'priority' => $requestedData['priority_request'] ?? 'medium',
            'reported_by' => $user->id, // Super admin who approved it
            'reported_date' => now(),
            'status' => 'reported',
            'is_public' => true,
            'expected_resolution_date' => $requestedData['expected_resolution_date'] ?? null,
            'problem_photo' => $requestedData['problem_photo'] ?? null,
        ];
        
        // Add category-specific data to admin notes
        $categoryData = [];
        $category = $requestedData['category'];
        
        switch ($category) {
            case 'health':
                if (isset($requestedData['health_type'])) $categoryData['health_type'] = $requestedData['health_type'];
                if (isset($requestedData['urgency_level'])) $categoryData['urgency_level'] = $requestedData['urgency_level'];
                break;
            case 'education':
                if (isset($requestedData['education_level'])) $categoryData['education_level'] = $requestedData['education_level'];
                if (isset($requestedData['education_issue'])) $categoryData['education_issue'] = $requestedData['education_issue'];
                break;
            case 'employment':
                if (isset($requestedData['employment_type'])) $categoryData['employment_type'] = $requestedData['employment_type'];
                if (isset($requestedData['experience_level'])) $categoryData['experience_level'] = $requestedData['experience_level'];
                break;
            case 'housing':
                if (isset($requestedData['housing_issue'])) $categoryData['housing_issue'] = $requestedData['housing_issue'];
                if (isset($requestedData['housing_urgency'])) $categoryData['housing_urgency'] = $requestedData['housing_urgency'];
                break;
            case 'financial':
                if (isset($requestedData['financial_issue'])) $categoryData['financial_issue'] = $requestedData['financial_issue'];
                if (isset($requestedData['amount_involved'])) $categoryData['amount_involved'] = $requestedData['amount_involved'];
                break;
        }
        
        if (!empty($categoryData)) {
            $categoryInfo = "Category Details:\n" . json_encode($categoryData, JSON_PRETTY_PRINT);
            $problemData['admin_notes'] = $categoryInfo;
        }
        
        // Add request source info
        $sourceInfo = "\n\nRequest Source: " . ($updateRequest->request_type === 'public_problem_creation' ? 'Public Family Portal' : 'Authenticated User');
        if ($updateRequest->user) {
            $sourceInfo .= "\nRequested by: " . $updateRequest->user->name;
        }
        if (isset($requestedData['contact_phone'])) {
            $sourceInfo .= "\nContact Phone: " . $requestedData['contact_phone'];
        }
        $sourceInfo .= "\nApproved by: " . $user->name . " on " . now()->format('Y-m-d H:i:s');
        
        $problemData['admin_notes'] = ($problemData['admin_notes'] ?? '') . $sourceInfo;
        
        $problem = Problem::create($problemData);
        
        // Update the request status
        $updateRequest->update([
            'status' => 'approved',
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
            'review_notes' => 'Problem created successfully with ID: ' . $problem->id
        ]);
        
        // Send email notifications
        $emailService = new \App\Services\EmailService();
        $emailService->sendAdminProblemNotification($problem);
        
        return redirect()->route('admin.requests.index')
            ->with('success', 'Problem request approved and problem created successfully! Problem ID: ' . $problem->id);
    }
    
    /**
     * Reject problem creation request
     */
    public function rejectProblemRequest(Request $request, UpdateRequest $updateRequest)
    {
        $user = Auth::user();
        
        if (!$user->isAdminOrSubAdmin()) {
            abort(403, 'Administrator privileges required to reject problem requests.');
        }
        
        if (!in_array($updateRequest->request_type, ['problem_creation', 'public_problem_creation'])) {
            return redirect()->back()->with('error', 'This is not a problem creation request.');
        }
        
        if ($updateRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        $validated = $request->validate([
            'review_notes' => 'required|string|max:1000'
        ]);
        
        $updateRequest->update([
            'status' => 'rejected',
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
            'review_notes' => $validated['review_notes']
        ]);
        
        return redirect()->route('admin.requests.index')
            ->with('success', 'Problem request rejected successfully.');
    }
}