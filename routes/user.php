<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Common\VaultController;
use App\Http\Controllers\Common\DoctorController;
use App\Http\Controllers\Common\ReportController;
use App\Http\Controllers\Common\TicketController;
use App\Http\Controllers\Common\LicenceController;
use App\Http\Controllers\Admin\DoctorFileController;
use App\Http\Controllers\Common\TransactionController;
use App\Http\Controllers\Common\DoctorRequestController;
use App\Http\Controllers\Common\MedicalFacilityController;
use App\Http\Controllers\Admin\MedicalFacilityFileController;

Route::prefix('user')->name('user.')->middleware('auth','role:branch_operations')->group(function () {
    Route::get('/home', [UserController::class, 'home'])->name('home');
    Route::resource('vaults', VaultController::class)->only(['index']);
    Route::resource("transactions", TransactionController::class);
    Route::get('/doctors/{doctor}/print', [DoctorController::class, 'print'])->name('doctors.print');
    Route::post('/doctors/{doctor}/approve', [DoctorController::class, 'approve'])->name('doctors.approve');
    Route::resource('doctors', DoctorController::class);
    Route::resource('doctors.files', DoctorFileController::class)->shallow();
    Route::resource('medical-facilities', MedicalFacilityController::class);
    Route::resource('medical-facilities.medical-facility-files', MedicalFacilityFileController::class);
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
});
