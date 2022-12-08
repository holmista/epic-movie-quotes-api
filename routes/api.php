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
use App\Http\Controllers\NotificationController;

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

Route::middleware(['jwt.auth'])->group(function () {
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

	Route::controller(MovieController::class)->group(function () {
		Route::get('/movies/{movie}/quotes', 'movieQuotes');
		Route::get('/movies/{movie}', 'getMovie');
		Route::get('/movies', 'myMovies');
		Route::post('/movies', 'create');
		Route::patch('/movies/{movie}', 'update');
		Route::delete('/movies/{movie}', 'delete');
	});

	Route::controller(QuoteController::class)->group(function () {
		Route::get('/quote', 'getAll');
		Route::get('/quote/{quote}', 'get');
		Route::post('/quote', 'create');
		Route::patch('/quote/{quote}', 'update');
		Route::delete('/quote/{quote}', 'delete');
	});

	Route::post('/comment', [CommentController::class, 'create']);

	Route::controller(LikeController::class)->group(function () {
		Route::post('/like', 'create');
		Route::delete('/like/{like}', 'delete');
	});

	Route::get('/category', [CategoryController::class, 'get']);

	Route::controller(NotificationController::class)->group(function () {
		Route::get('/notification', 'index');
		Route::patch('/notification/read-all', 'readAll');
		Route::patch('/notification/{notification}', 'update');
	});
});
