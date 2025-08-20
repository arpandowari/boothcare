<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BoothController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\FamilyMemberController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;

use App\Http\Controllers\AdminRequestController;
use App\Http\Controllers\ProblemRequestController;
use App\Http\Controllers\Auth\PasswordResetController;

// Dashboard (requires authentication)
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Test route to debug dashboard issues
Route::get('/test-dashboard', function () {
    $user = Auth::user();
    if (!$user) {
        return 'No user logged in';
    }

    return [
        'user' => $user->name,
        'role' => $user->role,
        'is_admin' => $user->isAdmin(),
        'areas_count' => App\Models\Area::count(),
        'users_count' => App\Models\User::count(),
    ];
})->middleware('auth');

// Area Management
Route::middleware('auth')->resource('areas', AreaController::class);

// Booth Management
Route::middleware('auth')->resource('booths', BoothController::class);

// House Management
Route::middleware('auth')->resource('houses', HouseController::class);

// Family Member Management (directly under houses)
Route::middleware('auth')->resource('members', FamilyMemberController::class);

// Admin Update Requests Management (New System)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/update-requests', [App\Http\Controllers\Admin\UpdateRequestController::class, 'index'])->name('update-requests.index');
    Route::get('/update-requests/{updateRequest}', [App\Http\Controllers\Admin\UpdateRequestController::class, 'show'])->name('update-requests.show');
    Route::post('/update-requests/{updateRequest}/approve', [App\Http\Controllers\Admin\UpdateRequestController::class, 'approve'])->name('update-requests.approve');
    Route::post('/update-requests/{updateRequest}/reject', [App\Http\Controllers\Admin\UpdateRequestController::class, 'reject'])->name('update-requests.reject');
    Route::post('/update-requests/bulk-approve', [App\Http\Controllers\Admin\UpdateRequestController::class, 'bulkApprove'])->name('update-requests.bulk-approve');
    Route::post('/update-requests/bulk-reject', [App\Http\Controllers\Admin\UpdateRequestController::class, 'bulkReject'])->name('update-requests.bulk-reject');
    
    // Notice Management
    Route::resource('notices', App\Http\Controllers\Admin\NoticeController::class);
    Route::post('/notices/{notice}/toggle', [App\Http\Controllers\Admin\NoticeController::class, 'toggle'])->name('notices.toggle');
    
    // Sub-Admin Management (Admin Only)
    Route::resource('sub-admins', App\Http\Controllers\SubAdminController::class);
    Route::post('/sub-admins/{subAdmin}/toggle-status', [App\Http\Controllers\SubAdminController::class, 'toggleStatus'])->name('sub-admins.toggle-status');
    Route::post('/sub-admins/{subAdmin}/permissions', [App\Http\Controllers\SubAdminController::class, 'updatePermissions'])->name('sub-admins.update-permissions');
});

// Admin & Sub-Admin Routes (Full access - no more Super Admin concept)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('areas', AreaController::class)->except(['index', 'show']);
    Route::resource('booths', BoothController::class)->except(['index', 'show']);
    Route::resource('houses', HouseController::class)->except(['index', 'show']);
    Route::resource('problems', ProblemController::class)->except(['index', 'show']);
    Route::resource('users', UserController::class);
    Route::delete('users/{user}/force', [UserController::class, 'forceDestroy'])->name('users.force-destroy');
    
    // Problem status updates (Admin and Sub-Admin can directly change)
    Route::get('/problems/{problem}/status', [ProblemController::class, 'showStatusUpdate'])->name('problems.status-update');
    Route::post('/problems/{problem}/status', [ProblemController::class, 'updateStatus'])->name('problems.update-status');
    
    // Problem request approval/rejection (Admin and Sub-Admin)
    Route::post('/admin-requests/{updateRequest}/approve-problem', [AdminRequestController::class, 'approveProblemRequest'])->name('admin.requests.approve-problem');
    Route::get('/admin-requests/{updateRequest}/approve-problem', [AdminRequestController::class, 'approveProblemRequest'])->name('admin.requests.approve-problem-get');
    Route::post('/admin-requests/{updateRequest}/reject-problem', [AdminRequestController::class, 'rejectProblemRequest'])->name('admin.requests.reject-problem');
    
    // Test route
    Route::get('/test-approve/{updateRequest}', function($updateRequest) {
        return 'Test approve route works! Request ID: ' . $updateRequest . ' User: ' . Auth::user()->name . ' Role: ' . Auth::user()->role;
    })->name('test.approve');
    

});

