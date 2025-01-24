<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\MedicalFacility;

class SearchController extends Controller
{
    public function searchLicensables(Request $request)
    {
        $query = $request->input('query');
        $doctors = Doctor::where('name', 'LIKE', "%{$query}%")->where('branch_id', request('branch_id'))->get(['id', 'name']);
        return response()->json($doctors);
    }

    public function searchFacilities(Request $request)
    {
        $query = $request->input('query');
        $facilities = MedicalFacility::where('name', 'LIKE', "%{$query}%")->where('branch_id', request('branch_id'))->get(['id', 'name']);
        return response()->json($facilities);
    }


    public function searchUsers(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'LIKE', "%{$query}%")->where('branch_id', request('branch_id'))->get(['id', 'name']);
        return response()->json($users);
    }
}
