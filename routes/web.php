<?php

use App\Models\Country;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WebsiteController;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Http\Controllers\Common\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [WebsiteController::class, 'index'])->name('index');
Route::get('/doc-login', [WebsiteController::class, 'doctor_login'])->name('doctor-login');
Route::post('/doc-auth', [WebsiteController::class, 'doctor_auth'])->name('doctor-auth');


Route::get('/checker', [WebsiteController::class, 'checker'])->name('checker');

Route::get('/verify-otp', [OtpController::class, 'showVerifyOtpForm'])->name('otp.verify');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('otp.check');


Route::get('/search-licensables', [SearchController::class, 'searchLicensables']);
Route::get('/search-facilities', [SearchController::class, 'searchFacilities']);
Route::get('/search-users', [SearchController::class, 'searchUsers']);



// upload documents
Route::get('/upload-documents', [WebsiteController::class, 'upload_documents'])->name('upload-documents');


// doctor.filepond.process
Route::post('/doctor/filepond/process', [WebsiteController::class, 'filepond_process'])->name('doctor.filepond.process');
Route::post('/doctor/filepond/revert', [WebsiteController::class, 'filepond_revert'])->name('doctor.filepond.revert');


// doctor.registration.complete
Route::get('/doctor/registration/{doctor}/complete', [WebsiteController::class, 'complete_registration'])->name('doctor.registration.complete');
Route::post('/verify-otp/resend', [OtpController::class, 'resendOtp'])
     ->name('otp.resend');
     
Route::get("/login", [AuthController::class, 'login'])->name('login');
Route::get("/register", [AuthController::class, 'register'])->name('register');
Route::post("/register", [AuthController::class, 'register_store'])->name('register-store');
Route::post("/do-login", [AuthController::class, 'do_login'])->name('do_login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('change-branch', [AuthController::class, 'change_branch'])->name('change_branch');
Route::middleware('auth')->group(function() {
    Route::get('/sections', [AuthController::class, 'sections'])->name('sections');
});


// send testing email
Route::get('/send-email', function() {
    Mail::to('aishaaltery89@gmail.com', 'Test')->send(new \App\Mail\TestingEmail());
});


Route::get('/set-uni', function() {

    $country = Country::first();
    $response = Http::get('http://universities.hipolabs.com/search?country=' . $country->en_name);


     $universities = $response->json();
     return $universities;
});

require __DIR__.'/admin.php';
require __DIR__.'/user.php';
require __DIR__.'/finance.php';
require __DIR__.'/doctor.php';
