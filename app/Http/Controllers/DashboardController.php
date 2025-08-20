<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Booth;
use App\Models\House;
use App\Models\FamilyMember;
use App\Models\Problem;
use App\Models\User;
use App\Models\UpdateRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Debug logging
        \Log::info('Dashboard accessed', [
            'user_id' => $user?->id,
            'user_role' => $user?->role,
            'is_admin' => $user?->isAdminOrSubAdmin(),
            'request_path' => $request->path(),
            'request_url' => $request->url()
        ]);

        if ($user && $user->isAdminOrSubAdmin()) {
            return $this->adminDashboard($request);
        } else {
            return $this->userDashboard();
        }
    }

    private function adminDashboard(Request $request = null)
    {
        $user = Auth::user();
        
        // Get time period from request (default: 'day')
        $period = $request ? $request->get('period', 'day') : 'day';

        // Initialize stats array
        $stats = [];

        // Check permissions for each stat
        if ($user->isAdmin() || $user->hasPermission('areas.view')) {
            $stats['total_areas'] = Area::count();
        }

        if ($user->isAdmin() || $user->hasPermission('booths.view')) {
            $stats['total_booths'] = Booth::count();
        }

        if ($user->isAdmin() || $user->hasPermission('houses.view')) {
            $stats['total_houses'] = House::count();
        }

        if ($user->isAdmin() || $user->hasPermission('members.view')) {
            $stats['total_members'] = FamilyMember::count();
            $stats['house_heads'] = FamilyMember::where('is_family_head', true)->count();
        }

        if ($user->isAdmin() || $user->hasPermission('users.view')) {
            $stats['total_users'] = User::where('role', 'user')->count();
        }

        if ($user->isAdmin() || $user->hasPermission('problems.view')) {
            $stats['total_problems'] = Problem::count();
            $stats['active_problems'] = Problem::whereIn('status', ['reported', 'in_progress'])->count();
            $stats['resolved_problems'] = Problem::where('status', 'resolved')->count();
            $stats['reported_problems'] = Problem::where('status', 'reported')->count();
            $stats['in_progress_problems'] = Problem::where('status', 'in_progress')->count();
            $stats['urgent_problems'] = Problem::where('priority', 'urgent')->count();
            $stats['high_priority'] = Problem::where('priority', 'high')->count();
            $stats['medium_priority'] = Problem::where('priority', 'medium')->count();
            $stats['low_priority'] = Problem::where('priority', 'low')->count();
            $stats['urgent_priority'] = Problem::where('priority', 'urgent')->count();
            $stats['new_problems_today'] = Problem::whereDate('created_at', today())->count();
            $stats['resolved_today'] = Problem::where('status', 'resolved')->whereDate('updated_at', today())->count();
        }

        if ($user->isAdmin() || $user->hasPermission('update_requests.view')) {
            $stats['pending_update_requests'] = UpdateRequest::where('status', 'pending')->count();
            $stats['total_update_requests'] = UpdateRequest::count();
        }

        // Always show database type
        $stats['database_type'] = 'MySQL';

        // Calculate trend percentages (comparing last 7 days vs previous 7 days)
        $lastWeekStart = now()->subDays(7);
        $previousWeekStart = now()->subDays(14);
        
        if ($user->isAdmin() || $user->hasPermission('members.view')) {
            $lastWeekMembers = FamilyMember::where('created_at', '>=', $lastWeekStart)->count();
            $previousWeekMembers = FamilyMember::whereBetween('created_at', [$previousWeekStart, $lastWeekStart])->count();
            $stats['members_trend'] = $previousWeekMembers > 0 ? round((($lastWeekMembers - $previousWeekMembers) / $previousWeekMembers) * 100, 1) : 0;
        }
        
        if ($user->isAdmin() || $user->hasPermission('houses.view')) {
            $lastWeekHouses = House::where('created_at', '>=', $lastWeekStart)->count();
            $previousWeekHouses = House::whereBetween('created_at', [$previousWeekStart, $lastWeekStart])->count();
            $stats['houses_trend'] = $previousWeekHouses > 0 ? round((($lastWeekHouses - $previousWeekHouses) / $previousWeekHouses) * 100, 1) : 0;
        }
        
        if ($user->isAdmin() || $user->hasPermission('problems.view')) {
            $lastWeekProblems = Problem::where('created_at', '>=', $lastWeekStart)->count();
            $previousWeekProblems = Problem::whereBetween('created_at', [$previousWeekStart, $lastWeekStart])->count();
            $stats['problems_trend'] = $previousWeekProblems > 0 ? round((($lastWeekProblems - $previousWeekProblems) / $previousWeekProblems) * 100, 1) : 0;
        }

        // Get recent problems if user has permission
        $recent_problems = collect();
        if ($user->isAdmin() || $user->hasPermission('problems.view')) {
            $recent_problems = Problem::with(['familyMember.house.booth.area', 'reportedBy'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        // Get chart data based on selected period
        $chartData = $this->getChartData($period, $user);

        // Get areas if user has permission
        $areas = collect();
        if ($user->isAdmin() || $user->hasPermission('areas.view')) {
            $areas = Area::with(['booths.houses.members'])->get();
        }

        // Get recent registrations if user has permission
        $recent_registrations = collect();
        if ($user->isAdmin() || $user->hasPermission('users.view')) {
            $recent_registrations = User::where('role', 'user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        // Add area statistics
        foreach ($areas as $area) {
            $area->total_houses = $area->booths->sum(function ($booth) {
                return $booth->houses->count();
            });
            $area->total_members = $area->booths->sum(function ($booth) {
                return $booth->houses->sum(function ($house) {
                    return $house->members->count();
                });
            });
            $area->total_problems = Problem::whereHas('familyMember.house.booth', function ($query) use ($area) {
                $query->where('area_id', $area->id);
            })->count();
        }

        // Add user permissions to the view data
        $userPermissions = $user->isAdmin() ? 'all' : $user->permissions;

        // Calculate percentage for revenue circle
        $percentage = 0;
        if (isset($stats['resolved_problems']) && isset($stats['total_problems']) && $stats['total_problems'] > 0) {
            $percentage = round(($stats['resolved_problems'] / $stats['total_problems']) * 100);
        }
        
        // Add resolution rate to stats
        $stats['resolution_rate'] = $percentage;

        return view('admin.dashboard', compact('stats', 'recent_problems', 'areas', 'recent_registrations', 'user', 'userPermissions', 'percentage', 'chartData', 'period'));
    }

    private function getChartData($period, $user)
    {
        $chartData = [
            'dates' => [],
            'problems' => [],
            'resolved' => [],
            'members' => [],
            'houses' => []
        ];

        switch ($period) {
            case 'day':
                // Last 10 days
                for ($i = 9; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $chartData['dates'][] = $date->format('M d');
                    
                    if ($user->isAdmin() || $user->hasPermission('problems.view')) {
                        $chartData['problems'][] = Problem::whereDate('created_at', $date)->count();
                        $chartData['resolved'][] = Problem::whereDate('updated_at', $date)->where('status', 'resolved')->count();
                    } else {
                        $chartData['problems'][] = 0;
                        $chartData['resolved'][] = 0;
                    }
                    
                    if ($user->isAdmin() || $user->hasPermission('members.view')) {
                        $chartData['members'][] = FamilyMember::whereDate('created_at', $date)->count();
                    } else {
                        $chartData['members'][] = 0;
                    }
                    
                    if ($user->isAdmin() || $user->hasPermission('houses.view')) {
                        $chartData['houses'][] = House::whereDate('created_at', $date)->count();
                    } else {
                        $chartData['houses'][] = 0;
                    }
                }
                break;

            case 'month':
                // Last 12 months
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $startOfMonth = $date->copy()->startOfMonth();
                    $endOfMonth = $date->copy()->endOfMonth();
                    $chartData['dates'][] = $date->format('M Y');
                    
                    if ($user->isAdmin() || $user->hasPermission('problems.view')) {
                        $chartData['problems'][] = Problem::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                        $chartData['resolved'][] = Problem::whereBetween('updated_at', [$startOfMonth, $endOfMonth])->where('status', 'resolved')->count();
                    } else {
                        $chartData['problems'][] = 0;
                        $chartData['resolved'][] = 0;
                    }
                    
                    if ($user->isAdmin() || $user->hasPermission('members.view')) {
                        $chartData['members'][] = FamilyMember::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                    } else {
                        $chartData['members'][] = 0;
                    }
                    
                    if ($user->isAdmin() || $user->hasPermission('houses.view')) {
                        $chartData['houses'][] = House::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                    } else {
                        $chartData['houses'][] = 0;
                    }
                }
                break;

            case 'year':
                // Last 5 years
                for ($i = 4; $i >= 0; $i--) {
                    $year = now()->subYears($i)->year;
                    $startOfYear = now()->setYear($year)->startOfYear();
                    $endOfYear = now()->setYear($year)->endOfYear();
                    $chartData['dates'][] = $year;
                    
                    if ($user->isAdmin() || $user->hasPermission('problems.view')) {
                        $chartData['problems'][] = Problem::whereBetween('created_at', [$startOfYear, $endOfYear])->count();
                        $chartData['resolved'][] = Problem::whereBetween('updated_at', [$startOfYear, $endOfYear])->where('status', 'resolved')->count();
                    } else {
                        $chartData['problems'][] = 0;
                        $chartData['resolved'][] = 0;
                    }
                    
                    if ($user->isAdmin() || $user->hasPermission('members.view')) {
                        $chartData['members'][] = FamilyMember::whereBetween('created_at', [$startOfYear, $endOfYear])->count();
                    } else {
                        $chartData['members'][] = 0;
                    }
                    
                    if ($user->isAdmin() || $user->hasPermission('houses.view')) {
                        $chartData['houses'][] = House::whereBetween('created_at', [$startOfYear, $endOfYear])->count();
                    } else {
                        $chartData['houses'][] = 0;
                    }
                }
                break;
        }

        return $chartData;
    }

    private function userDashboard()
    {
        $user = Auth::user();

        // Admin and sub-admin users don't need family member profiles
        if ($user->isAdminOrSubAdmin()) {
            return $this->adminDashboard();
        }

        // For regular users, find their linked family member
        $familyMember = FamilyMember::where('user_id', $user->id)->first();

        if (!$familyMember) {
            return redirect()->route('dashboard')->with('warning', 'Profile setup required. Please contact administrator.');
        }

        $stats = [
            'my_problems' => Problem::where('family_member_id', $familyMember->id)->count(),
            'active_problems' => Problem::where('family_member_id', $familyMember->id)
                ->whereIn('status', ['reported', 'in_progress'])->count(),
            'resolved_problems' => Problem::where('family_member_id', $familyMember->id)
                ->where('status', 'resolved')->count(),
            'house_members' => FamilyMember::where('house_id', $familyMember->house_id)->count(),
        ];

        $my_problems = Problem::where('family_member_id', $familyMember->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $house_problems = Problem::whereHas('familyMember', function ($query) use ($familyMember) {
            $query->where('house_id', $familyMember->house_id);
        })->where('family_member_id', '!=', $familyMember->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('user.dashboard', compact('stats', 'my_problems', 'house_problems', 'familyMember'));
    }
}
