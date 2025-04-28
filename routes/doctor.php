<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorHomeController;


Route::prefix('doctor')->name('doctor.')->middleware('auth:doctor')->group(function () {
   Route::get('/dashboard', [DoctorHomeController::class, 'dashboard'])->name('dashboard');
   Route::get('logout', [DoctorHomeController::class, 'logout'])->name('logout');
   Route::get('/licences/{licence}/print', [DoctorHomeController::class, 'licence_print'])->name('licences.print');
   Route::get('/tickets/create', [DoctorHomeController::class, 'create_ticket'])->name('tickets.create');
   Route::post('/tickets/store', [DoctorHomeController::class, 'store_ticket'])->name('tickets.store');
   Route::get('/tickets/{ticket}', [DoctorHomeController::class, 'show_ticket'])->name('tickets.show');
   Route::post('/tickets/{ticket}/reply', [DoctorHomeController::class, 'reply_ticket'])->name('tickets.reply');
   Route::delete('/tickets/{ticket}', [DoctorHomeController::class, 'print_ticket'])->name('tickets.print');
   Route::get('/doctor-requests/create', [DoctorHomeController::class, 'create_doctor_request'])->name('doctor-requests.create');
   Route::post('/doctor-requests/store', [DoctorHomeController::class, 'store_doctor_request'])->name('doctor-requests.store');
   Route::get('profile/change-password', [ProfileController::class, 'change_password'])->name('profile.change-password');
   Route::post('profile/change-password', [ProfileController::class, 'change_password_store'])->name('profile.change-password');
});
