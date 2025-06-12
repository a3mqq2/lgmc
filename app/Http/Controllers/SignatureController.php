<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use App\Models\Branch;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SignatureController extends Controller
{
    /* ---------- index / create / edit ---------- */
    public function index()
    {
        if(get_area_name() == "admin")
        {
            $signatures = Signature::whereNull('branch_id')->get();
        } else {
            $signatures = Signature::where('branch_id', auth()->user()->branch_id)->get();
        }
        return view('general.signatures.index', compact('signatures'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('general.signatures.create', compact('branches'));
    }

    public function edit(Signature $signature)
    {
        $branches = Branch::all();
        return view('general.signatures.edit', compact('signature', 'branches'));
    }

    /* ---------- store ---------- */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'name_en'      => 'required|string|max:255',
            'job_title_ar' => 'nullable|string|max:255',
            'job_title_en' => 'nullable|string|max:255',
            'branch_id'    => 'nullable|exists:branches,id',
            'is_selected'  => 'sometimes|boolean',
        ]);

        DB::transaction(function () use ($data) {
            $signature     = Signature::create($data);
            $wantSelected  = isset($data['is_selected']) && (int) $data['is_selected'] === 1;
            $this->syncBranchSelection($signature, $wantSelected);

            Log::create([
                'user_id'       => auth()->id(),
                'details'       => "إنشاء توقيع: {$signature->name}",
                'loggable_id'   => $signature->id,
                'loggable_type' => Signature::class,
                'action'        => 'create_signature',
            ]);
        });

        return redirect()->route(get_area_name() . '.signatures.index')
                         ->with('success', 'تم الإنشاء بنجاح.');
    }

    /* ---------- update ---------- */
    public function update(Request $request, Signature $signature)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'name_en'      => 'required|string|max:255',
            'job_title_ar' => 'nullable|string|max:255',
            'job_title_en' => 'nullable|string|max:255',
            'branch_id'    => 'nullable|exists:branches,id',
            'is_selected'  => 'sometimes|boolean',
        ]);

        DB::transaction(function () use ($data, $signature) {
            $signature->update($data);
            $wantSelected = isset($data['is_selected']) && (int) $data['is_selected'] === 1;
            $this->syncBranchSelection($signature, $wantSelected);

            Log::create([
                'user_id'       => auth()->id(),
                'details'       => "تعديل توقيع: {$signature->name}",
                'loggable_id'   => $signature->id,
                'loggable_type' => Signature::class,
                'action'        => 'update_signature',
            ]);
        });

        return redirect()->route(get_area_name() . '.signatures.index')
                         ->with('success', 'تم التعديل بنجاح.');
    }

    /* ---------- destroy ---------- */
    public function destroy(Signature $signature)
    {
        DB::transaction(function () use ($signature) {
            $branchId    = $signature->branch_id;
            $wasSelected = $signature->is_selected;
            $sigName     = $signature->name;
            $sigId       = $signature->id;

            $signature->delete();

            // نظّف مؤشر الفرع
            if ($branchId) {
                Branch::where('id', $branchId)
                      ->where('signature_id', $sigId)
                      ->update(['signature_id' => null]);
            }

            if ($wasSelected) {
                $this->ensureBranchHasSelection($branchId, $sigId);
            }

            Log::create([
                'user_id'       => auth()->id(),
                'details'       => "حذف توقيع: {$sigName}",
                'loggable_id'   => $sigId,
                'loggable_type' => Signature::class,
                'action'        => 'delete_signature',
            ]);
        });

        return redirect()->route(get_area_name() . '.signatures.index')
                         ->with('success', 'تم الحذف بنجاح.');
    }

    /* ---------- helpers ---------- */
    private function syncBranchSelection(Signature $signature, bool $wantSelected): void
    {
        $branchId = $signature->branch_id;

        // 1) جعل التوقيع مختاراً لهذا الفرع أو للنقابة العامة
        if ($wantSelected) {
            // ألغِ تمييز الآخرين في نفس النطاق
            Signature::where('id', '!=', $signature->id)
                     ->when($branchId === null,
                            fn($q) => $q->whereNull('branch_id'),
                            fn($q) => $q->where('branch_id', $branchId))
                     ->update(['is_selected' => 0]);

            // حدِّث مؤشر الفرع
            if ($branchId) {
                Branch::where('id', $branchId)->update(['signature_id' => $signature->id]);
            }

            return; // لا عمل آخر مطلوب
        }

        // 2) المستخدم أزال علامة الاختيار
        if ($branchId) {
            Branch::where('id', $branchId)
                  ->where('signature_id', $signature->id)
                  ->update(['signature_id' => null]);
        }

        // تأكّد من وجود بديل مختار
        $this->ensureBranchHasSelection($branchId, $signature->id);
    }

    private function ensureBranchHasSelection(?int $branchId, int $ignoreId = 0): void
    {
        $exists = Signature::when($branchId === null,
                                  fn($q) => $q->whereNull('branch_id'),
                                  fn($q) => $q->where('branch_id', $branchId))
                           ->where('is_selected', 1)
                           ->exists();

        if ($exists) return;

        $replacement = Signature::when($branchId === null,
                                       fn($q) => $q->whereNull('branch_id'),
                                       fn($q) => $q->where('branch_id', $branchId))
                                ->where('id', '!=', $ignoreId)
                                ->first();

        if (!$replacement) return;

        $replacement->update(['is_selected' => 1]);

        if ($branchId) {
            Branch::where('id', $branchId)->update(['signature_id' => $replacement->id]);
        }
    }
}
