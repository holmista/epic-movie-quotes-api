<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSignupRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\ResendEmailVerificationRequest;

class AuthController extends Controller
{
	public function signup(StoreSignupRequest $request)
	{
		$user = User::create([
			'name'     => $request->name,
			'email'    => $request->email,
			'password' => $request->password,
		]);
		event(new Registered($user));
		return response()->json([
			'message' => 'Signup successful',
			'user'    => $user,
		], 201);
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
}
