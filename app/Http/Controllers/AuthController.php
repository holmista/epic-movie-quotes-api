<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSignupRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\CustomEmailVerificationRequest;
use App\Http\Requests\ResendEmailVerificationRequest;
use App\Http\Requests\StoreSigninRequest;
use Illuminate\Http\JsonResponse;
use Firebase\JWT\JWT;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
	public function signup(StoreSignupRequest $request): JsonResponse
	{
		$user = User::create([
			'name'     => $request->name,
			'email'    => $request->email,
			'password' => bcrypt($request->password),
		]);
		event(new Registered($user));
		return response()->json([
			'message' => 'Verification email sent',
		], 201);
	}

	public function sendEmailVerificationEmail(CustomEmailVerificationRequest $request): JsonResponse
	{
		$request->fulfill();
		return response()->json([
			'message' => 'Email verified successfully',
		], 200);
	}

	public function resendVerificationEmail(ResendEmailVerificationRequest $request): JsonResponse
	{
		$email = $request->input('email');
		$user = User::where('email', $email)->first();
		if ($user->hasVerifiedEmail())
		{
			return response()->json([
				'message' => 'Email already verified',
			], 200);
		}
		$user->sendEmailVerificationNotification();
		return response()->json([
			'message' => 'Verification email sent',
		], 200);
	}

	public function signin(StoreSigninRequest $request): JsonResponse
	{
		$authenticated = auth()->attempt(
			[
				'email'    => request()->email,
				'password' => request()->password,
			]
		);

		if (!$authenticated)
		{
			return response()->json('wrong email or password', 401);
		}

		$payload = [
			'exp' => Carbon::now()->addSeconds(30)->timestamp,
			'uid' => User::where('email', '=', request()->email)->first()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

		$cookie = cookie('access_token', $jwt, 30, '/', '127.0.0.1', true, true, false, 'Strict');

		return response()->json('success', 200)->withCookie($cookie);
	}

	public function signout(): JsonResponse
	{
		auth()->logout(true);
		return response()->json(['message' => 'Successfully signed out']);
	}
}
