<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UpdateRequest;
use App\Models\Problem;
use App\Models\User;
use App\Models\FamilyMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestProblemApproval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:problem-approval';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test problem request approval functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing Problem Request Approval System');
        $this->newLine();

        try {
            // Test 1: Check existing problem requests
            $this->info('1. Checking problem requests...');
            $problemRequests = UpdateRequest::whereIn('request_type', ['problem_creation', 'public_problem_creation'])
                ->where('status', 'pending')
                ->get();
            
            $this->line("   - Found {$problemRequests->count()} pending problem requests");
            
            foreach ($problemRequests as $request) {
                $title = $request->requested_data['title'] ?? 'No title';
                $user = $request->user ? $request->user->name : 'Public User';
                $this->line("   - Request #{$request->id}: {$request->request_type}");
                $this->line("     Title: {$title}");
                $this->line("     User: {$user}");
            }

            if ($problemRequests->count() === 0) {
                $this->warn('   No pending problem requests found');
                return;
            }

            // Test 2: Test approval process
            $this->newLine();
            $this->info('2. Testing approval process...');
            
            $testRequest = $problemRequests->first();
            $this->line("   - Testing with request #{$testRequest->id}");
            
            DB::beginTransaction();
            try {
                // Simulate the applyProblemCreation method
                $requestedData = $testRequest->requested_data;
                
                // Handle both authenticated user and public problem creation requests
                $familyMemberId = null;
                $reportedBy = null;
                
                if ($testRequest->user && $testRequest->user->familyMember) {
                    // Authenticated user with family member
                    $familyMemberId = $testRequest->user->familyMember->id;
                    $reportedBy = $testRequest->user_id;
                    $this->line("   - Authenticated user request");
                    $this->line("     Family Member ID: {$familyMemberId}");
                    $this->line("     Reported By: {$reportedBy}");
                } elseif ($testRequest->target_type === 'App\\Models\\FamilyMember' && $testRequest->target_id) {
                    // Public request with target family member
                    $familyMemberId = $testRequest->target_id;
                    $reportedBy = null;
                    $this->line("   - Public user request");
                    $this->line("     Family Member ID: {$familyMemberId}");
                }

                // Create the problem based on the request
                $problemData = [
                    'family_member_id' => $familyMemberId,
                    'reported_by' => $reportedBy,
                    'title' => $requestedData['title'] ?? 'Problem Report',
                    'description' => $requestedData['description'] ?? 'No description provided',
                    'category' => $requestedData['category'] ?? 'general',
                    'priority' => $requestedData['priority'] ?? 'medium',
                    'status' => 'reported',
                    'reported_date' => now()->toDateString()
                ];

                // Add reporter information for public requests
                if ($testRequest->request_type === 'public_problem_creation') {
                    $reporterInfo = '';
                    if (isset($requestedData['reporter_name'])) {
                        $reporterInfo .= "Reporter: " . $requestedData['reporter_name'];
                    }
                    if (isset($requestedData['reporter_phone'])) {
                        $reporterInfo .= "\nPhone: " . $requestedData['reporter_phone'];
                    }
                    if (isset($requestedData['reporter_email'])) {
                        $reporterInfo .= "\nEmail: " . $requestedData['reporter_email'];
                    }
                    
                    if ($reporterInfo) {
                        $problemData['description'] .= "\n\n--- Reporter Information ---\n" . $reporterInfo;
                    }
                }

                $this->line("   - Problem data prepared:");
                $this->line("     Title: {$problemData['title']}");
                $this->line("     Category: {$problemData['category']}");
                $this->line("     Priority: {$problemData['priority']}");

                $problem = Problem::create($problemData);
                $this->info("   ✅ Problem created successfully with ID: {$problem->id}");

                // Update request status
                $testRequest->update([
                    'status' => 'approved',
                    'reviewed_by' => 1, // Assuming admin user ID 1
                    'reviewed_at' => now(),
                    'review_notes' => 'Test approval - problem created successfully'
                ]);

                $this->info("   ✅ Request status updated to approved");

                DB::commit();
                $this->info("   ✅ Transaction committed successfully");

            } catch (\Exception $e) {
                DB::rollback();
                $this->error("   ❌ Error during approval: " . $e->getMessage());
                $this->line("   📍 Error location: " . $e->getFile() . ":" . $e->getLine());
                return;
            }

            // Test 3: Verify the created problem
            $this->newLine();
            $this->info('3. Verifying created problems...');
            $recentProblems = Problem::orderBy('created_at', 'desc')->limit(5)->get();

            foreach ($recentProblems as $problem) {
                $this->line("   - Problem #{$problem->id}: {$problem->title}");
                $this->line("     Status: {$problem->status}");
                $this->line("     Priority: {$problem->priority}");
                $this->line("     Created: {$problem->created_at->format('Y-m-d H:i:s')}");
            }

            $this->newLine();
            $this->info('✅ Problem request approval test completed successfully!');

        } catch (\Exception $e) {
            $this->error("❌ Test failed: " . $e->getMessage());
            $this->line("📍 Error location: " . $e->getFile() . ":" . $e->getLine());
        }
    }
}
