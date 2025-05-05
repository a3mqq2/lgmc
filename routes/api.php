<?php

use Faker\Core\File;
use App\Models\Email;
use App\Models\Doctor;
use App\Models\Country;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorMailController;
use App\Http\Controllers\Admin\FileTypeController;
use App\Http\Controllers\Admin\SpecialtyController;

Route::get('/doctors', function (Request $request) {
    $search = $request->get('search');
    return Doctor::where('name', 'like', "%{$search}%")
        ->orWhere('code', 'like', "%{$search}%")
        ->select('id', 'name', 'code', 'type')
        ->limit(10)
        ->get();
});



Route::get('/doctors/{id}', function (Request $request, $id) {
    $doctor = Doctor::findOrFail($id);
    return $doctor;
});



Route::get('/emails', function (Request $request) {
    $search = $request->get('search');
    return Email::where('email', 'like', "%{$search}%")
        ->select('email')
        ->limit(10)
        ->get();
});

Route::post('/emails', function (Request $request) {
    $request->validate([
        'email' => 'required|email|unique:emails,email'
    ]);

    return Email::create([
        'email' => $request->email
    ]);
});

Route::get('/countries', function (Request $request) {
    $search = $request->get('search');
    return Country::where('name', 'like', "%{$search}%")
        ->select('id', 'name')
        ->limit(10)
        ->get();
});

Route::get('/pricing/{id}', function ($id) {
    return Pricing::findOrFail($id);
});

Route::get('/pricings', function (Request $request) {
    return Pricing::where('type', $request->get('type'))
        ->where('doctor_type', $request->get('doctor_type'))
        ->select('id', 'name', 'amount')
        ->get();
});


Route::post('/doctor-mails', [DoctorMailController::class, 'store'])
     ->name('doctor-mails.store');


     Route::get('/get-sub-specialties/{id}', [SpecialtyController::class, 'get_subs']);
     Route::get('file-types', [FileTypeController::class, 'index_api']);