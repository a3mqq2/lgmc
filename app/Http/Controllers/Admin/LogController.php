<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Log;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;


class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Log::query();

        // Apply filtering based on request parameters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Retrieve logs with relationships loaded for pagination
        $logs = $query->with('user', 'branch')->latest()->paginate(10);

        // Retrieve users and branches for filtering options
        $users = User::all();
        $branches = Branch::all();

        return view('general.logs.index', compact('logs', 'users', 'branches'));
    }

    // Other methods like create, store, show, edit, update, destroy can be added if required
}
