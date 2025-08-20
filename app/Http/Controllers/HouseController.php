<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;
use App\Models\Booth;

class HouseController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('houses.view')) {
            abort(403, 'You do not have permission to view houses.');
        }
        
        $query = House::with(['booth.area', 'members'])
            ->withCount(['members']);
        
        // Handle search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            \Log::info('House search performed', ['search_term' => $searchTerm]);
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('house_number', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('address', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('area', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('pincode', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('booth', function($boothQuery) use ($searchTerm) {
                      $boothQuery->where('booth_name', 'LIKE', "%{$searchTerm}%")
                                 ->orWhere('booth_number', 'LIKE', "%{$searchTerm}%");
                  })
                  ->orWhereHas('booth.area', function($areaQuery) use ($searchTerm) {
                      $areaQuery->where('area_name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }
        
        // Handle booth filter
        if ($request->filled('booth_filter')) {
            $query->where('booth_id', $request->booth_filter);
        }
        
        // Handle status filter
        if ($request->filled('status_filter')) {
            $isActive = $request->status_filter === 'active';
            $query->where('is_active', $isActive);
        }
        
        $houses = $query->paginate(10)->appends($request->query());

        return view('houses.index', compact('houses'));
    }

    public function create(Booth $booth = null)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('houses.create')) {
            abort(403, 'You do not have permission to create houses.');
        }
        
        $booths = Booth::where('is_active', true)->get();
        return view('houses.create', compact('booths', 'booth'));
    }

    public function store(Request $request, Booth $booth = null)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('houses.create')) {
            abort(403, 'You do not have permission to create houses.');
        }
        
        // Debug logging
        \Log::info('House creation attempt', [
            'user_id' => auth()->id(),
            'request_data' => $request->all(),
            'booth_param' => $booth?->id
        ]);
        
        $validated = $request->validate([
            'booth_id' => 'required|exists:booths,id',
            'house_number' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ], [
            'booth_id.required' => 'Please select a booth.',
            'booth_id.exists' => 'Selected booth does not exist.',
            'house_number.required' => 'House number is required.',
            'house_number.max' => 'House number cannot exceed 255 characters.',
            'address.required' => 'Address is required.',
            'address.max' => 'Address cannot exceed 255 characters.',
            'area.max' => 'Area cannot exceed 255 characters.',
            'pincode.max' => 'PIN code cannot exceed 10 characters.',
            'latitude.numeric' => 'Latitude must be a valid number.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.numeric' => 'Longitude must be a valid number.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
        ]);

        // Handle checkbox value properly (checkboxes send "on" when checked, nothing when unchecked)
        $validated['is_active'] = $request->has('is_active');

        // Clean up empty coordinate values
        if (empty($validated['latitude'])) {
            $validated['latitude'] = null;
        }
        if (empty($validated['longitude'])) {
            $validated['longitude'] = null;
        }

        // Check for duplicate house number in the same booth
        $exists = House::where('booth_id', $validated['booth_id'])
            ->where('house_number', $validated['house_number'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['house_number' => 'House number already exists in this booth.'])->withInput();
        }

        House::create($validated);

        if ($booth) {
            return redirect()->route('booths.show', $booth)->with('success', 'House added successfully!');
        }

        return redirect()->route('houses.index')->with('success', 'House created successfully!');
    }

    public function show(House $house)
    {
        $house->load(['booth.area', 'members.problems']);
        return view('houses.show', compact('house'));
    }

    public function edit(House $house)
    {
        $areas = \App\Models\Area::all();
        $booths = Booth::where('is_active', true)->get();
        return view('houses.edit', compact('house', 'areas', 'booths'));
    }

    public function update(Request $request, House $house)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->hasPermission('houses.edit')) {
            abort(403, 'You do not have permission to edit houses.');
        }
        
        $validated = $request->validate([
            'booth_id' => 'required|exists:booths,id',
            'house_number' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ], [
            'booth_id.required' => 'Please select a booth.',
            'booth_id.exists' => 'Selected booth does not exist.',
            'house_number.required' => 'House number is required.',
            'house_number.max' => 'House number cannot exceed 255 characters.',
            'address.required' => 'Address is required.',
            'address.max' => 'Address cannot exceed 255 characters.',
            'area.max' => 'Area cannot exceed 255 characters.',
            'pincode.max' => 'PIN code cannot exceed 10 characters.',
            'latitude.numeric' => 'Latitude must be a valid number.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.numeric' => 'Longitude must be a valid number.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
        ]);

        // Handle checkbox value properly (checkboxes send "on" when checked, nothing when unchecked)
        $validated['is_active'] = $request->has('is_active');

        // Clean up empty coordinate values
        if (empty($validated['latitude'])) {
            $validated['latitude'] = null;
        }
        if (empty($validated['longitude'])) {
            $validated['longitude'] = null;
        }

        // Check for duplicate house number in the same booth (excluding current house)
        $exists = House::where('booth_id', $validated['booth_id'])
            ->where('house_number', $validated['house_number'])
            ->where('id', '!=', $house->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['house_number' => 'House number already exists in this booth.'])->withInput();
        }

        $house->update($validated);

        return redirect()->route('houses.index')->with('success', 'House updated successfully!');
    }

    public function destroy(House $house)
    {
        $house->delete();
        return redirect()->route('houses.index')->with('success', 'House deleted successfully!');
    }

    // API method for getting houses by booth
    public function getByBooth(Booth $booth)
    {
        $houses = $booth->houses()->with(['members'])->get();
        return response()->json($houses);
    }

    // Show house members for the current user
    public function houseMembers()
    {
        $user = auth()->user();
        
        // Admin and sub-admin users should use the houses.index route instead
        if ($user->isAdminOrSubAdmin()) {
            return redirect()->route('houses.index')->with('info', 'As an admin, you can view all houses from the Houses section.');
        }
        
        // For regular users, find their linked family member
        $familyMember = \App\Models\FamilyMember::where('user_id', $user->id)->first();
        
        if (!$familyMember) {
            return redirect()->route('dashboard')->with('warning', 'Profile setup required. Please contact administrator.');
        }

        $house = $familyMember->house;
        
        if (!$house) {
            return redirect()->route('dashboard')->with('warning', 'House information not found. Please contact administrator.');
        }

        $members = $house->members()->with(['problems'])->get();
        
        return view('houses.members', compact('house', 'members', 'familyMember'));
    }
}
