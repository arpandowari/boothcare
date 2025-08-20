<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamilyMember;
use App\Models\House;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Helpers\FileUploadHelper;
use App\Services\EmailService;

class FamilyMemberController extends Controller
{
    public function index(Request $request)
    {
        $query = FamilyMember::with(['house.booth.area', 'user', 'problems'])
            ->orderBy('is_family_head', 'desc')
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('aadhar_number', 'like', '%' . $search . '%')
                  ->orWhereHas('house', function ($houseQuery) use ($search) {
                      $houseQuery->where('house_number', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('house.booth', function ($boothQuery) use ($search) {
                      $boothQuery->where('booth_number', 'like', '%' . $search . '%')
                                 ->orWhere('booth_name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('house.booth.area', function ($areaQuery) use ($search) {
                      $areaQuery->where('area_name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Role filter
        if ($request->has('role_filter') && $request->role_filter != '') {
            if ($request->role_filter == 'head') {
                $query->where('is_family_head', true);
            } elseif ($request->role_filter == 'member') {
                $query->where('is_family_head', false);
            }
        }

        $members = $query->paginate(15)->withQueryString();

        // Get statistics
        $stats = [
            'total_members' => FamilyMember::count(),
            'house_heads' => FamilyMember::where('is_family_head', true)->count(),
            'total_houses' => House::count(),
            'total_problems' => \App\Models\Problem::count(),
            'members_with_accounts' => FamilyMember::whereNotNull('user_id')->where('can_login', true)->count(),
        ];

        return view('members.index', compact('members', 'stats'));
    }

    public function create()
    {
        // Check permissions
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('members.create')) {
            abort(403, 'You do not have permission to create members.');
        }

        $areas = \App\Models\Area::all();
        $booths = \App\Models\Booth::all();
        $houses = House::with(['booth.area'])->get();
        $members = FamilyMember::all();
        
        // Check if user is admin for different form behavior
        $isAdmin = auth()->user()->isAdmin();
        
        return view('members.create', compact('areas', 'booths', 'houses', 'members', 'isAdmin'));
    }

    public function store(Request $request)
    {
        // Check permissions
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('members.create')) {
            abort(403, 'You do not have permission to create members.');
        }

        $isAdmin = auth()->user()->isAdmin();
        
        // Different validation rules for admin vs regular users
        $validationRules = [
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:family_members,email',
            'education' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:50',
            'monthly_income' => 'nullable|numeric|min:0',
            'is_family_head' => 'boolean',
            'age' => 'nullable|integer|min:0|max:120',
            'aadhar_number' => 'nullable|string|max:20|unique:family_members,aadhar_number',
            'pan_number' => 'nullable|string|max:20|unique:family_members,pan_number',
            'voter_id' => 'nullable|string|max:20|unique:family_members,voter_id',
            'ration_card_number' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            // File uploads
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'aadhar_photo' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'pan_photo' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'voter_id_photo' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'ration_card_photo' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ];

        // For admin: house_id and relation_to_head are optional
        // For non-admin: house_id and relation_to_head are required
        if ($isAdmin) {
            $validationRules['house_id'] = 'nullable|exists:houses,id';
            $validationRules['relation_to_head'] = 'nullable|string|max:100';
            $validationRules['member_type'] = 'nullable|in:family_member,standalone_member';
        } else {
            $validationRules['house_id'] = 'required|exists:houses,id';
            $validationRules['relation_to_head'] = 'required|string|max:100';
        }

        $validated = $request->validate($validationRules);

        // Handle file uploads using FileUploadHelper
        $fileFields = ['profile_photo', 'aadhar_photo', 'pan_photo', 'voter_id_photo', 'ration_card_photo'];
        $uploadedFiles = FileUploadHelper::handleFileUploads($request->allFiles(), $fileFields);
        
        // Merge uploaded file paths into validated data
        $validated = array_merge($validated, $uploadedFiles);

        $validated['is_family_head'] = $request->has('is_family_head');
        $validated['is_head'] = $validated['is_family_head'];
        $validated['is_active'] = true;
        $validated['relationship_to_head'] = $validated['relation_to_head'] ?? null;

        // Check if we can automatically create a user account
        $canCreateUserAccount = !empty($validated['aadhar_number']) && !empty($validated['date_of_birth']);
        $userCreated = false;
        $userId = null;

        if ($canCreateUserAccount) {
            // Check if Aadhar number is already used
            $existingUser = User::where('aadhar_number', $validated['aadhar_number'])->first();
            
            if (!$existingUser) {
                try {
                    // Create user account automatically
                    $user = User::create([
                        'name' => $validated['name'],
                        'email' => $validated['email'] ?? null,
                        'aadhar_number' => $validated['aadhar_number'],
                        'date_of_birth' => $validated['date_of_birth'],
                        'phone' => $validated['phone'] ?? null,
                        'gender' => $validated['gender'],
                        'role' => 'user',
                        'is_active' => true,
                        'password' => bcrypt('default123'), // Default password
                    ]);

                    $userId = $user->id;
                    $userCreated = true;
                    $validated['can_login'] = true;
                    $validated['user_account_created_at'] = now();
                } catch (\Exception $e) {
                    // If user creation fails, continue without user account
                    $validated['can_login'] = false;
                }
            } else {
                // Aadhar already exists, don't create user account
                $validated['can_login'] = false;
            }
        } else {
            $validated['can_login'] = false;
        }

        $validated['user_id'] = $userId;
        
        $member = FamilyMember::create($validated);

        // Send email notifications
        $emailService = new EmailService();
        
        if ($userCreated && $member->email) {
            // Send member account created email
            $emailService->sendMemberAccountCreated($member, 'default123');
        }

        $message = 'Family member added successfully!';
        if ($userCreated) {
            $message .= ' User account created automatically - they can now log in using their Aadhar number and date of birth.';
            if ($member->email) {
                $message .= ' Welcome email sent to ' . $member->email . '.';
            }
        } elseif ($canCreateUserAccount && !$userCreated) {
            $message .= ' Note: User account could not be created (Aadhar number may already be in use).';
        }

        return redirect()->route('members.index')->with('success', $message);
    }

    public function show(FamilyMember $member)
    {
        $member->load(['house.booth.area', 'user', 'problems']);
        return view('members.show', compact('member'));
    }

    public function edit(FamilyMember $member)
    {
        $areas = \App\Models\Area::all();
        $booths = \App\Models\Booth::all();
        $houses = House::with(['booth.area'])->get();
        return view('members.edit', compact('member', 'areas', 'booths', 'houses'));
    }

    public function update(Request $request, FamilyMember $member)
    {
        $validated = $request->validate([
            'house_id' => 'required|exists:houses,id',
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'relation_to_head' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:family_members,email,' . $member->id,
            'education' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:50',
            'monthly_income' => 'nullable|numeric|min:0',
            'is_family_head' => 'boolean',
            'aadhar_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'voter_id' => 'nullable|string|max:20',
            'ration_card_number' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            // File uploads
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'aadhar_photo' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'pan_photo' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'voter_id_photo' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'ration_card_photo' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // Handle file uploads using FileUploadHelper
        $fileFields = ['profile_photo', 'aadhar_photo', 'pan_photo', 'voter_id_photo', 'ration_card_photo'];
        $uploadedFiles = FileUploadHelper::handleFileUploads($request->allFiles(), $fileFields, $member);
        
        // Merge uploaded file paths into validated data
        $validated = array_merge($validated, $uploadedFiles);

        $validated['is_family_head'] = $request->has('is_family_head');
        $validated['is_head'] = $validated['is_family_head'];

        // Check if we should create/update user account
        $canCreateUserAccount = !empty($validated['aadhar_number']) && !empty($validated['date_of_birth']);
        $userCreated = false;
        $userUpdated = false;

        if ($canCreateUserAccount && !$member->hasUserAccount()) {
            // Check if Aadhar number is already used by another user
            $existingUser = User::where('aadhar_number', $validated['aadhar_number'])
                               ->where('id', '!=', $member->user_id ?? 0)
                               ->first();
            
            if (!$existingUser) {
                try {
                    // Create user account automatically
                    $user = User::create([
                        'name' => $validated['name'],
                        'email' => $validated['email'] ?? null,
                        'aadhar_number' => $validated['aadhar_number'],
                        'date_of_birth' => $validated['date_of_birth'],
                        'phone' => $validated['phone'] ?? null,
                        'gender' => $validated['gender'],
                        'role' => 'user',
                        'is_active' => true,
                        'password' => bcrypt('default123'), // Default password
                    ]);

                    $validated['user_id'] = $user->id;
                    $validated['can_login'] = true;
                    $validated['user_account_created_at'] = now();
                    $userCreated = true;
                } catch (\Exception $e) {
                    // If user creation fails, continue without user account
                    $validated['can_login'] = false;
                }
            }
        } elseif ($member->hasUserAccount() && $member->user) {
            // Update existing user account
            try {
                $member->user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'] ?? $member->user->email,
                    'aadhar_number' => $validated['aadhar_number'],
                    'date_of_birth' => $validated['date_of_birth'],
                    'phone' => $validated['phone'] ?? $member->user->phone,
                    'gender' => $validated['gender'],
                ]);
                $userUpdated = true;
            } catch (\Exception $e) {
                // Continue if user update fails
            }
        }

        $member->update($validated);

        $message = 'Family member updated successfully!';
        if ($userCreated) {
            $message .= ' User account created automatically - they can now log in using their Aadhar number and date of birth.';
        } elseif ($userUpdated) {
            $message .= ' User account updated as well.';
        }

        return redirect()->route('members.index')->with('success', $message);
    }

    public function destroy(FamilyMember $member)
    {
        try {
            $memberName = $member->name;
            
            // Check if this member has a linked user account
            if ($member->user_id && $member->user) {
                $user = $member->user;
                
                // Delete all update requests related to this user
                $updateRequestsCount = $user->updateRequests()->count();
                if ($updateRequestsCount > 0) {
                    $user->updateRequests()->delete();
                }
                
                // Delete all update requests that target this family member
                \App\Models\UpdateRequest::where('target_type', 'App\\Models\\FamilyMember')
                                         ->where('target_id', $member->id)
                                         ->delete();
                
                // Check if user has other family members or problems
                $otherFamilyMembers = \App\Models\FamilyMember::where('user_id', $user->id)
                                                             ->where('id', '!=', $member->id)
                                                             ->count();
                
                $problemsCount = $user->reportedProblems()->count() + $user->assignedProblems()->count();
                
                // If user has no other family members and no problems, delete the user account too
                if ($otherFamilyMembers == 0 && $problemsCount == 0) {
                    $user->delete();
                }
            } else {
                // Delete any update requests that target this family member
                \App\Models\UpdateRequest::where('target_type', 'App\\Models\\FamilyMember')
                                         ->where('target_id', $member->id)
                                         ->delete();
            }
            
            // Delete associated files
            $fileFields = ['profile_photo', 'aadhar_photo', 'pan_photo', 'voter_id_photo', 'ration_card_photo'];
            foreach ($fileFields as $field) {
                if ($member->$field) {
                    Storage::disk('public')->delete($member->$field);
                }
            }

            // Delete the family member
            $member->delete();
            
            return redirect()->route('members.index')->with('success', "Family member '{$memberName}' and all related data have been deleted successfully!");
            
        } catch (\Exception $e) {
            \Log::error('Family member deletion failed: ' . $e->getMessage());
            return redirect()->route('members.index')->with('error', 'Failed to delete family member. Please try again or contact support.');
        }
    }

    // API method for getting members by family (kept for compatibility)
    public function getByFamily($familyId)
    {
        // Since we don't have families anymore, return empty
        return response()->json([]);
    }

    // API method for getting members by house
    public function getByHouse($houseId)
    {
        $members = FamilyMember::where('house_id', $houseId)
            ->with(['house'])
            ->get(['id', 'name', 'relation_to_head', 'phone', 'date_of_birth', 'is_family_head', 'profile_photo', 'house_id']);
        return response()->json($members);
    }

    // Show house members page
    public function houseMembers(House $house)
    {
        $user = auth()->user();
        
        // Check if user has access to this house
        if (!$user->isAdminOrSubAdmin()) {
            // For regular users, check if they belong to this house
            $familyMember = \App\Models\FamilyMember::where('user_id', $user->id)->first();
            
            if (!$familyMember || $familyMember->house_id !== $house->id) {
                abort(403, 'You can only view members of your own house.');
            }
        }

        $house->load(['booth.area', 'members', 'familyHead']);
        $members = $house->members()->with(['problems'])->get();

        return view('houses.members', compact('house', 'members'));
    }


}
