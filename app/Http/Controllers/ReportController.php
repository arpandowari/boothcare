<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpdateRequest;
use App\Models\Problem;
use App\Models\User;
use App\Models\FamilyMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isAdminOrSubAdmin()) {
            abort(403, 'Only administrators can access reports.');
        }

        // Get date range (default to last 30 days)
        $startDate = request('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = request('end_date', Carbon::now()->format('Y-m-d'));
        
        // Generate comprehensive statistics
        $stats = $this->generateStats($startDate, $endDate);
        $charts = $this->generateChartData($startDate, $endDate);
        
        return view('reports.index', compact('stats', 'charts', 'startDate', 'endDate'));
    }

    private function generateStats($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        return [
            // Update Requests Stats
            'update_requests' => [
                'total' => UpdateRequest::whereBetween('created_at', [$start, $end])->count(),
                'pending' => UpdateRequest::whereBetween('created_at', [$start, $end])->where('status', 'pending')->count(),
                'approved' => UpdateRequest::whereBetween('created_at', [$start, $end])->where('status', 'approved')->count(),
                'rejected' => UpdateRequest::whereBetween('created_at', [$start, $end])->where('status', 'rejected')->count(),
                'approval_rate' => $this->calculateApprovalRate($start, $end, 'update_requests'),
            ],
            
            // Problems Stats
            'problems' => [
                'total' => Problem::whereBetween('created_at', [$start, $end])->count(),
                'reported' => Problem::whereBetween('created_at', [$start, $end])->where('status', 'reported')->count(),
                'in_progress' => Problem::whereBetween('created_at', [$start, $end])->where('status', 'in_progress')->count(),
                'resolved' => Problem::whereBetween('created_at', [$start, $end])->where('status', 'resolved')->count(),
                'resolution_rate' => $this->calculateResolutionRate($start, $end),
            ],
            
            // User Activity Stats
            'users' => [
                'total_active' => User::whereBetween('updated_at', [$start, $end])->count(),
                'new_registrations' => User::whereBetween('created_at', [$start, $end])->count(),
                'admin_actions' => $this->getAdminActionCount($start, $end),
            ],
            
            // Performance Metrics
            'performance' => [
                'avg_approval_time' => $this->getAverageApprovalTime($start, $end),
                'avg_resolution_time' => $this->getAverageResolutionTime($start, $end),
                'response_rate' => $this->getResponseRate($start, $end),
            ]
        ];
    }

    private function generateChartData($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = $start->diffInDays($end) + 1;
        
        $dates = [];
        $updateRequests = [];
        $problems = [];
        $approvals = [];
        $rejections = [];
        
        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $dates[] = $date->format('M d');
            
            $dayStart = $date->startOfDay();
            $dayEnd = $date->endOfDay();
            
            $updateRequests[] = UpdateRequest::whereBetween('created_at', [$dayStart, $dayEnd])->count();
            $problems[] = Problem::whereBetween('created_at', [$dayStart, $dayEnd])->count();
            $approvals[] = UpdateRequest::whereBetween('reviewed_at', [$dayStart, $dayEnd])
                ->where('status', 'approved')->count();
            $rejections[] = UpdateRequest::whereBetween('reviewed_at', [$dayStart, $dayEnd])
                ->where('status', 'rejected')->count();
        }
        
        return [
            'dates' => $dates,
            'update_requests' => $updateRequests,
            'problems' => $problems,
            'approvals' => $approvals,
            'rejections' => $rejections,
        ];
    }

    private function calculateApprovalRate($start, $end, $type = 'update_requests')
    {
        if ($type === 'update_requests') {
            $total = UpdateRequest::whereBetween('created_at', [$start, $end])
                ->whereIn('status', ['approved', 'rejected'])->count();
            $approved = UpdateRequest::whereBetween('created_at', [$start, $end])
                ->where('status', 'approved')->count();
        }
        
        return $total > 0 ? round(($approved / $total) * 100, 1) : 0;
    }

    private function calculateResolutionRate($start, $end)
    {
        $total = Problem::whereBetween('created_at', [$start, $end])->count();
        $resolved = Problem::whereBetween('created_at', [$start, $end])
            ->where('status', 'resolved')->count();
        
        return $total > 0 ? round(($resolved / $total) * 100, 1) : 0;
    }

    private function getAdminActionCount($start, $end)
    {
        return UpdateRequest::whereBetween('reviewed_at', [$start, $end])
            ->whereNotNull('reviewed_by')->count();
    }

    private function getAverageApprovalTime($start, $end)
    {
        $requests = UpdateRequest::whereBetween('created_at', [$start, $end])
            ->whereNotNull('reviewed_at')
            ->select('created_at', 'reviewed_at')
            ->get();
        
        if ($requests->isEmpty()) return 0;
        
        $totalHours = $requests->sum(function ($request) {
            return Carbon::parse($request->created_at)->diffInHours($request->reviewed_at);
        });
        
        return round($totalHours / $requests->count(), 1);
    }

    private function getAverageResolutionTime($start, $end)
    {
        $problems = Problem::whereBetween('created_at', [$start, $end])
            ->whereNotNull('actual_resolution_date')
            ->select('created_at', 'actual_resolution_date')
            ->get();
        
        if ($problems->isEmpty()) return 0;
        
        $totalDays = $problems->sum(function ($problem) {
            return Carbon::parse($problem->created_at)->diffInDays($problem->actual_resolution_date);
        });
        
        return round($totalDays / $problems->count(), 1);
    }

    private function getResponseRate($start, $end)
    {
        $total = UpdateRequest::whereBetween('created_at', [$start, $end])->count();
        $responded = UpdateRequest::whereBetween('created_at', [$start, $end])
            ->whereNotNull('reviewed_at')->count();
        
        return $total > 0 ? round(($responded / $total) * 100, 1) : 0;
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdminOrSubAdmin()) {
            abort(403, 'Only administrators can export reports.');
        }

        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $format = $request->input('format', 'csv');
        
        $stats = $this->generateStats($startDate, $endDate);
        $charts = $this->generateChartData($startDate, $endDate);
        
        if ($format === 'json') {
            return response()->json([
                'period' => ['start' => $startDate, 'end' => $endDate],
                'stats' => $stats,
                'charts' => $charts,
                'exported_at' => now()->toISOString(),
                'exported_by' => $user->name
            ]);
        }
        
        // CSV Export
        $filename = 'boothcare_report_' . $startDate . '_to_' . $endDate . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        return response()->stream(function () use ($stats, $charts) {
            $handle = fopen('php://output', 'w');
            
            // Header
            fputcsv($handle, ['Boothcare System Report']);
            fputcsv($handle, ['Generated on: ' . now()->format('Y-m-d H:i:s')]);
            fputcsv($handle, []);
            
            // Update Requests Stats
            fputcsv($handle, ['UPDATE REQUESTS STATISTICS']);
            fputcsv($handle, ['Metric', 'Value']);
            fputcsv($handle, ['Total Requests', $stats['update_requests']['total']]);
            fputcsv($handle, ['Pending', $stats['update_requests']['pending']]);
            fputcsv($handle, ['Approved', $stats['update_requests']['approved']]);
            fputcsv($handle, ['Rejected', $stats['update_requests']['rejected']]);
            fputcsv($handle, ['Approval Rate', $stats['update_requests']['approval_rate'] . '%']);
            fputcsv($handle, []);
            
            // Problems Stats
            fputcsv($handle, ['PROBLEMS STATISTICS']);
            fputcsv($handle, ['Metric', 'Value']);
            fputcsv($handle, ['Total Problems', $stats['problems']['total']]);
            fputcsv($handle, ['Reported', $stats['problems']['reported']]);
            fputcsv($handle, ['In Progress', $stats['problems']['in_progress']]);
            fputcsv($handle, ['Resolved', $stats['problems']['resolved']]);
            fputcsv($handle, ['Resolution Rate', $stats['problems']['resolution_rate'] . '%']);
            fputcsv($handle, []);
            
            // Performance Metrics
            fputcsv($handle, ['PERFORMANCE METRICS']);
            fputcsv($handle, ['Metric', 'Value']);
            fputcsv($handle, ['Average Approval Time (hours)', $stats['performance']['avg_approval_time']]);
            fputcsv($handle, ['Average Resolution Time (days)', $stats['performance']['avg_resolution_time']]);
            fputcsv($handle, ['Response Rate', $stats['performance']['response_rate'] . '%']);
            
            fclose($handle);
        }, 200, $headers);
    }

    public function detailed()
    {
        $user = Auth::user();
        
        if (!$user->isAdminOrSubAdmin()) {
            abort(403, 'Only administrators can access detailed reports.');
        }

        // Get detailed request data
        $updateRequests = UpdateRequest::with(['user', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $problems = Problem::with(['familyMember', 'reportedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('reports.detailed', compact('updateRequests', 'problems'));
    }
}