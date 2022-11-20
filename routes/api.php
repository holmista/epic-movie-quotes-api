<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CategoryController;

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

Route::controller(AuthController::class)->group(function () {
	Route::post('/signup', 'signup');
	Route::post('/signin', 'signin');
	Route::get('/email/verify/{id}/{hash}', 'sendEmailVerificationEmail')->middleware('signed')->name('verification.verify');
	Route::post('/email/verification-notification', 'resendVerificationEmail')->middleware(['throttle:6,1']);
});

Route::controller(PasswordResetController::class)->group(function () {
	Route::post('/forgot-password', 'sendPasswordResetEmail')->middleware('guest')->name('password.email');
	Route::post('/reset-password', 'resetPassword')->middleware('guest')->name('password.update');
});

Route::controller(GoogleAuthController::class)->group(function () {
	Route::get('/auth/redirect', [GoogleAuthController::class, 'redirect']);
	Route::get('/auth/callback', [GoogleAuthController::class, 'authenticate']);
});

Route::middleware(['auth', 'verified'])->group(function () {
	Route::get('/signout', [AuthController::class, 'signout']);

	Route::controller(EmailController::class)->group(function () {
		Route::post('/email', 'create');
		Route::delete('/email', 'delete');
		Route::post('/email/primary', 'makePrimary');
	});

	Route::controller(UserController::class)->group(function () {
		Route::post('/user', 'update');
		Route::get('/user', 'me');
	});
});

Route::get('/movies/{id}/quotes', [MovieController::class, 'movieQuotes']);
Route::get('/movies', [MovieController::class, 'myMovies']);
Route::post('/movies', [MovieController::class, 'create']);

Route::get('/quote/{id}', [QuoteController::class, 'get']);
Route::post('/quote', [QuoteController::class, 'create']);
Route::patch('/quote', [QuoteController::class, 'update']);
Route::delete('/quote/{id}', [QuoteController::class, 'delete']);

Route::post('/comment', [CommentController::class, 'create']);

Route::post('/like', [LikeController::class, 'create']);
Route::delete('/like/{id}', [LikeController::class, 'delete']);

Route::get('/category', [CategoryController::class, 'get']);
