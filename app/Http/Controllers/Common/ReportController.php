<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Vault;
use App\Models\Branch;
use App\Models\Licence;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $vaults = Vault::when(get_area_name() == "user", function ($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->get();

        $branches = Branch::all();
        return view('general.reports.index', compact('vaults', 'branches'));
    }

    public function transactions(Request $request)
    {
        $request->validate([
            "from_date" => "required|date",
            "to_date" => "required|date",
        ]);

        $transactions = Transaction::query();

        if ($request->vault_id) {
            $transactions->where('vault_id', $request->vault_id);
        }

        if (get_area_name() == "user") {
            $transactions->where('branch_id', auth()->user()->branch_id);
        }

        $from = Carbon::parse($request->from_date)->startOfDay();
        $to = Carbon::parse($request->to_date)->endOfDay();

        $transactions->whereBetween('created_at', [$from, $to]);

        $transactions = $transactions->orderByDesc('id')->paginate(10);

        return view('general.reports.transactions', compact('transactions'));
    }

    public function transactions_print(Request $request)
    {
        $request->validate([
            "from_date" => "required|date",
            "to_date" => "required|date",
        ]);

        $transactions = Transaction::query();

        if ($request->vault_id) {
            $transactions->where('vault_id', $request->vault_id);
        }

        if (get_area_name() == "user") {
            $transactions->where('branch_id', auth()->user()->branch_id);
        }

        $from = Carbon::parse($request->from_date)->startOfDay();
        $to = Carbon::parse($request->to_date)->endOfDay();

        $transactions->whereBetween('created_at', [$from, $to]);

        $transactions = $transactions->orderByDesc('id')->paginate(10);

        return view('general.reports.transactions_print', compact('transactions'));
    }

    public function licences(Request $request)
    {
        $request->validate([
            "from_date" => "required|date",
            "to_date" => "required|date",
        ]);

        $query = Licence::query();

        if (get_area_name() == "user") {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        if ($request->licensable_type) {
            $query->where('licensable_type', $request->licensable_type);
        }

        $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        $query->where('status', 'active');
        $licences = $query->orderByDesc('id')->get();

        return view('general.reports.licences', compact('licences'));
    }

    public function licences_print(Request $request)
    {
        $request->validate([
            "from_date" => "required|date",
            "to_date" => "required|date",
        ]);

        $query = Licence::query();

        if (get_area_name() == "user") {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        if ($request->licensable_type) {
            $query->where('licensable_type', $request->licensable_type);
        }

        $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        $query->where('status', 'active');
        $licences = $query->orderByDesc('id')->get();

        return view('general.reports.licences_print', compact('licences'));
    }
}
