<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorHomeController;
use App\Http\Controllers\DoctorMailController;
use App\Http\Controllers\VisitorDoctorController;
use App\Http\Controllers\Doctor\MedicalFacilityController;

Route::prefix('doctor')->name('doctor.')->middleware(['auth:doctor', 'otp.verified', 'docs.completed'])->group(function () {
    Route::get('/dashboard', [DoctorHomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/licences', [DoctorHomeController::class, 'licences'])->name('licences');
    Route::get('/invoices', [DoctorHomeController::class, 'invoices'])->name('invoices');
    Route::get('/invoices/{invoice}/show', [DoctorHomeController::class, 'invoice_show'])->name('invoices.show');
    Route::get('/doctor-mails', [DoctorHomeController::class, 'doctor_mails'])->name('doctor-mails');
    Route::get('/doctor-mails/create', [DoctorHomeController::class, 'doctor_mails_create'])->name('doctor-mails.create');
    Route::get('doctor-mails/{doctorMail}/edit', [DoctorMailController::class, 'edit'])->name('doctor-mails.edit');
    Route::post('doctor-mails/{doctorMail}/update', [DoctorMailController::class, 'updateRequest'])->name('doctor-mails.update');
    Route::get('/my-facility', [DoctorHomeController::class, 'my_facility'])->name('my-facility');
    Route::get('/my-facility/renew', [DoctorHomeController::class, 'renew'])->name('my-facility.renew');
    Route::get('/my-facility/create', [DoctorHomeController::class, 'my_facility_create'])->name('my-facility.create');
    Route::post('/my-facility/store', [DoctorHomeController::class, 'my_facility_store'])->name('my-facility.store');
    Route::put('/my-facility/update', [DoctorHomeController::class, 'my_facility_update'])->name('my-facility.update');
    Route::get('/my-facility/upload-documents', [DoctorHomeController::class, 'my_facility_upload_documents'])->name('my-facility.upload-documents');
    Route::get('logout', [DoctorHomeController::class, 'logout'])->name('logout');

    Route::get('/doctor-mails/{doctorMail}', [DoctorHomeController::class, 'show_mail'])->name('doctor-mails.show');
    Route::put('/doctor-mails/{doctorMail}', [DoctorHomeController::class, 'update_mail'])->name('doctor-emails.update');

    Route::post('doctor-mails/{doctorMail}/services/{service}/prepare-document', [DoctorMailController::class, 'prepareDocument'])
    ->name('doctor-mails.prepare-document');
    
    Route::get('/doctor-requests/create', [DoctorHomeController::class, 'create_doctor_request'])->name('doctor-requests.create');
    Route::post('/doctor-requests/store', [DoctorHomeController::class, 'store_doctor_request'])->name('doctor-requests.store');

    // ===== روابط الأطباء الزوار =====


        Route::post('visitor-doctors/{visitor}/upload-report', [VisitorDoctorController::class, 'uploadReport'])
        ->name('doctor.visitor-doctors.upload-report');


    Route::prefix('visitor-doctors')->name('visitor-doctors.')->group(function () {
        Route::get('/', [VisitorDoctorController::class, 'index'])->name('index');
        Route::get('/create', [VisitorDoctorController::class, 'create'])->name('create');
        Route::post('/', [VisitorDoctorController::class, 'register_store'])->name('store');
        Route::get('/{visitorDoctor}', [VisitorDoctorController::class, 'show'])->name('show');
        Route::get('/{visitorDoctor}/edit', [VisitorDoctorController::class, 'edit'])->name('edit');
        Route::put('/{doctor}', [VisitorDoctorController::class, 'update'])->name('update');
        Route::delete('/{visitorDoctor}', [VisitorDoctorController::class, 'destroy'])->name('destroy');
        Route::get('/{visitorDoctor}/extend', [VisitorDoctorController::class, 'extend'])->name('extend');
        Route::get('/{doctor}/upload-documents', [VisitorDoctorController::class, 'upload_documents'])->name('upload-documents');
        // complete_registration
        Route::get('/{doctor}/complete-registration', [VisitorDoctorController::class, 'complete_registration'])->name('complete_registration');
        Route::post('/{doctor}/renew', [VisitorDoctorController::class, 'renewSubscription'])
        ->name('renew');
    });

    Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/change-password', [ProfileController::class, 'change_password'])->name('profile.change-password');
    Route::post('profile/change-password', [ProfileController::class, 'change_password_store'])->name('profile.change-password');


    
});