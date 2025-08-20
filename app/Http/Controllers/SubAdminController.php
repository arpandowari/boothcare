<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\EmailService;

class SubAdminController extends Controller
{
    /**
     * Display a listing of sub-admins
     */
    public function index()
    {
        $subAdmins = User::where('role', 'sub_admin')
            ->with(['familyMember.house.booth.area'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.sub-admins.index', compact('subAdmins'));
    }

    /**
     * Show the form for creating a new sub-admin
     */
    public function create()
    {
        $availablePermissions = config('permissions.available_permissions');
        $permissionGroups = config('permissions.permission_groups');
        
        return view('admin.sub-admins.create', compact('availablePermissions', 'permissionGroups'));
    }

    /**
     * Store a newly created sub-admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'is_active' => 'boolean',
            'send_welcome_email' => 'boolean',
        ]);

        $plainPassword = $validated['password'];
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'sub_admin';
        $validated['is_active'] = $request->has('is_active');
        $validated['permissions'] = json_encode($validated['permissions']);

        $subAdmin = User::create($validated);

        // Send welcome email if requested
        if ($request->has('send_welcome_email') && $subAdmin->email) {
            $emailService = new EmailService();
            $emailService->sendWelcomeEmail($subAdmin, $plainPassword);
        }

        $message = 'Sub-Admin created successfully!';
        if ($request->has('send_welcome_email') && $subAdmin->email) {
            $message .= ' Welcome email sent to ' . $subAdmin->email . '.';
        }

        return redirect()->route('admin.sub-admins.index')->with('success', $message);
    }

    /**
     * Display the specified sub-admin
     */
    public function show(User $subAdmin)
    {
        if ($subAdmin->role !== 'sub_admin') {
            abort(404, 'Sub-Admin not found.');
        }

        $subAdmin->load(['familyMember.house.booth.area', 'reportedProblems', 'assignedProblems']);
        
        // Get activity statistics
        $stats = [
            'problems_reported' => $subAdmin->reportedProblems()->count(),
            'problems_assigned' => $subAdmin->assignedProblems()->count(),
            'problems_resolved' => $subAdmin->assignedProblems()->where('status', 'resolved')->count(),
            'update_requests_reviewed' => $subAdmin->reviewedRequests()->count(),
        ];

        return view('admin.sub-admins.show', compact('subAdmin', 'stats'));
    }

    /**
     * Show the form for editing the specified sub-admin
     */
    public function edit(User $subAdmin)
    {
        if ($subAdmin->role !== 'sub_admin') {
            abort(404, 'Sub-Admin not found.');
        }

        $availablePermissions = config('permissions.available_permissions');
        $permissionGroups = config('permissions.permission_groups');
        
        return view('admin.sub-admins.edit', compact('subAdmin', 'availablePermissions', 'permissionGroups'));
    }

    /**
     * Update the specified sub-admin
     */
    public function update(Request $request, User $subAdmin)
    {
        if ($subAdmin->role !== 'sub_admin') {
            abort(404, 'Sub-Admin not found.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $subAdmin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'is_active' => 'boolean',
        ]);

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['permissions'] = json_encode($validated['permissions']);

        $subAdmin->update($validated);

        return redirect()->route('admin.sub-admins.index')->with('success', 'Sub-Admin updated successfully!');
    }

    /**
     * Remove the specified sub-admin
     */
    public function destroy(User $subAdmin)
    {
        if ($subAdmin->role !== 'sub_admin') {
            abort(404, 'Sub-Admin not found.');
        }

        try {
            // Check if sub-admin has any related data
            $problemsCount = $subAdmin->reportedProblems()->count() + $subAdmin->assignedProblems()->count();
            $updateRequestsCount = $subAdmin->updateRequests()->count();
            
            if ($problemsCount > 0 || $updateRequestsCount > 0) {
                $message = "Cannot delete Sub-Admin '{$subAdmin->name}' because they have:";
                $reasons = [];
                
                if ($problemsCount > 0) {
                    $reasons[] = "{$problemsCount} related problem(s)";
                }
                
                if ($updateRequestsCount > 0) {
                    $reasons[] = "{$updateRequestsCount} update request(s)";
                }
                
                $message .= " " . implode(', ', $reasons) . ". Please reassign these first.";
                
                return redirect()->route('admin.sub-admins.index')->with('error', $message);
            }

            $subAdminName = $subAdmin->name;
            $subAdmin->delete();
            
            return redirect()->route('admin.sub-admins.index')->with('success', "Sub-Admin '{$subAdminName}' has been deleted successfully!");
            
        } catch (\Exception $e) {
            \Log::error('Sub-Admin deletion failed: ' . $e->getMessage());
            return redirect()->route('admin.sub-admins.index')->with('error', 'Failed to delete Sub-Admin. Please try again.');
        }
    }

    /**
     * Toggle sub-admin status
     */
    public function toggleStatus(User $subAdmin)
    {
        if ($subAdmin->role !== 'sub_admin') {
            abort(404, 'Sub-Admin not found.');
        }

        $subAdmin->update(['is_active' => !$subAdmin->is_active]);

        $status = $subAdmin->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Sub-Admin has been {$status} successfully!");
    }

    /**
     * Update permissions for a sub-admin
     */
    public function updatePermissions(Request $request, User $subAdmin)
    {
        if ($subAdmin->role !== 'sub_admin') {
            abort(404, 'Sub-Admin not found.');
        }

        $validated = $request->validate([
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string',
        ]);

        $subAdmin->update([
            'permissions' => json_encode($validated['permissions'])
        ]);

        return redirect()->back()->with('success', 'Permissions updated successfully!');
    }
}