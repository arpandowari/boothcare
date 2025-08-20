<?php

namespace App\Services;

use App\Models\Problem;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send welcome email to newly created user
     */
    public function sendWelcomeEmail(User $user, ?string $password = null)
    {
        try {
            $appName = config('app.name');
            $supportEmail = config('mail.from.address');
            $loginUrl = route('login');

            Mail::send('emails.welcome', [
                'user' => $user,
                'password' => $password,
                'appName' => $appName,
                'supportEmail' => $supportEmail,
                'loginUrl' => $loginUrl
            ], function ($message) use ($user, $appName) {
                $message->to($user->email)
                    ->subject("Welcome to $appName!");
            });

            Log::info('Welcome email sent to user: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
        }
    }

    /**
     * Send email notification to admin when new problem is reported
     */
    public function sendAdminProblemNotification(Problem $problem)
    {
        try {
            $admins = User::where('role', 'admin')->where('is_active', true)->get();

            foreach ($admins as $admin) {
                Mail::send('emails.admin-problem-notification', [
                    'problem' => $problem,
                    'admin' => $admin
                ], function ($message) use ($admin, $problem) {
                    $message->to($admin->email)
                        ->subject('New Problem Reported - ' . $problem->title);
                });
            }

            Log::info('Admin problem notification sent for problem ID: ' . $problem->id);
        } catch (\Exception $e) {
            Log::error('Failed to send admin problem notification: ' . $e->getMessage());
        }
    }

    /**
     * Send enhanced status update notification to user with images and detailed notes
     */
    public function sendProblemStatusUpdate(Problem $problem, string $oldStatus, string $newStatus, array $updateData = [])
    {
        try {
            $user = $problem->familyMember->user ?? User::find($problem->reported_by);

            if (!$user || !$user->email) {
                Log::warning('No user email found for problem status update notification');
                return;
            }

            $statusMessages = [
                'reported' => 'Your problem has been reported and is under review by our team.',
                'in_progress' => 'Great news! Your problem has been accepted and work is now in progress.',
                'resolved' => 'Excellent! Your problem has been resolved. Please review the solution and provide your feedback.',
                'closed' => 'Your problem has been closed. If you need further assistance, please contact us.'
            ];

            $statusIcons = [
                'reported' => '📋',
                'in_progress' => '🔧',
                'resolved' => '✅',
                'closed' => '🔒'
            ];

            $statusColors = [
                'reported' => '#f59e0b',
                'in_progress' => '#3b82f6',
                'resolved' => '#10b981',
                'closed' => '#6b7280'
            ];

            // Prepare email data
            $emailData = [
                'problem' => $problem,
                'user' => $user,
                'oldStatus' => $oldStatus,
                'newStatus' => $newStatus,
                'statusMessage' => $statusMessages[$newStatus] ?? 'Your problem status has been updated.',
                'statusIcon' => $statusIcons[$newStatus] ?? '📄',
                'statusColor' => $statusColors[$newStatus] ?? '#6b7280',
                'adminNotes' => $updateData['admin_notes'] ?? null,
                'resolutionNotes' => $updateData['resolution_notes'] ?? null,
                'actualCost' => $updateData['actual_cost'] ?? null,
                'statusUpdateImage' => $updateData['status_update_image'] ?? null,
                'updatedBy' => auth()->user(),
                'updateDate' => now()->format('M d, Y H:i A'),
            ];

            Mail::send('emails.enhanced-problem-status-update', $emailData, function ($message) use ($user, $problem, $newStatus, $statusIcons) {
                $message->to($user->email)
                    ->subject($statusIcons[$newStatus] . ' Problem Status Update - ' . ucfirst(str_replace('_', ' ', $newStatus)));
            });

            Log::info('Enhanced problem status update notification sent', [
                'problem_id' => $problem->id,
                'user_email' => $user->email,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'has_image' => !empty($updateData['status_update_image']),
                'has_notes' => !empty($updateData['resolution_notes'])
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send enhanced problem status update notification: ' . $e->getMessage());
        }
    }

    /**
     * Send feedback confirmation to user
     */
    public function sendFeedbackConfirmation(Problem $problem)
    {
        try {
            $user = $problem->familyMember->user ?? User::find($problem->reported_by);

            if (!$user || !$user->email) {
                Log::warning('No user email found for feedback confirmation');
                return;
            }

            Mail::send('emails.feedback-confirmation', [
                'problem' => $problem,
                'user' => $user
            ], function ($message) use ($user, $problem) {
                $message->to($user->email)
                    ->subject('Thank you for your feedback - ' . $problem->title);
            });

            Log::info('Feedback confirmation sent for problem ID: ' . $problem->id);
        } catch (\Exception $e) {
            Log::error('Failed to send feedback confirmation: ' . $e->getMessage());
        }
    }

    /**
     * Send problem request notification to admins
     */
    public function sendProblemRequestNotification($updateRequest)
    {
        try {
            $admins = User::where('role', 'admin')->orWhere('role', 'super_admin')->where('is_active', true)->get();

            foreach ($admins as $admin) {
                Mail::send('emails.problem-request-notification', [
                    'updateRequest' => $updateRequest,
                    'admin' => $admin
                ], function ($message) use ($admin, $updateRequest) {
                    $requestData = $updateRequest->requested_data;
                    $title = $requestData['title'] ?? 'New Problem Request';
                    $message->to($admin->email)
                        ->subject('New Problem Request - ' . $title);
                });
            }

            Log::info('Problem request notification sent for request ID: ' . $updateRequest->id);
        } catch (\Exception $e) {
            Log::error('Failed to send problem request notification: ' . $e->getMessage());
        }
    }

    /**
     * Send password reset OTP to user's email
     */
    public function sendPasswordResetOTP(string $email, string $otp, string $userName): bool
    {
        try {
            $appName = config('app.name', 'Boothcare');
            $supportEmail = config('mail.from.address');

            Mail::send('emails.password-reset-otp', [
                'userName' => $userName,
                'otp' => $otp,
                'appName' => $appName,
                'supportEmail' => $supportEmail,
                'expiryMinutes' => 10
            ], function ($message) use ($email, $appName) {
                $message->to($email)
                    ->subject("Password Reset OTP - $appName");
            });

            Log::info('Password reset OTP sent to email: ' . $email);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send password reset OTP: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send update request notification
     */
    public function sendUpdateRequestNotification($updateRequest, $action = 'created')
    {
        try {
            $user = $updateRequest->user;
            
            if ($action === 'created') {
                // Notify admins about new update request
                $admins = User::where('role', 'admin')->orWhere('role', 'sub_admin')->where('is_active', true)->get();

                foreach ($admins as $admin) {
                    Mail::send('emails.update-request-admin-notification', [
                        'updateRequest' => $updateRequest,
                        'admin' => $admin,
                        'user' => $user
                    ], function ($message) use ($admin, $updateRequest) {
                        $message->to($admin->email)
                            ->subject('New Update Request - ' . ucfirst($updateRequest->request_type) . ' Update');
                    });
                }

                // Send confirmation to user
                if ($user->email) {
                    Mail::send('emails.update-request-user-confirmation', [
                        'updateRequest' => $updateRequest,
                        'user' => $user
                    ], function ($message) use ($user, $updateRequest) {
                        $message->to($user->email)
                            ->subject('Update Request Submitted - ' . ucfirst($updateRequest->request_type) . ' Update');
                    });
                }

            } elseif ($action === 'approved') {
                // Notify user about approval
                if ($user->email) {
                    Mail::send('emails.update-request-approved', [
                        'updateRequest' => $updateRequest,
                        'user' => $user
                    ], function ($message) use ($user, $updateRequest) {
                        $message->to($user->email)
                            ->subject('Update Request Approved - ' . ucfirst($updateRequest->request_type) . ' Update');
                    });
                }

            } elseif ($action === 'rejected') {
                // Notify user about rejection
                if ($user->email) {
                    Mail::send('emails.update-request-rejected', [
                        'updateRequest' => $updateRequest,
                        'user' => $user
                    ], function ($message) use ($user, $updateRequest) {
                        $message->to($user->email)
                            ->subject('Update Request Rejected - ' . ucfirst($updateRequest->request_type) . ' Update');
                    });
                }
            }

            Log::info('Update request notification sent', [
                'request_id' => $updateRequest->id,
                'action' => $action,
                'request_type' => $updateRequest->request_type
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send update request notification: ' . $e->getMessage());
        }
    }
}
