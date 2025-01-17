<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;
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


Route::get("/login", [AuthController::class, 'login'])->name('login');
Route::get("/register", [AuthController::class, 'register'])->name('register');
Route::post("/register", [AuthController::class, 'register_store'])->name('register');
Route::post("/do-login", [AuthController::class, 'do_login'])->name('do_login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('change-branch', [AuthController::class, 'change_branch'])->name('change_branch');
Route::middleware('auth')->group(function() {
    Route::get('/sections', [AuthController::class, 'sections'])->name('sections');
});
require __DIR__.'/admin.php';
require __DIR__.'/user.php';
require __DIR__.'/finance.php';
require __DIR__.'/doctor.php';
