<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\VaultTransfer;

class HomeController extends Controller
{
    public function home()
    {
        // تاريخ بداية المنظومة
        $systemStartDate = '2026-01-01';
        $systemStartYear = 2026;
        
        // السنة الحالية
        $currentYear = now()->year;
        
        // التحقق من أن المنظومة بدأت
        $systemStarted = now() >= $systemStartDate;
        
        // تحديد السنة المطلوب التحويل عنها
        $requiredYear = null;
        $yearly_transfer = null;
        $hasYearlyTransfer = true; // افتراضياً true
        
        if ($systemStarted && $currentYear > $systemStartYear) {
            // إذا كنا في 2027 أو بعدها، نحتاج تحويل السنة السابقة
            $requiredYear = $currentYear - 1;
            
            // البحث عن التحويل المالي للسنة المطلوبة
            $yearly_transfer = VaultTransfer::where('to_vault_id', 1)
                ->whereYear('created_at', $requiredYear)
                ->first();
                
            $hasYearlyTransfer = !is_null($yearly_transfer);
        }
        
        // التحقق من أن المستخدم يعمل في فرع
        $userHasBranch = auth()->user()->branch_id ? true : false;

        return view('finance.home', compact(
            'yearly_transfer', 
            'hasYearlyTransfer', 
            'userHasBranch', 
            'requiredYear',
            'systemStarted',
            'systemStartYear'
        ));
    }
}