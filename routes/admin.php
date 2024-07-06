<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\VaultController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LicenceController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CapacityController;
use App\Http\Controllers\Admin\FileTypeController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\TransactionTypeController;
use App\Http\Controllers\Admin\DoctorFileController;
use App\Http\Controllers\Admin\UniversityController; 
use App\Http\Controllers\Admin\AcademicDegreeController;
use App\Http\Controllers\Admin\MedicalFacilityController;
use App\Http\Controllers\Admin\MedicalFacilityFileController;
use App\Http\Controllers\Admin\MedicalFacilityTypeController;

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/home', [AdminController::class, 'home'])->name('home');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/doctors/{doctor}/print', [DoctorController::class, 'print'])->name('doctors.print');
    Route::resource('doctors', DoctorController::class);
    Route::resource('specialties', SpecialtyController::class);
    Route::resource('countries', CountryController::class);
    Route::resource('branches', BranchController::class);
    Route::get('/users/{user}/change-status', [UsersController::class, 'change_status'])->name('users.change-status');
    Route::resource('users', UsersController::class);
    Route::resource('medical-facility-types', MedicalFacilityTypeController::class);
    Route::resource('medical-facilities.medical-facility-files', MedicalFacilityFileController::class);
    Route::resource('universities', UniversityController::class); 
    Route::resource('academic-degrees', AcademicDegreeController::class);
    Route::resource('medical-facilities', MedicalFacilityController::class);
    Route::resource('capacities', CapacityController::class);
    Route::resource('file-types', FileTypeController::class);
    Route::resource("vaults", VaultController::class);
    Route::resource("transaction-types", TransactionTypeController::class);
    Route::resource("transactions", TransactionController::class);
    Route::resource('doctors.files', DoctorFileController::class)->shallow();
    Route::patch('/licences/{licence}/approve', [LicenceController::class, 'approve'])->name('licences.approve');
    Route::get('/licences/{licence}/print', [LicenceController::class, 'print'])->name('licences.print');
    Route::resource('licences', LicenceController::class)->except(['create','store']);
    Route::get('/logs', [LogController::class, 'index'])->name('logs');

     // ================ REPORTS ================ //
     Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
     Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
     Route::get('/reports/licences', [ReportController::class, 'licences'])->name('reports.licences');
     Route::get('/reports/licences/print', [ReportController::class, 'licences_print'])->name('reports.licences_print');
     Route::get('/reports/transactions/print', [ReportController::class, 'transactions_print'])->name('reports.transactions_print');
     // =============== REPORTS ================ //

});