// Problems Routes (All authenticated users can view their own problems)
Route::middleware('auth')->group(function () {
    Route::get('/problems', [ProblemController::class, 'index'])->name('problems.index');
    Route::get('/problems/{problem}', [ProblemController::class, 'show'])->name('problems.show');
    
    // Problem feedback routes for users
    Route::get('/problems/{problem}/feedback', [ProblemController::class, 'showFeedback'])->name('problems.feedback');
    Route::post('/problems/{problem}/feedback', [ProblemController::class, 'storeFeedback'])->name('problems.store-feedback');
});

// Admin/Sub-Admin Routes (View only + Request system)
Route::middleware(['auth', 'admin'])->group(function () {
    // View-only access
    Route::get('/areas', [AreaController::class, 'index'])->name('areas.index');
    Route::get('/areas/{area}', [AreaController::class, 'show'])->name('areas.show');
    Route::get('/booths', [BoothController::class, 'index'])->name('booths.index');
    Route::get('/booths/{booth}', [BoothController::class, 'show'])->name('booths.show');
    Route::get('/houses', [HouseController::class, 'index'])->name('houses.index');
    Route::get('/houses/{house}', [HouseController::class, 'show'])->name('houses.show');
    Route::get('/members', [FamilyMemberController::class, 'index'])->name('members.index');
    Route::get('/members/{member}', [FamilyMemberController::class, 'show'])->name('members.show');
    
    // Problem request system for authenticated users
    Route::prefix('problem-requests')->name('problem-requests.')->group(function () {
        Route::get('/create', [ProblemRequestController::class, 'create'])->name('create');
        Route::post('/store', [ProblemRequestController::class, 'store'])->name('store');
    });
    
    // Admin request system
    Route::prefix('admin-requests')->name('admin.requests.')->group(function () {
        Route::get('/', [AdminRequestController::class, 'index'])->name('index');
        Route::get('/family-member-update', [AdminRequestController::class, 'requestFamilyMemberUpdate'])->name('family-member-update');
        Route::post('/family-member-update', [AdminRequestController::class, 'storeFamilyMemberUpdate'])->name('store-family-member-update');
        Route::get('/location-update', [AdminRequestController::class, 'requestLocationUpdate'])->name('location-update');
        Route::post('/location-update', [AdminRequestController::class, 'storeLocationUpdate'])->name('store-location-update');

        Route::get('/{request}', [AdminRequestController::class, 'show'])->name('show');
    });
});

// User Update Requests (Users can submit requests)
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/update-requests/create', [App\Http\Controllers\User\UpdateRequestController::class, 'create'])->name('update-requests.create');
    Route::post('/update-requests', [App\Http\Controllers\User\UpdateRequestController::class, 'store'])->name('update-requests.store');
});

// User Management (Admin only) - Already defined above in admin middleware group

// Problem Request System for Regular Users (Family Members)
Route::middleware('auth')->prefix('problem-requests')->name('problem-requests.')->group(function () {
    Route::get('/create', [ProblemRequestController::class, 'create'])->name('create');
    Route::post('/store', [ProblemRequestController::class, 'store'])->name('store');
});

// Profile Management (Authenticated users only)
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::put('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::get('/documents', [ProfileController::class, 'documents'])->name('documents');
    Route::post('/documents', [ProfileController::class, 'uploadDocuments'])->name('upload-documents');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    
    // AJAX API Routes
    Route::put('/password', [ProfileController::class, 'updatePasswordAjax']);
    Route::put('/privacy', [ProfileController::class, 'updatePrivacy']);
    Route::put('/preferences', [ProfileController::class, 'updatePreferences']);
    Route::post('/export-data', [ProfileController::class, 'exportData']);
    Route::post('/feedback', [ProfileController::class, 'submitFeedback']);
    Route::post('/contact-support', [ProfileController::class, 'contactSupport']);
    Route::delete('/delete-account', [ProfileController::class, 'deleteAccountAjax']);
    Route::post('/cleanup-storage', [ProfileController::class, 'cleanupStorage']);
    Route::get('/storage-usage', [ProfileController::class, 'getStorageUsage']);
    Route::post('/revoke-sessions', [ProfileController::class, 'revokeSessions']);
});

// House Members
Route::middleware('auth')->get('/house/members', [HouseController::class, 'houseMembers'])->name('house.members');
Route::middleware('auth')->get('/members/house/{house}', [FamilyMemberController::class, 'houseMembers'])->name('members.house-members');

