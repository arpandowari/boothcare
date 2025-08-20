<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\FamilyMember;
use App\Models\UpdateRequest;
use App\Services\EmailService;
use Illuminate\Support\Facades\Auth;

class ProblemRequestController extends Controller
{
    /**
     * Show problem request form for authenticated users
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get user's family member record
        $familyMember = FamilyMember::where('user_id', $user->id)->with(['house.booth.area'])->first();
        
        if (!$familyMember) {
            return redirect()->route('dashboard')->with('error', 'No family member profile found. Please contact administrator.');
        }
        
        return view('problem-requests.create', compact('familyMember'));
    }
    
    /**
     * Store problem request from authenticated user
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Get user's family member record
        $familyMember = FamilyMember::where('user_id', $user->id)->first();
        
        if (!$familyMember) {
            return redirect()->route('dashboard')->with('error', 'No family member profile found.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:health,education,employment,housing,legal,financial,social,infrastructure,other',
            'description' => 'required|string',
            'priority_request' => 'nullable|in:low,medium,high,urgent',
            'problem_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'expected_resolution_date' => 'nullable|date|after:today',
            
            // Category-specific fields
            'health_type' => 'nullable|string',
            'urgency_level' => 'nullable|string',
            'education_level' => 'nullable|string',
            'education_issue' => 'nullable|string',
            'employment_type' => 'nullable|string',
            'experience_level' => 'nullable|string',
            'housing_issue' => 'nullable|string',
            'housing_urgency' => 'nullable|string',
            'financial_issue' => 'nullable|string',
            'amount_involved' => 'nullable|numeric|min:0',
        ]);
        
        // Handle photo upload
        if ($request->hasFile('problem_photo')) {
            $validated['problem_photo'] = \App\Helpers\FileUploadHelper::uploadFile($request->file('problem_photo'), 'problem_photos');
        }
        
        // Store category-specific data
        $categoryData = $this->getCategoryData($request, $validated['category']);
        
        // Create update request for problem creation
        $requestData = [
            'user_id' => $user->id,
            'request_type' => 'problem_creation',
            'target_id' => $familyMember->id,
            'target_type' => FamilyMember::class,
            'current_data' => [
                'family_member_id' => $familyMember->id,
                'family_member_name' => $familyMember->name,
                'house' => $familyMember->house->house_number ?? 'Unknown',
                'area' => $familyMember->house->booth->area->area_name ?? 'Unknown',
            ],
            'requested_data' => array_merge($validated, $categoryData),
            'reason' => 'Problem reported by family member: ' . $validated['title'],
            'status' => 'pending',
        ];
        
        $updateRequest = UpdateRequest::create($requestData);
        
        // Send email notification to admins
        $emailService = new EmailService();
        $emailService->sendProblemRequestNotification($updateRequest);
        
        return redirect()->route('dashboard')->with('success', 'Problem request submitted successfully! An administrator will review and create the problem record.');
    }
    
    /**
     * Show problem request form for public users (family portal)
     */
    public function createPublic()
    {
        $familyMemberId = session('family_member_id');
        
        if (!$familyMemberId) {
            return redirect()->route('public.index')->with('error', 'Please authenticate first.');
        }
        
        $familyMember = FamilyMember::with(['house.booth.area'])->findOrFail($familyMemberId);
        
        return view('public.report-problem', compact('familyMember'));
    }
    
    /**
     * Store problem request from public user
     */
    public function storePublic(Request $request)
    {
        $familyMemberId = session('family_member_id');
        
        if (!$familyMemberId) {
            return redirect()->route('public.index')->with('error', 'Please authenticate first.');
        }
        
        $familyMember = FamilyMember::with(['house.booth.area'])->findOrFail($familyMemberId);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:health,education,employment,housing,legal,financial,social,infrastructure,other',
            'description' => 'required|string',
            'priority_request' => 'nullable|in:low,medium,high,urgent',
            'problem_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'contact_phone' => 'nullable|string|max:20',
            
            // Category-specific fields
            'health_type' => 'nullable|string',
            'urgency_level' => 'nullable|string',
            'education_level' => 'nullable|string',
            'education_issue' => 'nullable|string',
            'employment_type' => 'nullable|string',
            'experience_level' => 'nullable|string',
            'housing_issue' => 'nullable|string',
            'housing_urgency' => 'nullable|string',
            'financial_issue' => 'nullable|string',
            'amount_involved' => 'nullable|numeric|min:0',
        ]);
        
        // Handle photo upload
        if ($request->hasFile('problem_photo')) {
            $validated['problem_photo'] = \App\Helpers\FileUploadHelper::uploadFile($request->file('problem_photo'), 'problem_photos');
        }
        
        // Store category-specific data
        $categoryData = $this->getCategoryData($request, $validated['category']);
        
        // Create update request for problem creation
        $requestData = [
            'user_id' => null, // Public user has no user account
            'request_type' => 'public_problem_creation',
            'target_id' => $familyMember->id,
            'target_type' => FamilyMember::class,
            'current_data' => [
                'family_member_id' => $familyMember->id,
                'family_member_name' => $familyMember->name,
                'house' => $familyMember->house->house_number ?? 'Unknown',
                'area' => $familyMember->house->booth->area->area_name ?? 'Unknown',
                'contact_phone' => $validated['contact_phone'] ?? $familyMember->phone,
            ],
            'requested_data' => array_merge($validated, $categoryData),
            'reason' => 'Problem reported by family member via public portal: ' . $validated['title'],
            'status' => 'pending',
        ];
        
        $updateRequest = UpdateRequest::create($requestData);
        
        // Send email notification to admins
        $emailService = new EmailService();
        $emailService->sendProblemRequestNotification($updateRequest);
        
        return redirect()->route('public.dashboard')->with('success', 'Problem request submitted successfully! An administrator will review and create the problem record.');
    }
    
    /**
     * Get category-specific data from request
     */
    private function getCategoryData(Request $request, string $category): array
    {
        $categoryData = [];
        
        switch ($category) {
            case 'health':
                if ($request->health_type) $categoryData['health_type'] = $request->health_type;
                if ($request->urgency_level) $categoryData['urgency_level'] = $request->urgency_level;
                break;
            case 'education':
                if ($request->education_level) $categoryData['education_level'] = $request->education_level;
                if ($request->education_issue) $categoryData['education_issue'] = $request->education_issue;
                break;
            case 'employment':
                if ($request->employment_type) $categoryData['employment_type'] = $request->employment_type;
                if ($request->experience_level) $categoryData['experience_level'] = $request->experience_level;
                break;
            case 'housing':
                if ($request->housing_issue) $categoryData['housing_issue'] = $request->housing_issue;
                if ($request->housing_urgency) $categoryData['housing_urgency'] = $request->housing_urgency;
                break;
            case 'financial':
                if ($request->financial_issue) $categoryData['financial_issue'] = $request->financial_issue;
                if ($request->amount_involved) $categoryData['amount_involved'] = $request->amount_involved;
                break;
        }
        
        return $categoryData;
    }
}