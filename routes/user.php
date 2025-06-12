<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\DoctorMailController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\Common\VaultController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Common\DoctorController;
use App\Http\Controllers\Common\ReportController;
use App\Http\Controllers\Common\TicketController;
use App\Http\Controllers\Admin\FileTypeController;
use App\Http\Controllers\Common\InvoiceController;
use App\Http\Controllers\Common\LicenceController;
use App\Http\Controllers\DoctorTransferController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\DoctorFileController;
use App\Http\Controllers\Admin\DoctorRankController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Common\DoctorEmailController;
use App\Http\Controllers\Common\TransactionController;
use App\Http\Controllers\MedicalFacilityFileController;
use App\Http\Controllers\Admin\AcademicDegreeController;
use App\Http\Controllers\Common\DoctorRequestController;
use App\Http\Controllers\Common\MedicalFacilityController;
use App\Http\Controllers\Admin\MedicalFacilityTypeController;

Route::prefix('user')->name('user.')->middleware(['auth', 'role:branch_operations'])->group(function () {

    Route::get('/search', [UserController::class, 'search'])->name('search');
    Route::get('/home',   [UserController::class, 'home'])->name('home');

    Route::resource('vaults',       VaultController::class)->only(['index']);
    Route::resource('transactions', TransactionController::class);

    // ========= DOCTORS ROUTES - SPECIFIC ROUTES FIRST ========= //
    Route::get('/doctors/print-list',      [DoctorController::class, 'printList'])->name('doctors.print_list');
    Route::get('/doctors/generate-report', [DoctorController::class, 'generateReport'])->name('doctors.generate_report');
    Route::get('/doctors/preview-report',  [DoctorController::class, 'previewReport'])->name('doctors.preview_report');

    Route::post('/doctors/{doctor}/ban',           [DoctorController::class, 'ban'])->name('doctors.toggle-ban');
    Route::get ('/doctors/{doctor}/print',         [DoctorController::class, 'print'])->name('doctors.print');
    Route::get('doctors/{doctor}/download-all-files', [DoctorController::class, 'downloadAllFiles'])
    ->name('doctors.download-all-files');
    Route::get ('/doctors/{doctor}/print-membership-form',         [DoctorController::class, 'print_membership_form'])->name('doctors.print-membership-form');
    Route::get ('/doctors/{doctor}/print-id',      [DoctorController::class, 'print_id'])->name('doctors.print-id');
    Route::get ('/doctors/{doctor}/print-license', [DoctorController::class, 'print_license'])->name('doctors.print-license');
    Route::post('/doctors/{doctor}/change-status', [DoctorController::class, 'change_status'])->name('doctors.change-status');

    // RESOURCE ROUTE (after specific routes)
    Route::resource('doctors', DoctorController::class);
    // ========= END DOCTORS ROUTES ========= //

    // ===== DOCTOR FILES ROUTES ===== //
    Route::resource('doctors.files', DoctorFileController::class);          // nested resource
    Route::delete('files/{file}', [DoctorFileController::class, 'destroy']) // alias for easy access
        ->name('files.destroy');
    // ===== END DOCTOR FILES ROUTES ===== //





    // ===== DOCTOR FILES ROUTES ===== //
    Route::resource('medical-facilities.files', MedicalFacilityFileController::class);          // nested resource
    Route::delete('medical-facilities/{file}', [MedicalFacilityFileController::class, 'destroy']) // alias for easy access
    ->name('medical-facilities.upload-file');
    Route::delete('medical-facilities/{file}', [MedicalFacilityFileController::class, 'destroy']) // alias for easy access
    ->name('medical-facilities.destroy');
    // ===== END DOCTOR FILES ROUTES ===== //



    Route::delete('medical-facility-files/{file}/destroy',
        [MedicalFacilityController::class, 'file_destroy'])->name('medical-facility-files.destroy');
    Route::resource('medical-facilities', MedicalFacilityController::class);

    Route::resource('doctor-requests', DoctorRequestController::class);

    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::resource('tickets', TicketController::class);

    Route::resource('institutions', InstitutionController::class);

    // ======== DOCTOR TRANSFERS ROUTES ======== //
    Route::get   ('/doctor-transfers/print',  [DoctorTransferController::class, 'print'])->name('doctor-transfers.print');
    Route::patch ('/doctor-transfers/{doctor_transfer}/approve', [DoctorTransferController::class, 'approve'])->name('doctor-transfers.approve');
    Route::patch ('/doctor-transfers/{doctor_transfer}/reject',  [DoctorTransferController::class, 'reject'])->name('doctor-transfers.reject');
    Route::get   ('/doctor-transfers/report', [DoctorTransferController::class, 'report'])->name('doctor-transfers.report');
    Route::resource('doctor-transfers', DoctorTransferController::class);
    // ======== END DOCTOR TRANSFERS ROUTES ======== //

    Route::get ('profile/change-password',  [ProfileController::class, 'change_password'])->name('profile.change-password');
    Route::post('profile/change-password',  [ProfileController::class, 'change_password_store'])->name('profile.change-password');

    // ============ SETTINGS ============ //
    Route::resource('universities',            UniversityController::class);
    Route::resource('doctor_ranks',            DoctorRankController::class);
    Route::resource('academic-degrees',        AcademicDegreeController::class);
    Route::resource('countries',               CountryController::class);
    Route::resource('specialties',             SpecialtyController::class);
    Route::resource('file-types',              FileTypeController::class);
    Route::resource('medical-facility-types',  MedicalFacilityTypeController::class);
    // =========== SETTINGS ============= //

    Route::get ('/invoices/{invoice}/print',    [InvoiceController::class, 'print'])->name('invoices.print');
    Route::post('/invoices/{invoice}/received', [InvoiceController::class, 'received'])->name('invoices.received');
    Route::resource('invoices', InvoiceController::class)->only(['index', 'edit', 'update','destroy','create','store']);


    // user.doctors.files.reorder
    Route::post('/doctors/files/reorder', [DoctorFileController::class, 'reorderFiles'])->name('doctors.files.reorder');


    Route::resource('signatures', SignatureController::class);


    Route::post('/doctors/{doctor}/renew-membership', [DoctorController::class, 'renewMembership'])
    ->name('doctors.renew-membership');


    Route::resource('licences', LicenceController::class);
    Route::get('/licences/{licence}/print', [LicenceController::class, 'print'])->name('licences.print');

    // =========== DOCTOR MAILS ============= //
    Route::resource('doctor-mails', DoctorMailController::class);
    Route::put('doctor-mails/{doctor_mail}/complete', [DoctorMailController::class, 'markComplete'])->name('doctor-mails.complete');
    Route::get('doctor-mails/{doctor_mail}/print', [DoctorMailController::class, 'print'])->name('doctor-mails.print');
    // =========== DOCTOR MAILS ============= //
});
