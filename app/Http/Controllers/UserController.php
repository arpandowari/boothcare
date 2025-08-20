<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FamilyMember;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\EmailService;
use App\Helpers\FileUploadHelper;

class UserController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('users.view')) {
            abort(403, 'You do not have permission to view users.');
        }
        
        $users = User::with(['familyMember.house.booth.area'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('users.create')) {
            abort(403, 'You do not have permission to create users.');
        }
        
        $areas = \App\Models\Area::all();
        $booths = \App\Models\Booth::all();
        $houses = \App\Models\House::all();
        $members = FamilyMember::all();
        $availablePermissions = config('permissions.available_permissions');
        $permissionGroups = config('permissions.permission_groups');
        
        return view('users.create', compact('areas', 'booths', 'houses', 'members', 'availablePermissions', 'permissionGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,sub_admin,user',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'nid_number' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = FileUploadHelper::uploadFile($request->file('profile_photo'), 'profile_photos');
        }

        $plainPassword = $validated['password'];
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        // Handle permissions for sub-admin
        if ($validated['role'] === 'sub_admin' && isset($validated['permissions'])) {
            $validated['permissions'] = json_encode($validated['permissions']);
        } else {
            $validated['permissions'] = null;
        }

        $user = User::create($validated);

        // Send welcome email if requested
        if ($request->has('send_welcome_email') && $user->email) {
            $emailService = new EmailService();
            $emailService->sendWelcomeEmail($user, $plainPassword);
        }
        
        $message = 'User created successfully!';
        if ($request->has('send_welcome_email') && $user->email) {
            $message .= ' Welcome email sent to ' . $user->email . '.';
        }

        return redirect()->route('users.index')->with('success', $message);
    }

    public function show(User $user)
    {
        $user->load(['familyMember.house.booth.area', 'reportedProblems', 'assignedProblems']);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $areas = \App\Models\Area::all();
        $booths = \App\Models\Booth::all();
        $houses = \App\Models\House::all();
        $members = FamilyMember::all();
        $availablePermissions = config('permissions.available_permissions');
        $permissionGroups = config('permissions.permission_groups');
        
        return view('users.edit', compact('user', 'areas', 'booths', 'houses', 'members', 'availablePermissions', 'permissionGroups'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,sub_admin,user',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'nid_number' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle profile photo replacement
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if it exists
            FileUploadHelper::deleteFile($user->profile_photo);
            
            // Upload new profile photo
            $validated['profile_photo'] = FileUploadHelper::uploadFile($request->file('profile_photo'), 'profile_photos');
        }

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Handle permissions for sub-admin
        if ($validated['role'] === 'sub_admin' && isset($validated['permissions'])) {
            $validated['permissions'] = json_encode($validated['permissions']);
        } else {
            $validated['permissions'] = null;
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        try {
            // Don't allow deleting the current user
            if ($user->id === auth()->id()) {
                return redirect()->route('users.index')->with('error', 'You cannot delete your own account!');
            }

            // Check if user has any related data that might prevent deletion
            $userName = $user->name;
            
            // Check for related problems
            $problemsCount = $user->reportedProblems()->count() + $user->assignedProblems()->count();
            
            // Check for family member relationship
            $hasFamilyMember = $user->familyMember()->exists();
            
            // Check for update requests
            $updateRequestsCount = $user->updateRequests()->count();
            
            if ($problemsCount > 0 || $hasFamilyMember || $updateRequestsCount > 0) {
                $message = "Cannot delete user '{$userName}' because they have:";
                $reasons = [];
                
                if ($problemsCount > 0) {
                    $reasons[] = "{$problemsCount} related problem(s)";
                }
                
                if ($hasFamilyMember) {
                    $reasons[] = "a linked family member profile";
                }
                
                if ($updateRequestsCount > 0) {
                    $reasons[] = "{$updateRequestsCount} update request(s)";
                }
                
                $message .= " " . implode(', ', $reasons) . ". Please remove these relationships first.";
                
                return redirect()->route('users.index')->with('error', $message);
            }

            // Delete the user (model will handle file deletion)
            $user->delete();
            
            return redirect()->route('users.index')->with('success', "User '{$userName}' has been deleted successfully!");
            
        } catch (\Exception $e) {
            \Log::error('User deletion failed: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Failed to delete user. Please try again or contact support.');
        }
    }

    /**
     * Force delete a user and all related data
     */
    public function forceDestroy(User $user)
    {
        try {
            // Don't allow deleting the current user
            if ($user->id === auth()->id()) {
                return redirect()->route('users.index')->with('error', 'You cannot delete your own account!');
            }

            $userName = $user->name;
            
            // Start a database transaction
            \DB::beginTransaction();
            
            try {
                // Delete related problems (reported by user)
                $user->reportedProblems()->delete();
                
                // Reassign assigned problems to null or another admin
                $user->assignedProblems()->update(['assigned_to' => null]);
                
                // Delete update requests
                $user->updateRequests()->delete();
                
                // Delete family member relationship if exists
                if ($user->familyMember) {
                    $user->familyMember->delete();
                }
                
                // Delete any other related data
                // Add more relationships here as needed
                
                // Finally delete the user (model will handle file deletion)
                $user->delete();
                
                \DB::commit();
                
                return redirect()->route('users.index')->with('success', "User '{$userName}' and all related data have been permanently deleted!");
                
            } catch (\Exception $e) {
                \DB::rollback();
                throw $e;
            }
            
        } catch (\Exception $e) {
            \Log::error('Force user deletion failed: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Failed to force delete user. Please try again or contact support.');
        }
    }
}
