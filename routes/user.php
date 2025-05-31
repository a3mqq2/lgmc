<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
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
use App\Http\Controllers\Admin\AcademicDegreeController;
use App\Http\Controllers\Common\DoctorRequestController;
use App\Http\Controllers\Common\MedicalFacilityController;
use App\Http\Controllers\Admin\MedicalFacilityFileController;
use App\Http\Controllers\Admin\MedicalFacilityTypeController;

Route::prefix('user')->name('user.')->middleware('auth','role:branch_operations')->group(function () {
    Route::get('/search', [UserController::class, 'search'])->name('search');
    Route::get('/home', [UserController::class, 'home'])->name('home');
    Route::resource('vaults', VaultController::class)->only(['index']);
    Route::resource("transactions", TransactionController::class);
    Route::get('/doctors/print-list', [DoctorController::class, 'printList'])->name('doctors.print_list');
    Route::post('/doctors/{doctor}/ban', [DoctorController::class, 'ban'])->name('doctors.toggle-ban');
    Route::get('/doctors/{doctor}/print', [DoctorController::class, 'print'])->name('doctors.print');
    Route::get('/doctors/{doctor}/print-id', [DoctorController::class, 'print_id'])->name('doctors.print-id');
    Route::post('/doctors/{doctor}/change-status', [DoctorController::class, 'change_status'])->name('doctors.change-status');
    // Route::post('/doctors/{doctor}/reject', [DoctorController::class, 'reject'])->name('doctors.reject');
    Route::resource('doctors', DoctorController::class);
    Route::resource('doctors.files', DoctorFileController::class)->shallow();
    Route::delete('medical-facility-files/{file}/destroy', [MedicalFacilityController::class, 'file_destroy'])->name('medical-facility-files.destroy');
    Route::resource('medical-facilities', MedicalFacilityController::class);
    Route::resource('doctors.files', DoctorFileController::class)->shallow();

    Route::resource('doctor-requests', DoctorRequestController::class);
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::resource('tickets', TicketController::class);



    Route::resource('institutions', InstitutionController::class);


    // approve and reject doctor transfer route
    Route::patch('/doctor-transfers/{doctor_transfer}/approve', [DoctorTransferController::class, 'approve'])->name('doctor-transfers.approve');
    Route::patch('/doctor-transfers/{doctor_transfer}/reject', [DoctorTransferController::class, 'reject'])->name('doctor-transfers.reject');
    Route::resource('doctor-transfers', DoctorTransferController::class);

    Route::get('profile/change-password', [ProfileController::class, 'change_password'])->name('profile.change-password');
    Route::post('profile/change-password', [ProfileController::class, 'change_password_store'])->name('profile.change-password');


    // ============ SETTINGS ============ //
    Route::resource('universities', UniversityController::class);
    Route::resource('doctor_ranks', DoctorRankController::class);
    Route::resource('academic-degrees', AcademicDegreeController::class);
    Route::resource('countries', CountryController::class);
    Route::resource('specialties', SpecialtyController::class);
    Route::resource('file-types', FileTypeController::class);
    Route::resource('medical-facility-types', MedicalFacilityTypeController::class);
    // =========== SETTINGS ============= //
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::post('/invoices/{invoice}/received', [InvoiceController::class, 'received'])->name('invoices.received');
    Route::get('/doctors/{doctor}/print-license', [DoctorController::class, 'print_license'])->name('doctors.print-license');

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
    
    // =========== DOCTOR MAILS ============= //
});
