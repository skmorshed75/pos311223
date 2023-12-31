<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::post('/user-registration',[UserController::class,'UserRegistration']);
Route::get('/user-login',[UserController::class,'UserLogin']);
Route::get('/send-otp',[UserController::class,'SendOTPCode']);
Route::get('/verify-otp',[UserController::class,'VerifyOtp']);

//Token must verify before resetting password
Route::get('/reset-password',[UserController::class, 'ResetPassword'])->middleware([TokenVerificationMiddleware::class]);