<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;
use App\Models\FamilyMember;

class FamilyController extends Controller
{
    // This controller is kept for backward compatibility
    // Since we removed families, redirect to members
    
    public function index()
    {
        return redirect()->route('members.index');
    }

    public function getByHouse(House $house)
    {
        // API method for getting family members by house
        $members = $house->members()->with(['problems'])->get();
        return response()->json($members);
    }
}