// Reports (Admin only)
Route::middleware('auth')->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/problems', [ReportController::class, 'problems'])->name('problems');
    Route::get('/members', [ReportController::class, 'members'])->name('members');
});

// Settings (Admin only)
Route::middleware(['auth', 'admin'])->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::post('/update', [SettingsController::class, 'update'])->name('update');
    Route::post('/test-email', [SettingsController::class, 'testEmail'])->name('test-email');
});



// API Routes for AJAX calls
Route::prefix('api')->group(function () {
    Route::get('areas/{area}/booths', [BoothController::class, 'getByArea']);
    Route::get('booths/{booth}/houses', [HouseController::class, 'getByBooth']);
    Route::get('houses/{house}/members', [FamilyMemberController::class, 'getByHouse']);
    Route::get('houses/{house}/families', [FamilyController::class, 'getByHouse']);
    Route::get('families/{family}/members', [FamilyMemberController::class, 'getByFamily']);
    Route::get('members/{member}/problems', [ProblemController::class, 'getByMember']);
});

// Custom Password Reset Routes (for users with Aadhar)
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password-custom', [PasswordResetController::class, 'showRequestForm'])->name('password.request.custom');
    Route::post('/forgot-password-custom', [PasswordResetController::class, 'sendOtp'])->name('password.send-otp');
    Route::get('/verify-otp', [PasswordResetController::class, 'showVerifyOtpForm'])->name('password.verify-otp');
    Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp'])->name('password.verify-otp.post');
    Route::post('/resend-otp', [PasswordResetController::class, 'resendOtp'])->name('password.resend-otp');
    Route::get('/reset-password-custom', [PasswordResetController::class, 'showResetForm'])->name('password.reset.custom');
    Route::post('/reset-password-custom', [PasswordResetController::class, 'resetPassword'])->name('admin.password.reset');
});

// Test Routes (Admin only)
Route::middleware(['auth', 'admin'])->group(function () {
    // Debug problem routes
    Route::get('/debug-problem/{problem}', function($problemId) {
        $problem = \App\Models\Problem::with(['familyMember.user'])->find($problemId);
        if (!$problem) {
            return response()->json(['error' => 'Problem not found']);
        }
        
        $user = Auth::user();
        $familyMember = \App\Models\FamilyMember::where('user_id', $user->id)->first();
        
        return response()->json([
            'problem' => [
                'id' => $problem->id,
                'title' => $problem->title,
                'status' => $problem->status,
                'family_member_id' => $problem->family_member_id,
                'feedback_submitted' => $problem->feedback_submitted
            ],
            'current_user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'is_admin' => $user->isAdminOrSubAdmin()
            ],
            'family_member' => $familyMember ? [
                'id' => $familyMember->id,
                'name' => $familyMember->name,
                'user_id' => $familyMember->user_id
            ] : null,
            'problem_family_member' => $problem->familyMember ? [
                'id' => $problem->familyMember->id,
                'name' => $problem->familyMember->name,
                'user_id' => $problem->familyMember->user_id
            ] : null,
            'can_view' => $user->isAdminOrSubAdmin() || ($familyMember && $problem->family_member_id === $familyMember->id),
            'can_feedback' => $problem->status === 'resolved' && !$problem->feedback_submitted && $familyMember && $problem->family_member_id === $familyMember->id,
            'routes' => [
                'show' => route('problems.show', $problem),
                'feedback' => route('problems.feedback', $problem)
            ]
        ]);
    })->name('debug.problem');

    Route::get('/test-email', function () {
        $emailService = new \App\Services\EmailService();
        $result = $emailService->testEmailConfiguration();
        
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully! Check your inbox.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email. Check your email configuration.'
            ], 500);
        }
    })->name('test.email');

    Route::get('/test-storage', function () {
        $storagePath = storage_path('app/public');
        $publicPath = public_path('storage');
        
        return response()->json([
            'storage_path' => $storagePath,
            'storage_exists' => is_dir($storagePath),
            'storage_writable' => is_writable($storagePath),
            'public_path' => $publicPath,
            'public_exists' => is_dir($publicPath),
            'symlink_exists' => is_link($publicPath),
            'directories' => [
                'profile_photos' => Storage::disk('public')->exists('profile_photos'),
                'documents' => Storage::disk('public')->exists('documents'),
                'problem_photos' => Storage::disk('public')->exists('problem_photos'),
            ]
        ]);
    })->name('test.storage');

    Route::get('/test-auth', function () {
        $users = \App\Models\User::where('role', 'user')
            ->where('is_active', true)
            ->whereNotNull('aadhar_number')
            ->select('id', 'name', 'aadhar_number', 'date_of_birth', 'is_active', 'role')
            ->get();
        
        return response()->json([
            'total_users' => $users->count(),
            'users' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'aadhar_number' => $user->aadhar_number,
                    'date_of_birth' => $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : null,
                    'is_active' => $user->is_active,
                    'role' => $user->role
                ];
            })
        ]);
    })->name('test.auth');
});

