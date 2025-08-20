<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    // Middleware is handled by routes

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notices = Notice::with('creator')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notices.index', compact('notices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.notices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:urgent,important,general',
            'display_location' => 'required|in:marquee,card,both',
            'priority' => 'required|integer|min:0|max:100',
            'author' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        Notice::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'display_location' => $request->display_location,
            'priority' => $request->priority,
            'author' => $request->author ?: Auth::user()->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => Auth::id()
        ]);

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notice $notice)
    {
        return view('admin.notices.show', compact('notice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notice $notice)
    {
        return view('admin.notices.edit', compact('notice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:urgent,important,general',
            'display_location' => 'required|in:marquee,card,both',
            'priority' => 'required|integer|min:0|max:100',
            'author' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        $notice->update([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'display_location' => $request->display_location,
            'priority' => $request->priority,
            'author' => $request->author ?: Auth::user()->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notice $notice)
    {
        $notice->delete();

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice deleted successfully!');
    }

    /**
     * Toggle notice status
     */
    public function toggle(Notice $notice)
    {
        $notice->update(['is_active' => !$notice->is_active]);

        return redirect()->back()
            ->with('success', 'Notice status updated successfully!');
    }
}
