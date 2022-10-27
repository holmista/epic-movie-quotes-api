<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Requests\CustomEmailVerificationRequest;

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

Route::post('signup', [AuthController::class, 'signup']);

Route::get('/email/verify/{id}/{hash}', function (CustomEmailVerificationRequest $request) {
	$request->fulfill();
	return response()->json([
		'message' => 'Email verified successfully',
	], 200);
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail']);
