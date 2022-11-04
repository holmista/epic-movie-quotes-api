<?php

namespace App\Http\Controllers;

use App\Http\Requests\PassworForgotRequest;
use App\Http\Requests\PasswordResetRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
	public function sendPasswordResetEmail(PassworForgotRequest $request)
	{
		$status = Password::sendResetLink(
			$request->only('email')
		);

		$status === Password::RESET_LINK_SENT;
		return response()->json(['message' =>$status]);
	}

	public function resetPassword(PasswordResetRequest $request)
	{
		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function ($user, $password) {
				$user->forceFill([
					'password' => Hash::make($password),
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

		$status === Password::PASSWORD_RESET;
		return response()->json(['message' =>$status]);
	}
}
