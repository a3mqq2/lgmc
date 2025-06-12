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




Route::post('/emails', function (Request $request) {
    $request->validate([
        'email' => 'required|email|unique:emails,email'
    ]);

    return Email::create([
        'email' => $request->email
    ]);
});

Route::get('/emails', function (Request $request) {
    return Email::select('id', 'email')
        ->limit(100)  
        ->get()
        ->map(function($email) {
            return [
                'id' => $email->id,
                'email' => $email->email,
                'label' => $email->email,
                'value' => $email->email
            ];
        });
});

Route::get('/countries', function (Request $request) {
    return Country::where('mailable', 1)
        ->select('id', 'country_name_ar')
        ->limit(200)  
        ->get()
        ->map(function($country) {
            return [
                'id' => $country->id,
                'name_ar' => $country->country_name_ar,
                'label' => $country->country_name_ar,
                'value' => $country->country_name_ar
            ];
        });
});

Route::get('/pricing/{id}', function ($id) {
    return Pricing::findOrFail($id);
});

Route::get('/pricings', function (Request $request) {
    return Pricing::where('type', 'mail')
        ->where('doctor_type', $request->get('doctor_type'))
        ->select('id', 'name', 'amount','file_required', 'file_name')
        ->get();
});


Route::post('/doctor-mails', [DoctorMailController::class, 'store'])
     ->name('doctor-mails.store');


     Route::get('/get-sub-specialties/{id}', [SpecialtyController::class, 'get_subs']);
     Route::get('file-types', [FileTypeController::class, 'index_api']);

     Route::patch('doctor-mails/{doctorMail}/update', [DoctorMailController::class, 'updateRequest'])->name('doctor-mails.update');
     Route::get('doctor-mails/{doctorMail}/data', [DoctorMailController::class, 'getDoctorMailData'])->name('doctor-mails.data');