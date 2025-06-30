<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\DoctorMailController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Common\VaultController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\PricingController;
use App\Http\Controllers\Common\DoctorController;
use App\Http\Controllers\Common\ReportController;
use App\Http\Controllers\Common\TicketController;
use App\Http\Controllers\Admin\FileTypeController;
use App\Http\Controllers\Common\InvoiceController;
use App\Http\Controllers\Common\LicenceController;
use App\Http\Controllers\DoctorTransferController;
use App\Http\Controllers\Admin\BlacklistController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\DoctorFileController;
use App\Http\Controllers\Admin\DoctorRankController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Common\TransactionController;
use App\Http\Controllers\MedicalFacilityFileController;
use App\Http\Controllers\Admin\AcademicDegreeController;
use App\Http\Controllers\Common\DoctorRequestController;
use App\Http\Controllers\Common\MedicalFacilityController;
use App\Http\Controllers\Common\TransactionTypeController;
use App\Http\Controllers\Admin\MedicalFacilityTypeController;
use App\Http\Controllers\SignatureController;

Route::prefix('admin')->name('admin.')->middleware('auth','role:general_admin')->group(function () {
Route::get('/home', [AdminController::class, 'home'])->name('home');
Route::get('/search', [UserController::class, 'search'])->name('search');

Route::post('/doctors/{doctor}/ban', [DoctorController::class, 'ban'])->name('doctors.toggle-ban');
Route::get('/doctors/print-list', [DoctorController::class, 'printList'])->name('doctors.print_list');
Route::get('/doctors/generate-report', [DoctorController::class, 'generateReport'])->name('doctors.generate_report');
Route::get('/doctors/preview-report', [DoctorController::class, 'previewReport'])->name('doctors.preview_report');
Route::get ('/doctors/{doctor}/print-membership-form',         [DoctorController::class, 'print_membership_form'])->name('doctors.print-membership-form');


Route::post('/invoices/print-multiple', [InvoiceController::class, 'printMultiple'])
->name('invoices.print-multiple');


Route::post('/doctors/{doctor}/renew-membership', [DoctorController::class, 'renewMembership'])
    ->name('user.doctors.renew-membership');

    Route::post('/doctors/{doctor}/add-card', [DoctorController::class, 'addCard'])
    ->name('doctors.add-card');
    
// Route لتجديد العضوية - للإدارة  
Route::post('/doctors/{doctor}/renew-membership', [DoctorController::class, 'renewMembership'])
    ->name('doctors.renew-membership');


Route::get('/reports', [ReportController::class, 'index'])->middleware('permission:manage-branches-reports')->name('reports.index');
Route::get('/doctors/{doctor}/print-id', [DoctorController::class, 'print_id'])->name('doctors.print-id');
Route::get('/doctors/{doctor}/print', [DoctorController::class, 'print'])->name('doctors.print');
Route::get('/doctors/{doctor}/print-license', [DoctorController::class, 'print_license'])->name('doctors.print-license');
Route::post('/doctors/import-store', [DoctorController::class, 'import_store'])->name('doctors.import-store');
Route::get('/doctors/import', [DoctorController::class, 'import'])->name('doctors.import');
Route::resource('doctors', DoctorController::class);
Route::resource('specialties', SpecialtyController::class)->middleware('permission:addons');
Route::resource('countries', CountryController::class)->middleware('permission:addons');
Route::resource('branches', BranchController::class);
Route::get('/users/{user}/change-status', [UsersController::class, 'change_status'])->name('users.change-status');
Route::resource('users', UsersController::class)->middleware('permission:manage-staff');
Route::resource('staffs', StaffController::class);
Route::resource('medical-facility-types', MedicalFacilityTypeController::class)->middleware('permission:addons');
Route::resource('universities', UniversityController::class)->middleware('permission:addons'); 
Route::resource('academic-degrees', AcademicDegreeController::class)->middleware('permission:addons');
Route::get('medical-facilities/import', [ MedicalFacilityController::class, 'import'])->name('medical-facilities.import');
Route::post('medical-facilities/import', [ MedicalFacilityController::class, 'import_store'])->name('medical-facilities.import-store');
Route::resource('medical-facilities', MedicalFacilityController::class);

Route::post('/medical-facilities/{medicalFacility}/change-status', [MedicalFacilityController::class, 'change_status'])->name('medical-facilities.change-status');


Route::resource('doctor_ranks', DoctorRankController::class);
Route::resource('file-types', FileTypeController::class)->middleware('permission:addons');

Route::resource('doctors.files', DoctorFileController::class)->shallow();
Route::resource('medical-facility-files', DoctorFileController::class)->shallow();
Route::patch('/licences/{licence}/approve', [LicenceController::class, 'approve'])->middleware('permission:manage-medical-licenses,manage-doctor-permits')->name('licences.approve');
Route::get('/licences/{licence}/print', [LicenceController::class, 'print'])->name('licences.print');
Route::resource('licences', LicenceController::class);
Route::resource('pricings', PricingController::class)->middleware('permission:services-pricing');
Route::resource('blacklists', BlacklistController::class)->middleware('permission:manage-blacklist');
Route::resource('doctor-requests', DoctorRequestController::class)->middleware('permission:doctor-requests');
Route::get('/logs', [LogController::class, 'index'])->name('logs')->middleware('permission:logs');
Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
Route::resource('tickets', TicketController::class);
    // ================ REPORTS ================ //
    Route::get('/reports', [ReportController::class, 'index'])->middleware('permission:manage-branches-reports')->name('reports.index');
    Route::get('/reports/transactions', [ReportController::class, 'transactions'])->middleware('permission:finance-general')->name('reports.transactions');
    Route::get('/reports/licences', [ReportController::class, 'licences'])->middleware('permission:manage-branches-reports')->name('reports.licences');
    Route::get('/reports/licences/print', [ReportController::class, 'licences_print'])->middleware('permission:manage-branches-reports')->name('reports.licences_print');
    Route::get('/reports/transactions/print', [ReportController::class, 'transactions_print'])->middleware('permission:manage-branches-reports')->name('reports.transactions_print');
    // =============== REPORTS ================ //

    Route::patch('/doctor-transfers/{doctor_transfer}/approve', [DoctorTransferController::class, 'approve'])->name('doctor-transfers.approve');
    Route::patch('/doctor-transfers/{doctor_transfer}/reject', [DoctorTransferController::class, 'reject'])->name('doctor-transfers.reject');
    Route::resource('doctor-transfers', DoctorTransferController::class);
    
Route::get('profile/change-password', [ProfileController::class, 'change_password'])->name('profile.change-password');
Route::post('profile/change-password', [ProfileController::class, 'change_password_store'])->name('profile.change-password');





// ===== DOCTOR FILES ROUTES ===== //
Route::post('medical-facilities/{medicalFacility}', [MedicalFacilityFileController::class, 'store'])->name('medical-facilities.upload-file');
// destroy
Route::delete('medical-facility-files/{file}/destroy', [MedicalFacilityFileController::class, 'destroy'])->name('medical-facility-files.destroy');
// /{{ get_area_name() }}/medical-facilities/${medicalFacilityId}/files/${fileId} | for edit file
Route::put('medical-facilities/{medicalFacility}/files/{fileId}', [MedicalFacilityFileController::class, 'update'])->name('medical-facility-files.edit');

// print license medical facilities
Route::get('medical-facilities/{medicalFacility}/print-license', [MedicalFacilityController::class, 'print_license'])->name('medical-facilities.print-license');



// ===== END DOCTOR FILES ROUTES ===== //



Route::post('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
Route::post('/invoices/{invoice}/received', [InvoiceController::class, 'received'])->name('invoices.received');
Route::resource('institutions', InstitutionController::class);

Route::get('doctors/{doctor}/download-all-files', [DoctorController::class, 'downloadAllFiles'])
->name('doctors.download-all-files');

Route::resource('invoices', InvoiceController::class)->only(['index', 'edit', 'update','destroy','create','store']);

// Route::post('/doctors/{doctor}/approve', [DoctorController::class, 'approve'])->name('doctors.approve');
// Route::post('/doctors/{doctor}/reject', [DoctorController::class, 'reject'])->name('doctors.reject');
Route::patch('doctor-mails/{doctorMail}/approve', [DoctorMailController::class, 'approve'])->name('doctor-mails.approve');
Route::patch('doctor-mails/{doctorMail}/reject', [DoctorMailController::class, 'reject'])->name('doctor-mails.reject');    
Route::post('/doctors/{doctor}/change-status', [DoctorController::class, 'change_status'])->name('doctors.change-status');

// save-file-to-doctor
Route::post('/doctor-mails/{DoctorMail}/save-file-to-doctor', [DoctorController::class, 'saveFileToDoctor'])->name('doctor-mails.save-file-to-doctor');

// =========== DOCTOR MAILS ============= //
Route::resource('doctor-mails', DoctorMailController::class);
Route::patch(
    'doctor-mails/{doctor_mail}/complete',
    [DoctorMailController::class, 'markComplete']
)->name('doctor-mails.complete');
Route::get(
    'doctor-mails/{doctor_mail}/print',
    [DoctorMailController::class, 'print']
)->name('doctor-mails.print');


Route::post('doctor-mails/{doctorMail}/services/{service}/prepare-document', [DoctorMailController::class, 'prepareDocument'])
    ->name('doctor-mails.prepare-document');

// راوت صفحة الطباعة
Route::get('document-preparations/{documentPreparation}/print', [DoctorMailController::class, 'printDocumentPreparation'])
    ->name('document-preparations.print');

// راوت تصدير PDF
Route::get('document-preparations/{documentPreparation}/export-pdf', [DoctorMailController::class, 'exportToPdf'])
    ->name('document-preparations.export-pdf');

// راوت عرض تفاصيل إعداد المستند
Route::get('doctor-mails/{doctorMail}/document-preparations/{documentPreparation}', [DoctorMailController::class, 'showDocumentPreparation'])
    ->name('doctor-mails.document-preparations.show');

// راوت إكمال إعداد المستند
Route::patch('doctor-mails/{doctorMail}/document-preparations/{documentPreparation}/complete', [DoctorMailController::class, 'completeDocumentPreparation'])
    ->name('doctor-mails.document-preparations.complete');


Route::resource('emails', EmailController::class);



Route::patch('/doctor-transfers/{doctor_transfer}/approve', [DoctorTransferController::class, 'approve'])->name('doctor-transfers.approve');
Route::patch('/doctor-transfers/{doctor_transfer}/reject', [DoctorTransferController::class, 'reject'])->name('doctor-transfers.reject');
Route::resource('doctor-transfers', DoctorTransferController::class);

Route::resource('signatures', SignatureController::class);


// =========== DOCTOR MAILS ============= //

});

