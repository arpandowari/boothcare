<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use App\Models\FamilyMember;
use App\Models\Review;
use App\Models\PublicReport;
use App\Models\User;
use App\Models\Notice;
use App\Models\PageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PublicBoothController extends Controller
{
    public function index()
    {
        $booths = Booth::with(['area', 'images', 'reviews'])
            ->where('is_active', true)
            ->get();

        // Get admin-managed notices
        $marqueeNotices = Notice::active()->marquee()->byPriority()->get();
        $cardNotices = Notice::active()->card()->byPriority()->take(6)->get();

        // Get admin-managed page settings
        $heroSettings = PageSetting::getBySection('hero');
        $sliderImages = PageSetting::get('slider_images', []);

        return view('public.booth-index', compact('booths', 'marqueeNotices', 'cardNotices', 'heroSettings', 'sliderImages'));
    }

    public function show(Booth $booth)
    {
        $booth->load([
            'area',
            'images',
            'reviews.familyMember',
            'reviews.user',
            'publicReports' => function($query) {
                $query->where('status', '!=', 'rejected')->latest();
            }
        ]);

        $members = FamilyMember::whereHas('house', function($query) use ($booth) {
            $query->where('booth_id', $booth->id);
        })->where('is_active', true)->get();

        $recentReports = $booth->publicReports()->take(10)->get();

        return view('public.booth-show', compact('booth', 'members', 'recentReports'));
    }

    public function memberLogin(Request $request)
    {
        $request->validate([
            'aadhar_number' => 'required|string|size:12',
            'date_of_birth' => 'required|date',
            'member_id' => 'required|exists:family_members,id'
        ]);

        $member = FamilyMember::find($request->member_id);
        
        if (!$member || 
            $member->aadhar_number !== $request->aadhar_number || 
            $member->date_of_birth->format('Y-m-d') !== $request->date_of_birth) {
            return back()->withErrors(['login' => 'Invalid credentials']);
        }

        session(['authenticated_member' => $member->id]);
        
        return back()->with('success', 'Successfully authenticated! You can now view member details.');
    }

    public function memberDetails($memberId)
    {
        if (!session('authenticated_member') || session('authenticated_member') != $memberId) {
            return redirect()->back()->withErrors(['access' => 'Please authenticate to view member details']);
        }

        $member = FamilyMember::with(['house.booth.area', 'problems'])->findOrFail($memberId);
        
        return view('public.member-details', compact('member'));
    }

    public function storeReview(Request $request, Booth $booth)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'reviewer_name' => 'required|string|max:255',
            'reviewer_phone' => 'nullable|string|max:15',
            'family_member_id' => 'nullable|exists:family_members,id'
        ]);

        Review::create([
            'booth_id' => $booth->id,
            'family_member_id' => $request->family_member_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'reviewer_name' => $request->reviewer_name,
            'reviewer_phone' => $request->reviewer_phone,
            'is_approved' => false
        ]);

        $message = $request->family_member_id 
            ? 'Review submitted successfully as authenticated member! It will be visible after admin approval.'
            : 'Review submitted successfully! It will be visible after admin approval.';

        return back()->with('success', $message);
    }

    public function storeReport(Request $request, Booth $booth)
    {
        $request->validate([
            'reporter_name' => 'required|string|max:255',
            'reporter_phone' => 'required|string|max:15',
            'reporter_email' => 'nullable|email|max:255',
            'problem_title' => 'required|string|max:255',
            'problem_description' => 'required|string|max:2000',
            'category' => 'required|string|in:infrastructure,sanitation,electricity,water,security,other',
            'priority' => 'required|string|in:low,medium,high',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'family_member_id' => 'nullable|exists:family_members,id'
        ]);

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('public_reports', 'public');
                $photos[] = $path;
            }
        }

        PublicReport::create([
            'booth_id' => $booth->id,
            'reporter_name' => $request->reporter_name,
            'reporter_phone' => $request->reporter_phone,
            'reporter_email' => $request->reporter_email,
            'problem_title' => $request->problem_title,
            'problem_description' => $request->problem_description,
            'category' => $request->category,
            'priority' => $request->priority,
            'photos' => $photos,
            'status' => 'pending',
            'is_verified' => false,
            'family_member_id' => $request->family_member_id
        ]);

        $message = $request->family_member_id 
            ? 'Community report submitted successfully as authenticated member! It will be reviewed by admin.'
            : 'Report submitted successfully! It will be reviewed by admin.';

        return back()->with('success', $message);
    }

    public function logout()
    {
        session()->forget('authenticated_member');
        return back()->with('success', 'Logged out successfully');
    }

    public function notices(Request $request)
    {
        $query = Notice::active();

        // Filter by type if provided
        if ($request->has('type') && in_array($request->type, ['urgent', 'important', 'general'])) {
            $query->where('type', $request->type);
        }

        $notices = $query->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('public.notices', compact('notices'));
    }

    public function features()
    {
        // Sample images for the slider - you can replace these with actual images
        $sliderImages = [
            [
                'url' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=1200&h=600&fit=crop',
                'title' => 'Community Management',
                'description' => 'Efficient booth and member management system'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=1200&h=600&fit=crop',
                'title' => 'Digital Services',
                'description' => 'Modern digital solutions for community needs'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200&h=600&fit=crop',
                'title' => 'Secure Platform',
                'description' => 'Government-grade security and data protection'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=1200&h=600&fit=crop',
                'title' => 'Community Support',
                'description' => '24/7 support for all community members'
            ]
        ];

        return view('public.features', compact('sliderImages'));
    }
}