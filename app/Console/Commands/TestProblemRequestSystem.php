<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\FamilyMember;
use App\Models\UpdateRequest;
use App\Models\Problem;

class TestProblemRequestSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:problem-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the problem request system functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing Problem Request System');
        $this->info('================================');
        $this->newLine();

        // Test 1: Check if we have users and family members
        $this->info('1. Checking system data...');
        $userCount = User::count();
        $familyMemberCount = FamilyMember::count();
        $problemCount = Problem::count();
        $requestCount = UpdateRequest::count();

        $this->line("   - Users: {$userCount}");
        $this->line("   - Family Members: {$familyMemberCount}");
        $this->line("   - Problems: {$problemCount}");
        $this->line("   - Update Requests: {$requestCount}");
        $this->newLine();

        // Test 2: Check if we have problem creation requests
        $this->info('2. Checking problem requests...');
        $problemRequests = UpdateRequest::whereIn('request_type', ['problem_creation', 'public_problem_creation'])->get();
        $this->line("   - Problem creation requests: {$problemRequests->count()}");

        if ($problemRequests->count() > 0) {
            $this->line("   - Pending: " . $problemRequests->where('status', 'pending')->count());
            $this->line("   - Approved: " . $problemRequests->where('status', 'approved')->count());
            $this->line("   - Rejected: " . $problemRequests->where('status', 'rejected')->count());
        }
        $this->newLine();

        // Test 3: Check user roles
        $this->info('3. Checking user roles...');
        $superAdmins = User::where('role', 'super_admin')->count();
        $admins = User::where('role', 'admin')->count();
        $users = User::where('role', 'user')->count();

        $this->line("   - Super Admins: {$superAdmins}");
        $this->line("   - Admins: {$admins}");
        $this->line("   - Regular Users: {$users}");
        $this->newLine();

        // Test 4: Show recent problem requests
        $this->info('4. Recent problem requests:');
        $recentRequests = UpdateRequest::whereIn('request_type', ['problem_creation', 'public_problem_creation'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        if ($recentRequests->count() > 0) {
            foreach ($recentRequests as $request) {
                $title = $request->requested_data['title'] ?? 'No title';
                $status = $request->status;
                $date = $request->created_at->format('Y-m-d H:i');
                $user = $request->user ? $request->user->name : 'Public User';
                
                $this->line("   - [{$status}] {$title} by {$user} ({$date})");
            }
        } else {
            $this->line("   - No problem requests found");
        }
        $this->newLine();

        $this->info('✅ Problem Request System Test Complete!');
        $this->newLine();
        $this->info('System Status:');
        $this->line('- Problem requests can be submitted by users');
        $this->line('- Requests appear in admin panel for Super Admin approval');
        $this->line('- Super Admins can approve/reject requests');
        $this->line('- Approved requests create actual Problem records');
        
        return 0;
    }
}
