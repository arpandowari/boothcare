<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\PublicReport;
use App\Models\BoothImage;
use App\Models\Booth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminPublicController extends Controller
{
    public function reviewsIndex()
    {
        $reviews = Review::with(['booth', 'user', 'familyMember'])
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approveReview(Review $review)
    {
        $review->update([
            'is_approved' => true,
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        return back()->with('success', 'Review approved successfully');
    }

    public function rejectReview(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review rejected and deleted');
    }

    public function reportsIndex()
    {
        $reports = PublicReport::with(['booth', 'verifiedBy'])
            ->latest()
            ->paginate(20);

        return view('admin.reports.index', compact('reports'));
    }

    public function showReport(PublicReport $report)
    {
        return view('admin.reports.show', compact('report'));
    }

    public function verifyReport(Request $request, PublicReport $report)
    {
        $request->validate([
            'admin_response' => 'nullable|string|max:1000'
        ]);

        $report->update([
            'is_verified' => true,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'status' => 'verified',
            'admin_response' => $request->admin_response
        ]);

        return back()->with('success', 'Report verified successfully');
    }

    public function updateReportStatus(Request $request, PublicReport $report)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,in_progress,resolved,rejected',
            'admin_response' => 'nullable|string|max:1000'
        ]);

        $report->update([
            'status' => $request->status,
            'admin_response' => $request->admin_response,
            'is_verified' => $request->status !== 'pending'
        ]);

        return back()->with('success', 'Report status updated successfully');
    }

    public function boothImagesIndex()
    {
        $booths = Booth::with('images')->where('is_active', true)->get();
        return view('admin.booth-images.index', compact('booths'));
    }

    public function storeBoothImage(Request $request, Booth $booth)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        $path = $request->file('image')->store('booth_images', 'public');

        BoothImage::create([
            'booth_id' => $booth->id,
            'image_path' => $path,
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => true,
            'sort_order' => $booth->images()->max('sort_order') + 1
        ]);

        return back()->with('success', 'Image uploaded successfully');
    }

    public function deleteBoothImage(BoothImage $image)
    {
        if ($image->image_path) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        $image->delete();
        return back()->with('success', 'Image deleted successfully');
    }
}