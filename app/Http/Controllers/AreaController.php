<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;

class AreaController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('areas.view')) {
            abort(403, 'You do not have permission to view areas.');
        }
        
        $areas = Area::withCount(['booths'])->paginate(10);
        return view('areas.index', compact('areas'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('areas.create')) {
            abort(403, 'You do not have permission to create areas.');
        }
        
        return view('areas.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('areas.create')) {
            abort(403, 'You do not have permission to create areas.');
        }
        
        $validated = $request->validate([
            'area_name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        Area::create($validated);

        return redirect()->route('areas.index')->with('success', 'Area created successfully!');
    }

    public function show(Area $area)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('areas.view')) {
            abort(403, 'You do not have permission to view areas.');
        }
        
        $area->load(['booths.houses.members']);
        return view('areas.show', compact('area'));
    }

    public function edit(Area $area)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('areas.edit')) {
            abort(403, 'You do not have permission to edit areas.');
        }
        
        return view('areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'area_name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $area->update($validated);

        return redirect()->route('areas.index')->with('success', 'Area updated successfully!');
    }

    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route('areas.index')->with('success', 'Area deleted successfully!');
    }
}