// Debug Auth Route (Remove in production)
Route::post('/debug-auth', function (Request $request) {
    $aadharNumber = $request->input('aadhar_number');
    $dateOfBirth = $request->input('date_of_birth');
    
    $user = \App\Models\User::where('aadhar_number', $aadharNumber)
        ->where('role', 'user')
        ->where('is_active', true)
        ->first();
    
    $response = [
        'input_aadhar' => $aadharNumber,
        'input_dob' => $dateOfBirth,
        'user_found' => $user ? true : false,
    ];
    
    if ($user) {
        $response['user_data'] = [
            'id' => $user->id,
            'name' => $user->name,
            'aadhar_number' => $user->aadhar_number,
            'date_of_birth' => $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : null,
            'is_active' => $user->is_active,
            'role' => $user->role
        ];
        
        $response['dob_match'] = $user->date_of_birth && $user->date_of_birth->format('Y-m-d') === $dateOfBirth;
    }
    
    return response()->json($response);
})->name('debug.auth');

// Public Booth Routes (No authentication required)
Route::prefix('public')->name('public.')->group(function () {
    Route::get('/booths', [App\Http\Controllers\PublicBoothController::class, 'index'])->name('booth.index');
    Route::get('/booths/{booth}', [App\Http\Controllers\PublicBoothController::class, 'show'])->name('booth.show');
    Route::post('/booths/{booth}/review', [App\Http\Controllers\PublicBoothController::class, 'storeReview'])->name('booth.review');
    Route::get('/notices', [App\Http\Controllers\PublicBoothController::class, 'notices'])->name('notices');
    Route::post('/booths/{booth}/report', [App\Http\Controllers\PublicBoothController::class, 'storeReport'])->name('booth.report');
    Route::post('/member/login', [App\Http\Controllers\PublicBoothController::class, 'memberLogin'])->name('member.login');
    Route::get('/member/logout', [App\Http\Controllers\PublicBoothController::class, 'logout'])->name('member.logout');
    Route::get('/member/{member}/details', [App\Http\Controllers\PublicBoothController::class, 'memberDetails'])->name('member.details');
});

// Admin Public Management Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Reviews Management
    Route::get('/reviews', [App\Http\Controllers\AdminPublicController::class, 'reviewsIndex'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [App\Http\Controllers\AdminPublicController::class, 'approveReview'])->name('reviews.approve');
    Route::delete('/reviews/{review}/reject', [App\Http\Controllers\AdminPublicController::class, 'rejectReview'])->name('reviews.reject');
    
    // Reports Management
    Route::get('/reports', [App\Http\Controllers\AdminPublicController::class, 'reportsIndex'])->name('reports.index');
    Route::get('/reports/{report}', [App\Http\Controllers\AdminPublicController::class, 'showReport'])->name('reports.show');
    Route::post('/reports/{report}/verify', [App\Http\Controllers\AdminPublicController::class, 'verifyReport'])->name('reports.verify');
    Route::post('/reports/{report}/update-status', [App\Http\Controllers\AdminPublicController::class, 'updateReportStatus'])->name('reports.update-status');
    
    // Booth Images Management
    Route::get('/booth-images', [App\Http\Controllers\AdminPublicController::class, 'boothImagesIndex'])->name('booth-images.index');
    Route::post('/booths/{booth}/images', [App\Http\Controllers\AdminPublicController::class, 'storeBoothImage'])->name('booth-images.store');
    Route::delete('/booth-images/{image}', [App\Http\Controllers\AdminPublicController::class, 'deleteBoothImage'])->name('booth-images.delete');
});

// Main public booth page at root   
Route::get('/', [App\Http\Controllers\PublicBoothController::class, 'index'])->name('home');

// Features page
Route::get('/features', [App\Http\Controllers\PublicBoothController::class, 'features'])->name('features');

// Authentication Routes
require __DIR__ . '/auth.php';
