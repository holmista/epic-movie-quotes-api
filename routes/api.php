<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\UserController;

// use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/signin', [AuthController::class, 'signin']);
Route::get('/signout', [AuthController::class, 'signout']);
Route::get('/email', function () {
	return view('emails.AccountVerification');
});

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'sendEmailVerificationEmail'])->middleware('signed')->name('verification.verify');

Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])->middleware(['throttle:6,1']);

Route::post('/forgot-password', [PasswordResetController::class, 'sendPasswordResetEmail'])->middleware('guest')->name('password.email');

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->middleware('guest')->name('password.update');

Route::get('/auth/redirect', [GoogleAuthController::class, 'redirect']);

Route::get('/auth/callback', [GoogleAuthController::class, 'authenticate']);

Route::post('/email', [EmailController::class, 'create']);
Route::delete('/email', [EmailController::class, 'delete']);
Route::patch('/user', [UserController::class, 'update']);
Route::post('/email/primary', [EmailController::class, 'makePrimary']);
