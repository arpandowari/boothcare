<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booth;

class BoothController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('booths.view')) {
            abort(403, 'You do not have permission to view booths.');
        }
        
        $query = Booth::with(['area'])->withCount(['houses']);
        
        // Handle search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            \Log::info('Booth search performed', ['search_term' => $searchTerm]);
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('booth_number', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('booth_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('location', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('constituency', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('area', function($areaQuery) use ($searchTerm) {
                      $areaQuery->where('area_name', 'LIKE', "%{$searchTerm}%")
                               ->orWhere('district', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }
        
        // Handle status filter
        if ($request->filled('status_filter')) {
            $isActive = $request->status_filter === 'active';
            $query->where('is_active', $isActive);
        }
        
        // Handle area filter
        if ($request->filled('area_filter')) {
            $query->where('area_id', $request->area_filter);
        }
        
        $booths = $query->paginate(10)->appends($request->query());

        return view('booths.index', compact('booths'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('booths.create')) {
            abort(403, 'You do not have permission to create booths.');
        }
        
        $areas = \App\Models\Area::where('is_active', true)->get();
        return view('booths.create', compact('areas'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('booths.create')) {
            abort(403, 'You do not have permission to create booths.');
        }
        
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'booth_number' => 'required|string|unique:booths',
            'booth_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'constituency' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        Booth::create($validated);

        return redirect()->route('booths.index')->with('success', 'Booth created successfully!');
    }

    public function show(Booth $booth)
    {
        $booth->load(['houses.members.problems']);
        return view('booths.show', compact('booth'));
    }

    public function edit(Booth $booth)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('booths.edit')) {
            abort(403, 'You do not have permission to edit booths.');
        }
        
        $areas = \App\Models\Area::where('is_active', true)->get();
        return view('booths.edit', compact('booth', 'areas'));
    }

    public function update(Request $request, Booth $booth)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('booths.edit')) {
            abort(403, 'You do not have permission to edit booths.');
        }
        
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'booth_number' => 'required|string|unique:booths,booth_number,' . $booth->id,
            'booth_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'constituency' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $booth->update($validated);

        return redirect()->route('booths.index')->with('success', 'Booth updated successfully!');
    }

    public function destroy(Booth $booth)
    {
        $booth->delete();
        return redirect()->route('booths.index')->with('success', 'Booth deleted successfully!');
    }

    // API method for AJAX calls
    public function getByArea($areaId)
    {
        $booths = Booth::where('area_id', $areaId)->get(['id', 'booth_number', 'booth_name']);
        return response()->json($booths);
    }
}
