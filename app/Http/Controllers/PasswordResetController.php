<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordForgotRequest;
use App\Http\Requests\PasswordResetRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class PasswordResetController extends Controller
{
	public function sendPasswordResetEmail(PasswordForgotRequest $request): JsonResponse
	{
		App::setLocale($request->locale);
		$status = Password::sendResetLink(
			$request->only('email')
		);

		$status === Password::RESET_LINK_SENT;
		return response()->json(['message' =>$status]);
	}

	public function resetPassword(PasswordResetRequest $request): JsonResponse
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
