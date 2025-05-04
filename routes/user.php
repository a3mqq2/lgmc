<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\Common\VaultController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Common\DoctorController;
use App\Http\Controllers\Common\ReportController;
use App\Http\Controllers\Common\TicketController;
use App\Http\Controllers\Admin\FileTypeController;
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
    Route::post('/doctors/{doctor}/ban', [DoctorController::class, 'ban'])->name('doctors.toggle-ban');
    Route::get('/doctors/{doctor}/print', [DoctorController::class, 'print'])->name('doctors.print');
    Route::get('/doctors/{doctor}/print-id', [DoctorController::class, 'print_id'])->name('doctors.print-id');
    Route::post('/doctors/{doctor}/approve', [DoctorController::class, 'approve'])->name('doctors.approve');
    Route::post('/doctors/{doctor}/reject', [DoctorController::class, 'reject'])->name('doctors.reject');
    Route::resource('doctors', DoctorController::class);
    Route::resource('doctors.files', DoctorFileController::class)->shallow();
    Route::delete('medical-facility-files/{file}/destroy', [MedicalFacilityController::class, 'file_destroy'])->name('medical-facility-files.destroy');
    Route::resource('medical-facilities', MedicalFacilityController::class);
    Route::resource('doctors.files', DoctorFileController::class)->shallow();
    Route::patch('/licences/{licence}/approve', [LicenceController::class, 'approve'])->name('licences.approve');
    Route::patch('/licences/{licence}/payment', [LicenceController::class, 'payment'])->name('licences.payment');
    Route::get('/licences/{licence}/print', [LicenceController::class, 'print'])->name('licences.print');
    Route::resource('licences', LicenceController::class);
    Route::get('/doctor-requests/{doctor_request}/print', [DoctorRequestController::class, 'print'])->name('doctor-requests.print');
    Route::put('/doctor-requests/{doctor_request}/approve', [DoctorRequestController::class, 'approve'])->name('doctor-requests.approve');
    Route::put('/doctor-requests/{doctor_request}/reject', [DoctorRequestController::class, 'reject'])->name('doctor-requests.reject');
    Route::put('/doctor-requests/{doctor_request}/done', [DoctorRequestController::class, 'done'])->name('doctor-requests.done');
    Route::resource('doctor-requests', DoctorRequestController::class);
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::resource('tickets', TicketController::class);
    // ================ REPORTS ================ //
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
    Route::get('/reports/licences', [ReportController::class, 'licences'])->name('reports.licences');
    Route::get('/reports/licences/print', [ReportController::class, 'licences_print'])->name('reports.licences_print');
    Route::get('/reports/transactions/print', [ReportController::class, 'transactions_print'])->name('reports.transactions_print');
    // =============== REPORTS ================ //



    Route::resource('institutions', InstitutionController::class);


    // approve and reject doctor transfer route
    Route::patch('/doctor-transfers/{doctor_transfer}/approve', [DoctorTransferController::class, 'approve'])->name('doctor-transfers.approve');
    Route::patch('/doctor-transfers/{doctor_transfer}/reject', [DoctorTransferController::class, 'reject'])->name('doctor-transfers.reject');
    Route::resource('doctor-transfers', DoctorTransferController::class);

    Route::get('profile/change-password', [ProfileController::class, 'change_password'])->name('profile.change-password');
    Route::post('profile/change-password', [ProfileController::class, 'change_password_store'])->name('profile.change-password');


    // ============ SETTINGS ============ //
    Route::resource('universities', UniversityController::class)->middleware('permission:branch-manager');
    Route::resource('doctor_ranks', DoctorRankController::class)->middleware('permission:branch-manager');
    Route::resource('academic-degrees', AcademicDegreeController::class)->middleware('permission:branch-manager');
    Route::resource('countries', CountryController::class)->middleware('permission:branch-manager');
    Route::resource('specialties', SpecialtyController::class)->middleware('permission:branch-manager');
    Route::resource('file-types', FileTypeController::class)->middleware('permission:branch-manager');
    Route::resource('medical-facility-types', MedicalFacilityTypeController::class)->middleware('permission:branch-manager');
    // =========== SETTINGS ============= //


    Route::resource('doctor-emails', DoctorEmailController::class);
});
