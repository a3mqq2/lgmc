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
    
            if ($request->filled('log_type')) {
                $query->where('action', $request->log_type);
            }
    
            // Retrieve logs with relationships loaded for pagination
            $logs = $query->with('user', 'branch')->latest()->paginate(10);
    
            // Retrieve users and branches for filtering options
            $users = User::all();
            $branches = Branch::all();
    
            // 📝 Comprehensive list of all log types
            $logTypes = [
                // 🧑‍💼 User Management
                'create_user' => 'إنشاء مستخدم',
                'update_user' => 'تعديل مستخدم',
                'delete_user' => 'حذف مستخدم',
                'login' => 'تسجيل الدخول',
                'logout' => 'تسجيل الخروج',
                'change_password' => 'تغيير كلمة المرور',
    
                // 🏢 Branch Management
                'create_branch' => 'إنشاء فرع',
                'update_branch' => 'تعديل فرع',
                'delete_branch' => 'حذف فرع',
    
                // 👨‍⚕️ Doctor Management
                'create_doctor' => 'إنشاء طبيب',
                'update_doctor' => 'تعديل طبيب',
                'delete_doctor' => 'حذف طبيب',
                'approve_doctor' => 'الموافقة على طبيب',
                'reject_doctor' => 'رفض طبيب',
                'transfer_doctor' => 'نقل طبيب',
                'print_doctor_card' => 'طباعة بطاقة الطبيب',
    
                // 🏥 Medical Facility Management
                'create_medical_facility' => 'إنشاء منشأة طبية',
                'update_medical_facility' => 'تعديل منشأة طبية',
                'delete_medical_facility' => 'حذف منشأة طبية',
                'approve_medical_facility' => 'الموافقة على منشأة طبية',
                'reject_medical_facility' => 'رفض منشأة طبية',
                'print_medical_license' => 'طباعة ترخيص منشأة طبية',
    
                // 💳 Invoice Management
                'create_invoice' => 'إنشاء فاتورة',
                'update_invoice' => 'تعديل فاتورة',
                'delete_invoice' => 'حذف فاتورة',
                'pay_invoice' => 'دفع فاتورة',
                'print_invoice' => 'طباعة فاتورة',
                'cancel_invoice' => 'إلغاء فاتورة',
                'refund_invoice' => 'استرداد فاتورة',
    
                // 💵 Transaction Management
                'create_transaction' => 'إنشاء معاملة مالية',
                'update_transaction' => 'تعديل معاملة مالية',
                'delete_transaction' => 'حذف معاملة مالية',
                'approve_transaction' => 'الموافقة على معاملة',
                'reject_transaction' => 'رفض معاملة',
                'transfer_funds' => 'تحويل أموال',
                'deposit_funds' => 'إيداع أموال',
                'withdraw_funds' => 'سحب أموال',
                'print_transaction' => 'طباعة إيصال معاملة',
    
                // 🏦 Vault Management
                'create_vault' => 'إنشاء حساب',
                'update_vault' => 'تعديل حساب',
                'delete_vault' => 'حذف حساب',
                'open_vault' => 'فتح حساب',
                'close_vault' => 'إغلاق حساب',
                'vault_transfer' => 'تحويل بين الخزنات',
                'approve_vault_transfer' => 'الموافقة على تحويل حساب',
                'reject_vault_transfer' => 'رفض تحويل حساب',
    
                // 📄 File Management
                'upload_file' => 'رفع ملف',
                'delete_file' => 'حذف ملف',
                'update_file' => 'تعديل ملف',
                'download_file' => 'تحميل ملف',
                'view_file' => 'عرض ملف',
                'print_file' => 'طباعة ملف',
    
                // 📝 License Management
                'create_license' => 'إنشاء إذن مزاولة',
                'update_license' => 'تعديل إذن مزاولة',
                'delete_license' => 'حذف إذن مزاولة',
                'approve_license' => 'الموافقة على إذن مزاولة',
                'reject_license' => 'رفض إذن مزاولة',
                'print_license' => 'طباعة إذن مزاولة',
    
                // 🛡️ Permissions & Roles
                'assign_role' => 'تعيين دور',
                'revoke_role' => 'إلغاء تعيين دور',
                'update_permissions' => 'تحديث الصلاحيات',
    
                // 📦 Doctor Transfers
                'request_doctor_transfer' => 'طلب نقل طبيب',
                'approve_doctor_transfer' => 'الموافقة على نقل طبيب',
                'reject_doctor_transfer' => 'رفض نقل طبيب',
    
                // 🏫 Institutions & Universities
                'create_institution' => 'إنشاء جهة عمل',
                'update_institution' => 'تعديل جهة عمل',
                'delete_institution' => 'حذف جهة عمل',
                'create_university' => 'إنشاء جامعة',
                'update_university' => 'تعديل جامعة',
                'delete_university' => 'حذف جامعة',
    
                // 🥇 Academic Degrees & Specialties
                'create_academic_degree' => 'إنشاء درجة علمية',
                'update_academic_degree' => 'تعديل درجة علمية',
                'delete_academic_degree' => 'حذف درجة علمية',
                'create_specialty' => 'إنشاء تخصص',
                'update_specialty' => 'تعديل تخصص',
                'delete_specialty' => 'حذف تخصص',
    
                // 🏷️ Pricing Management
                'create_pricing' => 'إنشاء سعر',
                'update_pricing' => 'تعديل سعر',
                'delete_pricing' => 'حذف سعر',
    
                // 🕹️ General Activities
                'system_backup' => 'نسخة احتياطية للنظام',
                'system_restore' => 'استعادة النظام',
                'settings_update' => 'تحديث الإعدادات',
                'language_change' => 'تغيير اللغة',
                'theme_change' => 'تغيير الثيم',
    
                // 🖨️ Printing Logs
                'print_document' => 'طباعة مستند',
                'print_report' => 'طباعة تقرير',
                'print_receipt' => 'طباعة إيصال',
                'print_contract' => 'طباعة عقد',
                'print_summary' => 'طباعة ملخص',
            ];
    
            return view('general.logs.index', compact('logs', 'users', 'branches', 'logTypes'));
        }
    }
    

