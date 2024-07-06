<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VaultController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LicenceController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\DoctorFileController;
use App\Http\Controllers\Admin\MedicalFacilityController;
use App\Http\Controllers\Admin\MedicalFacilityFileController;

Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::get('/home', [UserController::class, 'home'])->name('home');
    Route::resource('vaults', VaultController::class)->only(['index']);
    Route::resource("transactions", TransactionController::class);
    Route::get('/doctors/{doctor}/print', [DoctorController::class, 'print'])->name('doctors.print');
    Route::resource('doctors', DoctorController::class);
    Route::resource('doctors.files', DoctorFileController::class)->shallow();
    Route::resource('medical-facilities', MedicalFacilityController::class);
    Route::resource('medical-facilities.medical-facility-files', MedicalFacilityFileController::class);
    Route::resource('doctors.files', DoctorFileController::class)->shallow();
    Route::patch('/licences/{licence}/approve', [LicenceController::class, 'approve'])->name('licences.approve');
    Route::patch('/licences/{licence}/payment', [LicenceController::class, 'payment'])->name('licences.payment');
    Route::get('/licences/{licence}/print', [LicenceController::class, 'print'])->name('licences.print');
    Route::resource('licences', LicenceController::class);

    // ================ REPORTS ================ //
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
    Route::get('/reports/licences', [ReportController::class, 'licences'])->name('reports.licences');
    Route::get('/reports/licences/print', [ReportController::class, 'licences_print'])->name('reports.licences_print');
    Route::get('/reports/transactions/print', [ReportController::class, 'transactions_print'])->name('reports.transactions_print');
    // =============== REPORTS ================ //
});
