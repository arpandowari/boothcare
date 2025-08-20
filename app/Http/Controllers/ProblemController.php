<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\FamilyMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\EmailService;
use App\Helpers\FileUploadHelper;

class ProblemController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Build query based on user role
        if ($user->isAdminOrSubAdmin()) {
            $query = Problem::with(['familyMember.house.booth.area', 'reportedBy']);
        } else {
            // Regular users see only their own problems
            $familyMember = FamilyMember::where('user_id', $user->id)->first();
            
            if (!$familyMember) {
                $problems = Problem::where('id', 0)->paginate(15);
                $stats = $this->getEmptyStats();
                return view('problems.index', compact('problems', 'stats'));
            }
            
            $query = Problem::with(['familyMember.house.booth.area', 'reportedBy'])
                ->where('family_member_id', $familyMember->id);
        }
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhereHas('familyMember', function($fq) use ($searchTerm) {
                      $fq->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        if ($request->filled('date')) {
            $dateFilter = $request->date;
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }
        
        // Get problems with pagination
        $problems = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Generate statistics
        $stats = $this->generateStats($user);
        
        return view('problems.index', compact('problems', 'stats'));
    }
    
    private function generateStats($user)
    {
        if ($user->isAdminOrSubAdmin()) {
            $baseQuery = Problem::query();
        } else {
            $familyMember = FamilyMember::where('user_id', $user->id)->first();
            if (!$familyMember) {
                return $this->getEmptyStats();
            }
            $baseQuery = Problem::where('family_member_id', $familyMember->id);
        }
        
        return [
            'total' => $baseQuery->count(),
            'reported' => $baseQuery->where('status', 'reported')->count(),
            'in_progress' => $baseQuery->where('status', 'in_progress')->count(),
            'resolved' => $baseQuery->where('status', 'resolved')->count(),
            'urgent' => $baseQuery->where('priority', 'urgent')->count(),
            'high' => $baseQuery->where('priority', 'high')->count(),
            'medium' => $baseQuery->where('priority', 'medium')->count(),
            'low' => $baseQuery->where('priority', 'low')->count(),
        ];
    }
    
    private function getEmptyStats()
    {
        return [
            'total' => 0,
            'reported' => 0,
            'in_progress' => 0,
            'resolved' => 0,
            'urgent' => 0,
            'high' => 0,
            'medium' => 0,
            'low' => 0,
        ];
    }

    public function create()
    {
        $user = Auth::user();
        
        // Only admins and sub-admins can create problems
        // Family members are just data records, not login users
        if (!$user->isAdminOrSubAdmin()) {
            abort(403, 'Only administrators can create problems.');
        }
        
        // Admin can select any member
        $areas = \App\Models\Area::all();
        $booths = \App\Models\Booth::all();
        $houses = \App\Models\House::all();
        $members = FamilyMember::with(['house.booth.area'])->get();
        $selectedMember = null;
        $selectedArea = null;
        $selectedBooth = null;
        $selectedHouse = null;

        return view('problems.create', compact(
            'areas', 'booths', 'houses', 'members', 
            'selectedMember', 'selectedArea', 'selectedBooth', 'selectedHouse'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Base validation rules
        $rules = [
            'family_member_id' => 'required|exists:family_members,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:health,education,employment,housing,legal,financial,social,infrastructure,other',
            'problem_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'expected_resolution_date' => 'nullable|date|after:today',
        ];
        
        // Only admins can access this, so always require priority
        $rules['priority'] = 'required|in:low,medium,high,urgent';
        $rules['estimated_cost'] = 'nullable|numeric|min:0';
        
        // Add category-specific validation rules
        $category = $request->input('category');
        switch ($category) {
            case 'health':
                $rules['health_type'] = 'nullable|string';
                $rules['urgency_level'] = 'nullable|string';
                break;
            case 'education':
                $rules['education_level'] = 'nullable|string';
                $rules['education_issue'] = 'nullable|string';
                break;
            case 'employment':
                $rules['employment_type'] = 'nullable|string';
                $rules['experience_level'] = 'nullable|string';
                break;
            case 'housing':
                $rules['housing_issue'] = 'nullable|string';
                $rules['housing_urgency'] = 'nullable|string';
                break;
            case 'financial':
                $rules['financial_issue'] = 'nullable|string';
                $rules['amount_involved'] = 'nullable|numeric|min:0';
                break;
        }
        
        $validated = $request->validate($rules);

        // Handle photo upload using FileUploadHelper
        if ($request->hasFile('problem_photo')) {
            $validated['problem_photo'] = FileUploadHelper::uploadFile($request->file('problem_photo'), 'problem_photos');
        }

        // Store category-specific data as JSON
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
        
        $validated['reported_by'] = $user->id;
        $validated['reported_date'] = now();
        $validated['status'] = 'reported';
        $validated['is_public'] = true;
        
        // Store category-specific data in admin_notes for now (or create a new field)
        if (!empty($categoryData)) {
            $categoryInfo = "Category Details:\n" . json_encode($categoryData, JSON_PRETTY_PRINT);
            $validated['admin_notes'] = isset($validated['admin_notes']) 
                ? $validated['admin_notes'] . "\n\n" . $categoryInfo 
                : $categoryInfo;
        }

        $problem = Problem::create($validated);

        // Send email notifications
        $emailService = new EmailService();
        
        // Send admin notification
        $emailService->sendAdminProblemNotification($problem);

        return redirect()->route('problems.index')->with('success', 'Problem reported successfully! Administrators have been notified.');
    }

    public function show(Problem $problem)
    {
        $user = Auth::user();
        
        // Check if user can view this problem
        if ($user->isAdminOrSubAdmin()) {
            // Admins can view all problems
        } else {
            // Regular users can only view their own problems
            $familyMember = FamilyMember::where('user_id', $user->id)->first();
            if (!$familyMember || $problem->family_member_id !== $familyMember->id) {
                abort(403, 'You can only view your own problems.');
            }
        }

        $problem->load(['familyMember.house.booth.area', 'reportedBy', 'assignedUser']);
        return view('problems.show', compact('problem'));
    }

    public function edit(Problem $problem)
    {
        $user = Auth::user();
        
        // Only allow family members to edit their own problems
        // Admins cannot edit user problems
        $familyMember = FamilyMember::where('user_id', $user->id)->first();
        if (!$familyMember || $problem->family_member_id !== $familyMember->id) {
            abort(403, 'You can only edit your own problems.');
        }

        $members = collect([$user->familyMember]);

        return view('problems.edit', compact('problem', 'members'));
    }

    public function update(Request $request, Problem $problem)
    {
        $user = Auth::user();
        
        // Only allow family members to update their own problems
        // Admins cannot edit user problems
        $familyMember = FamilyMember::where('user_id', $user->id)->first();
        if (!$familyMember || $problem->family_member_id !== $familyMember->id) {
            abort(403, 'You can only edit your own problems.');
        }

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:health,education,employment,housing,legal,financial,social,infrastructure,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'problem_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validated = $request->validate($rules);

        // Handle photo upload using FileUploadHelper
        if ($request->hasFile('problem_photo')) {
            // Delete old photo if exists
            if ($problem->problem_photo) {
                FileUploadHelper::deleteFile($problem->problem_photo);
            }
            
            $validated['problem_photo'] = FileUploadHelper::uploadFile($request->file('problem_photo'), 'problem_photos');
        }

        $problem->update($validated);

        return redirect()->route('problems.show', $problem)->with('success', 'Problem updated successfully!');
    }

    public function destroy(Problem $problem)
    {
        $user = Auth::user();
        
        // Only allow family members to delete their own problems
        // Admins cannot delete user problems
        $familyMember = FamilyMember::where('user_id', $user->id)->first();
        if (!$familyMember || $problem->family_member_id !== $familyMember->id) {
            abort(403, 'You can only delete your own problems.');
        }

        // Delete photo if exists
        if ($problem->problem_photo) {
            Storage::delete('public/' . $problem->problem_photo);
        }

        $problem->delete();

        return redirect()->route('problems.index')->with('success', 'Problem deleted successfully!');
    }

    /**
     * Show feedback form for resolved problems
     */
    public function showFeedback(Problem $problem)
    {
        $user = Auth::user();
        
        // Check if user can provide feedback for this problem
        $familyMember = FamilyMember::where('user_id', $user->id)->first();
        if (!$familyMember || $problem->family_member_id !== $familyMember->id) {
            abort(403, 'You can only provide feedback for your own problems.');
        }
        
        // Check if problem is resolved
        if ($problem->status !== 'resolved') {
            return redirect()->route('problems.show', $problem)
                ->with('warning', 'Feedback can only be provided for resolved problems.');
        }
        
        // Check if feedback already submitted
        if ($problem->feedback_submitted) {
            return redirect()->route('problems.show', $problem)
                ->with('info', 'You have already provided feedback for this problem.');
        }
        
        return view('problems.feedback', compact('problem'));
    }
    
    /**
     * Store user feedback for resolved problems
     */
    public function storeFeedback(Request $request, Problem $problem)
    {
        $user = Auth::user();
        
        // Check if user can provide feedback for this problem
        $familyMember = FamilyMember::where('user_id', $user->id)->first();
        if (!$familyMember || $problem->family_member_id !== $familyMember->id) {
            abort(403, 'You can only provide feedback for your own problems.');
        }
        
        // Check if problem is resolved
        if ($problem->status !== 'resolved') {
            return redirect()->route('problems.show', $problem)
                ->with('warning', 'Feedback can only be provided for resolved problems.');
        }
        
        // Check if feedback already submitted
        if ($problem->feedback_submitted) {
            return redirect()->route('problems.show', $problem)
                ->with('info', 'You have already provided feedback for this problem.');
        }
        
        $validated = $request->validate([
            'user_rating' => 'required|integer|min:1|max:5',
            'user_feedback' => 'nullable|string|max:1000',
        ]);
        
        $problem->update([
            'user_rating' => $validated['user_rating'],
            'user_feedback' => $validated['user_feedback'],
            'feedback_date' => now(),
            'feedback_submitted' => true,
        ]);
        
        // Send feedback confirmation email
        $emailService = new EmailService();
        $emailService->sendFeedbackConfirmation($problem);
        
        return redirect()->route('problems.show', $problem)
            ->with('success', 'Thank you for your feedback! Your input helps us improve our services.');
    }
    
    /**
     * Admin method to change problem status
     */
    public function updateStatus(Request $request, Problem $problem)
    {
        $user = Auth::user();
        
        if (!$user->isAdminOrSubAdmin()) {
            abort(403, 'Only administrators can update problem status.');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:reported,in_progress,resolved,closed',
            'admin_notes' => 'nullable|string',
            'resolution_notes' => 'nullable|string',
            'actual_resolution_date' => 'nullable|date',
            'actual_cost' => 'nullable|numeric|min:0',
            'status_update_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'notify_member' => 'nullable|boolean',
        ]);
        
        $oldStatus = $problem->status;
        
        // Handle image upload
        if ($request->hasFile('status_update_image')) {
            $image = $request->file('status_update_image');
            $imageName = 'status_update_' . $problem->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('problem_updates', $imageName, 'public');
            $validated['status_update_image'] = $imagePath;
        }
        
        // Set actual resolution date if status is resolved
        if ($validated['status'] === 'resolved' && !isset($validated['actual_resolution_date'])) {
            $validated['actual_resolution_date'] = now();
        }
        
        // Add update timestamp and admin info
        $validated['last_updated_by'] = $user->id;
        $validated['status_updated_at'] = now();
        
        $problem->update($validated);
        
        // Create status update log
        $this->createStatusUpdateLog($problem, $oldStatus, $validated['status'], $user, $validated);
        
        // Send status update email if status changed (always notify unless explicitly disabled)
        if ($oldStatus !== $validated['status']) {
            $shouldNotify = $validated['notify_member'] ?? true; // Default to true
            
            if ($shouldNotify) {
                $emailService = new EmailService();
                $emailService->sendProblemStatusUpdate($problem, $oldStatus, $validated['status'], $validated);
                
                Log::info('Problem status update email sent', [
                    'problem_id' => $problem->id,
                    'old_status' => $oldStatus,
                    'new_status' => $validated['status'],
                    'user_email' => $problem->familyMember->user->email ?? 'N/A'
                ]);
            }
        }
        
        return redirect()->route('problems.show', $problem)
            ->with('success', 'Problem status updated successfully! ' . 
                   (($validated['notify_member'] ?? true) ? 'Member has been notified via email.' : ''));
    }
    
    private function createStatusUpdateLog($problem, $oldStatus, $newStatus, $user, $data)
    {
        // Create a status update log entry
        $logData = [
            'problem_id' => $problem->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'updated_by' => $user->id,
            'admin_notes' => $data['admin_notes'] ?? null,
            'resolution_notes' => $data['resolution_notes'] ?? null,
            'status_update_image' => $data['status_update_image'] ?? null,
            'actual_cost' => $data['actual_cost'] ?? null,
            'updated_at' => now(),
        ];
        
        // Store in admin_notes with timestamp for history
        $historyEntry = "\n\n--- Status Update " . now()->format('Y-m-d H:i:s') . " by {$user->name} ---\n";
        $historyEntry .= "Status changed from '{$oldStatus}' to '{$newStatus}'\n";
        if (!empty($data['admin_notes'])) {
            $historyEntry .= "Admin Notes: {$data['admin_notes']}\n";
        }
        if (!empty($data['resolution_notes'])) {
            $historyEntry .= "Resolution Notes: {$data['resolution_notes']}\n";
        }
        if (!empty($data['actual_cost'])) {
            $historyEntry .= "Actual Cost: ৳" . number_format($data['actual_cost']) . "\n";
        }
        if (!empty($data['status_update_image'])) {
            $historyEntry .= "Update Image: {$data['status_update_image']}\n";
        }
        
        $problem->admin_notes = ($problem->admin_notes ?? '') . $historyEntry;
        $problem->save();
    }

    public function showStatusUpdate(Problem $problem)
    {
        $user = Auth::user();
        
        if (!$user->isAdminOrSubAdmin()) {
            abort(403, 'Only administrators can update problem status.');
        }
        
        $problem->load(['familyMember.house.booth.area', 'reportedBy']);
        
        return view('problems.status-update', compact('problem'));
    }

    // API method for getting problems by member
    public function getByMember(FamilyMember $member)
    {
        $problems = $member->problems()->orderBy('created_at', 'desc')->get();
        return response()->json($problems);
    }
}
