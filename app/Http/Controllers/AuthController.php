<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSignupRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\CustomEmailVerificationRequest;
use App\Http\Requests\ResendEmailVerificationRequest;
use App\Http\Requests\StoreSigninRequest;
use Illuminate\Http\JsonResponse;

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
		$authBy = null;
		$userEmail = User::where('email', $request->email)->first();
		if ($userEmail)
		{
			$authBy = 'email';
		}
		$userName = User::where('name', $request->email)->first();
		if ($userName)
		{
			$authBy = 'name';
		}
		$user = $userEmail ?? $userName;
		if (!$user)
		{
			return response()->json([
				'message' => 'Invalid credentials',
			], 401);
		}
		if (!$user->email_verified_at)
		{
			return response()->json([
				'message' => 'Email not verified',
			], 401);
		}
		$accessToken = auth()->attempt([
			$authBy    => $request->email,
			'password' => $request->password,
		]);
		if (!$accessToken)
		{
			return response()->json([
				'message' => 'Invalid credentials',
			], 401);
		}
		return response()->json([
			'access_token' => $accessToken,
			'token_type'   => 'bearer',
			'expires_in'   => auth()->factory()->getTTL() * 60,
		]);
	}

	public function signout(): JsonResponse
	{
		auth()->logout(true);
		return response()->json(['message' => 'Successfully signed out']);
	}
}
