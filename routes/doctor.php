<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorHomeController;
use App\Http\Controllers\Doctor\MedicalFacilityController;

Route::prefix('doctor')->name('doctor.')->middleware(['auth:doctor', 'otp.verified', 'docs.completed'])->group(function () {
    Route::get('/dashboard', [DoctorHomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/licences', [DoctorHomeController::class, 'licences'])->name('licences');
    Route::get('/doctor-mails', [DoctorHomeController::class, 'doctor_mails'])->name('doctor-mails');
    Route::get('/doctor-mails/create', [DoctorHomeController::class, 'doctor_mails_create'])->name('doctor-mails.create');
    Route::get('/my-facility', [DoctorHomeController::class, 'my_facility'])->name('my-facility');
    Route::get('/my-facility/renew', [DoctorHomeController::class, 'renew'])->name('my-facility.renew');
    Route::get('/my-facility/create', [DoctorHomeController::class, 'my_facility_create'])->name('my-facility.create');
    Route::post('/my-facility/store', [DoctorHomeController::class, 'my_facility_store'])->name('my-facility.store');
    Route::put('/my-facility/update', [DoctorHomeController::class, 'my_facility_update'])->name('my-facility.update');
    Route::get('/my-facility/upload-documents', [DoctorHomeController::class, 'my_facility_upload_documents'])->name('my-facility.upload-documents');
    Route::get('logout', [DoctorHomeController::class, 'logout'])->name('logout');

    Route::get('/doctor-mails/{doctorMail}', [DoctorHomeController::class, 'show_mail'])->name('doctor-mails.show');
    Route::put('/doctor-mails/{doctorMail}', [DoctorHomeController::class, 'update_mail'])->name('doctor-emails.update');

    Route::get('/doctor-requests/create', [DoctorHomeController::class, 'create_doctor_request'])->name('doctor-requests.create');
    Route::post('/doctor-requests/store', [DoctorHomeController::class, 'store_doctor_request'])->name('doctor-requests.store');

    Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/change-password', [ProfileController::class, 'change_password'])->name('profile.change-password');
    Route::post('profile/change-password', [ProfileController::class, 'change_password_store'])->name('profile.change-password');
});
