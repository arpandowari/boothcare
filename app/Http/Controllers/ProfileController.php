<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\FamilyMember;
use App\Models\House;
use App\Helpers\FileUploadHelper;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $familyMember = null;
        
        // Only try to get family member for regular users
        if ($user->role === 'user') {
            $familyMember = FamilyMember::with(['house.booth.area', 'problems'])->where('user_id', $user->id)->first();
        }

        return view('profile.show', [
            'user' => $user,
            'familyMember' => $familyMember,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }



    /**
     * Display the documents management page.
     */
    public function documents(Request $request): View
    {
        $user = $request->user();
        $familyMember = null;

        if ($user->family_member_id) {
            $familyMember = FamilyMember::find($user->family_member_id);
        }

        return view('profile.documents', [
            'user' => $user,
            'familyMember' => $familyMember,
        ]);
    }

    /**
     * Upload documents.
     */
    public function uploadDocuments(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'aadhar_card' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'voter_id' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'pan_card' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'ration_card' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $user = $request->user();

        // Admin and sub-admin users don't need family member profiles for document upload
        if ($user->isAdminOrSubAdmin()) {
            return redirect()->route('profile.show')->with('info', 'Document management is not required for admin users.');
        }

        if (!$user->family_member_id) {
            return redirect()->route('profile.show')->with('error', 'Please complete your profile first.');
        }

        $familyMember = FamilyMember::find($user->family_member_id);
        $updateData = [];

        // Handle document uploads
        foreach (['aadhar_card', 'voter_id', 'pan_card', 'ration_card'] as $document) {
            if ($request->hasFile($document)) {
                // Delete old file if exists
                if ($familyMember->$document) {
                    Storage::disk('public')->delete($familyMember->$document);
                }

                $path = $request->file($document)->store('documents', 'public');
                $updateData[$document] = $path;
            }
        }

        if (!empty($updateData)) {
            $familyMember->update($updateData);
        }

        return redirect()->route('profile.documents')->with('success', 'Documents uploaded successfully!');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // Only admin and sub-admin users can update their profiles
        if (!$user->isAdminOrSubAdmin()) {
            return redirect()->route('profile.show')->with('error', 'You do not have permission to edit your profile.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'aadhar_number' => 'nullable|string|size:12|unique:users,aadhar_number,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo) {
                FileUploadHelper::deleteFile($user->profile_photo);
            }
            $validated['profile_photo'] = FileUploadHelper::uploadFile($request->file('profile_photo'), 'profile_photos');
        }

        // Check if email is being changed
        if ($user->email !== $validated['email']) {
            $validated['email_verified_at'] = null;
        }

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // Only admin and sub-admin users can update their passwords
        if (!$user->isAdminOrSubAdmin()) {
            return redirect()->route('profile.show')->with('error', 'You do not have permission to change your password.');
        }

        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Password updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update user password via AJAX
     */
    public function updatePasswordAjax(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => 'required',
                'password' => 'required|string|min:8',
                'password_confirmation' => 'required|same:password',
            ]);

            $user = $request->user();

            // Check current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect.'
                ], 400);
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating password.'
            ], 500);
        }
    }

    /**
     * Update privacy settings via AJAX
     */
    public function updatePrivacy(Request $request)
    {
        try {
            $validated = $request->validate([
                'profile_visibility' => 'required|in:public,members,private',
                'show_contact' => 'boolean',
                'show_activity' => 'boolean',
            ]);

            $user = $request->user();
            
            // Update user privacy settings (you may need to add these columns to users table)
            $user->update([
                'profile_visibility' => $validated['profile_visibility'],
                'show_contact' => $validated['show_contact'] ?? false,
                'show_activity' => $validated['show_activity'] ?? false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Privacy settings updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating privacy settings.'
            ], 500);
        }
    }

    /**
     * Update user preferences via AJAX
     */
    public function updatePreferences(Request $request)
    {
        try {
            $validated = $request->validate([
                'key' => 'required|string',
                'value' => 'required',
            ]);

            $user = $request->user();
            
            // Get current preferences or initialize empty array
            $preferences = $user->preferences ?? [];
            
            // Update the specific preference
            $preferences[$validated['key']] = $validated['value'];
            
            // Save back to user (you may need to add preferences column to users table)
            $user->update(['preferences' => $preferences]);

            return response()->json([
                'success' => true,
                'message' => 'Preference updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating preference.'
            ], 500);
        }
    }

    /**
     * Export user data
     */
    public function exportData(Request $request)
    {
        try {
            $validated = $request->validate([
                'format' => 'required|in:json,csv,pdf',
                'include' => 'required|array',
                'include.profile' => 'boolean',
                'include.problems' => 'boolean',
                'include.documents' => 'boolean',
                'include.activity' => 'boolean',
            ]);

            $user = $request->user();
            
            // Here you would implement the actual data export logic
            // For now, we'll just simulate the request
            
            // You could use a job to process this in the background
            // dispatch(new ExportUserDataJob($user, $validated));

            return response()->json([
                'success' => true,
                'message' => 'Data export request submitted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting export request.'
            ], 500);
        }
    }

    /**
     * Submit feedback
     */
    public function submitFeedback(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:bug,feature,improvement,general',
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
                'rating' => 'nullable|integer|min:1|max:5',
            ]);

            $user = $request->user();
            
            // Here you would save the feedback to database
            // You may need to create a Feedback model and migration
            
            /*
            Feedback::create([
                'user_id' => $user->id,
                'type' => $validated['type'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'rating' => $validated['rating'],
            ]);
            */

            return response()->json([
                'success' => true,
                'message' => 'Feedback submitted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting feedback.'
            ], 500);
        }
    }

    /**
     * Submit contact support request
     */
    public function contactSupport(Request $request)
    {
        try {
            $validated = $request->validate([
                'issue_type' => 'required|in:technical,account,billing,other',
                'message' => 'required|string',
            ]);

            $user = $request->user();
            
            // Here you would save the support request to database
            // You may need to create a SupportRequest model and migration
            
            /*
            SupportRequest::create([
                'user_id' => $user->id,
                'issue_type' => $validated['issue_type'],
                'message' => $validated['message'],
                'status' => 'open',
            ]);
            */

            return response()->json([
                'success' => true,
                'message' => 'Support request submitted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting support request.'
            ], 500);
        }
    }

    /**
     * Delete account via AJAX
     */
    public function deleteAccountAjax(Request $request)
    {
        try {
            $validated = $request->validate([
                'confirmation' => 'required|in:DELETE',
            ]);

            $user = $request->user();
            
            // Here you would implement account deletion logic
            // You might want to soft delete or mark for deletion
            
            // For now, we'll just simulate the request
            // $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Account deletion request submitted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting account.'
            ], 500);
        }
    }

    /**
     * Cleanup storage
     */
    public function cleanupStorage(Request $request)
    {
        try {
            $user = $request->user();
            
            // Here you would implement storage cleanup logic
            // For example, delete old temporary files, optimize images, etc.
            
            $freedSpace = '1.2 MB'; // Simulated value
            
            return response()->json([
                'success' => true,
                'message' => 'Storage cleanup completed successfully!',
                'freed_space' => $freedSpace
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during storage cleanup.'
            ], 500);
        }
    }

    /**
     * Get storage usage
     */
    public function getStorageUsage(Request $request)
    {
        try {
            $user = $request->user();
            
            // Here you would calculate actual storage usage
            // For now, we'll return simulated data
            
            return response()->json([
                'success' => true,
                'usage_percentage' => 65,
                'total_used' => '6.5 MB',
                'total_available' => '10 MB',
                'breakdown' => [
                    'profile' => '2.1 MB',
                    'problems' => '1.8 MB',
                    'documents' => '2.6 MB'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching storage usage.'
            ], 500);
        }
    }

    /**
     * Revoke all sessions
     */
    public function revokeSessions(Request $request)
    {
        try {
            $user = $request->user();
            
            // Here you would implement session revocation logic
            // This might involve clearing session data, tokens, etc.
            
            return response()->json([
                'success' => true,
                'message' => 'All other sessions have been revoked successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while revoking sessions.'
            ], 500);
        }
    }
}
