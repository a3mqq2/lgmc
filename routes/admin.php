<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\DoctorMailController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Admin\UsersController;
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
use App\Http\Controllers\Admin\AcademicDegreeController;
use App\Http\Controllers\Common\DoctorRequestController;
use App\Http\Controllers\Common\MedicalFacilityController;
use App\Http\Controllers\Common\TransactionTypeController;
use App\Http\Controllers\Admin\MedicalFacilityFileController;
use App\Http\Controllers\Admin\MedicalFacilityTypeController;

Route::prefix('admin')->name('admin.')->middleware('auth','role:general_admin')->group(function () {
    Route::get('/home', [AdminController::class, 'home'])->name('home');
    Route::get('/search', [UserController::class, 'search'])->name('search');

    Route::post('/doctors/{doctor}/ban', [DoctorController::class, 'ban'])->name('doctors.toggle-ban');
    Route::get('/doctors/print-list', [DoctorController::class, 'printList'])->name('doctors.print_list');
    Route::get('/reports', [ReportController::class, 'index'])->middleware('permission:manage-branches-reports')->name('reports.index');
    Route::get('/doctors/{doctor}/print-id', [DoctorController::class, 'print_id'])->name('doctors.print-id');
    Route::get('/doctors/{doctor}/print', [DoctorController::class, 'print'])->middleware('permission:manage-doctors')->name('doctors.print');
    Route::post('/doctors/import-store', [DoctorController::class, 'import_store'])->middleware('permission:manage-doctors')->name('doctors.import-store');
    Route::get('/doctors/import', [DoctorController::class, 'import'])->middleware('permission:manage-doctors')->name('doctors.import');
    Route::resource('doctors', DoctorController::class)->middleware('permission:manage-doctors');
    Route::resource('specialties', SpecialtyController::class)->middleware('permission:registration-settings');
    Route::resource('countries', CountryController::class)->middleware('permission:registration-settings');
    Route::resource('branches', BranchController::class)->middleware('permission:manage-branches');
    Route::get('/users/{user}/change-status', [UsersController::class, 'change_status'])->name('users.change-status');
    Route::resource('users', UsersController::class)->middleware('permission:manage-staff');
    Route::resource('staffs', StaffController::class);
    Route::resource('medical-facility-types', MedicalFacilityTypeController::class)->middleware('permission:registration-settings');
    Route::resource('universities', UniversityController::class)->middleware('permission:registration-settings'); 
    Route::resource('academic-degrees', AcademicDegreeController::class)->middleware('permission:registration-settings');
    Route::get('medical-facilities/import', [ MedicalFacilityController::class, 'import'])->middleware('permission:manage-medical-facilities')->name('medical-facilities.import');
    Route::post('medical-facilities/import', [ MedicalFacilityController::class, 'import_store'])->middleware('permission:manage-medical-facilities')->name('medical-facilities.import-store');
    Route::resource('medical-facilities', MedicalFacilityController::class)->middleware('permission:manage-medical-facilities');
    Route::patch('medical-facilities/{facility}/approve', 
    [MedicalFacilityController::class,'approve'])
    ->name('medical-facilities.approve');
Route::patch('medical-facilities/{facility}/reject', 
    [MedicalFacilityController::class,'reject'])
    ->name('medical-facilities.reject');
    Route::resource('doctor_ranks', DoctorRankController::class)->middleware('permission:manage-doctors');
    Route::resource('file-types', FileTypeController::class)->middleware('permission:registration-settings');
    
    Route::resource('doctors.files', DoctorFileController::class)->shallow()->middleware('permission:manage-doctors');
    Route::resource('medical-facility-files', DoctorFileController::class)->shallow()->middleware('permission:manage-doctors');
    Route::patch('/licences/{licence}/approve', [LicenceController::class, 'approve'])->middleware('permission:manage-medical-licenses,manage-doctor-permits')->name('licences.approve');
    Route::get('/licences/{licence}/print', [LicenceController::class, 'print'])->middleware('permission:manage-medical-licenses,manage-doctor-permits')->name('licences.print');
    Route::resource('licences', LicenceController::class)->middleware('permission:manage-medical-licenses,manage-doctor-permits');
    Route::resource('pricings', PricingController::class)->middleware('permission:services-pricing');
    Route::resource('blacklists', BlacklistController::class)->middleware('permission:manage-blacklist');
    Route::resource('doctor-requests', DoctorRequestController::class)->middleware('permission:doctor-requests');
    Route::get('/logs', [LogController::class, 'index'])->name('logs')->middleware('permission:logs');
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::resource('tickets', TicketController::class);
     // ================ REPORTS ================ //
     Route::get('/reports', [ReportController::class, 'index'])->middleware('permission:manage-branches-reports')->name('reports.index');
     Route::get('/reports/transactions', [ReportController::class, 'transactions'])->middleware('permission:financial-administration')->name('reports.transactions');
     Route::get('/reports/licences', [ReportController::class, 'licences'])->middleware('permission:manage-branches-reports')->name('reports.licences');
     Route::get('/reports/licences/print', [ReportController::class, 'licences_print'])->middleware('permission:manage-branches-reports')->name('reports.licences_print');
     Route::get('/reports/transactions/print', [ReportController::class, 'transactions_print'])->middleware('permission:manage-branches-reports')->name('reports.transactions_print');
     // =============== REPORTS ================ //

     Route::patch('/doctor-transfers/{doctor_transfer}/approve', [DoctorTransferController::class, 'approve'])->name('doctor-transfers.approve');
     Route::patch('/doctor-transfers/{doctor_transfer}/reject', [DoctorTransferController::class, 'reject'])->name('doctor-transfers.reject');
     Route::resource('doctor-transfers', DoctorTransferController::class);
     
    Route::get('profile/change-password', [ProfileController::class, 'change_password'])->name('profile.change-password');
    Route::post('profile/change-password', [ProfileController::class, 'change_password_store'])->name('profile.change-password');


    Route::post('/doctors/{doctor}/approve', [DoctorController::class, 'approve'])->name('doctors.approve');
    Route::post('/doctors/{doctor}/reject', [DoctorController::class, 'reject'])->name('doctors.reject');

    // =========== DOCTOR MAILS ============= //
    Route::resource('doctor-mails', DoctorMailController::class);
    Route::put(
        'doctor-mails/{doctor_mail}/complete',
        [DoctorMailController::class, 'markComplete']
    )->name('doctor-mails.complete');
    Route::get(
        'doctor-mails/{doctor_mail}/print',
        [DoctorMailController::class, 'print']
    )->name('doctor-mails.print');

    Route::resource('emails', EmailController::class);

    
    // =========== DOCTOR MAILS ============= //

});

