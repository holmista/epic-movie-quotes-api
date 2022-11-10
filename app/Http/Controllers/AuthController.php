<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSignupRequest;
use App\Models\User;
use App\Models\Email;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\CustomEmailVerificationRequest;
use App\Http\Requests\ResendEmailVerificationRequest;
use App\Http\Requests\StoreSigninRequest;

class AuthController extends Controller
{
	public function signup(StoreSignupRequest $request)
	{
		$user = User::create([
			'name'     => $request->name,
		]);
		$email = Email::create([
			'email'      => $request->email,
			'password'   => bcrypt($request->password),
			'is_primary' => true,
			'user_id'    => $user->id,
		]);
		$user->email = $email;
		event(new Registered($user));
		return response()->json([
			'message' => 'Verification email sent',
		], 201);
	}

	public function sendEmailVerificationEmail(CustomEmailVerificationRequest $request)
	{
		$request->fulfill();
		// return response()->redirectTo(env('FRONT_BASE_URL') . 'activation-email-sent');
		return response()->json([
			'message' => 'Email verified successfully',
		], 200);
	}

	public function resendVerificationEmail(ResendEmailVerificationRequest $request)
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

	public function signin(StoreSigninRequest $request)
	{
		$emailToken = auth()->attempt([
			'email'    => $request->email,
			'password' => $request->password,
		]);
		$nameToken = auth()->attempt([
			'name'     => $request->email,
			'password' => $request->password,
		]);
		if (!$emailToken && !$nameToken)
		{
			return response()->json([
				'message' => 'Invalid credentials',
			], 401);
		}
		$accessToken = $emailToken ? $emailToken : $nameToken;
		return response()->json([
			'access_token' => $accessToken,
			'token_type'   => 'bearer',
			'expires_in'   => auth()->factory()->getTTL() * 60,
		]);
	}

	public function signout()
	{
		auth()->logout(true);
		return response()->json(['message' => 'Successfully signed out']);
	}
}
