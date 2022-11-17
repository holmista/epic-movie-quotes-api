<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
	public function redirect(): JsonResponse
	{
		return  response()->json(['url'=>Socialite::driver('google')->stateless()->redirect()->getTargetUrl()]);
	}

	public function authenticate(): JsonResponse
	{
		$googleUser = Socialite::driver('google')->stateless()->user();
		$googleUser->password = strtolower(Str::random(15));
		$user = User::updateOrCreate([
			'google_id' => $googleUser->id,
		], [
			'name'              => $googleUser->name,
			'email'             => $googleUser->email,
			'password'          => bcrypt($googleUser->password),
			'email_verified_at' => now(),
		]);
		$token = auth()->attempt([
			'email'     => $user->email,
			'password'  => $googleUser->password,
		]);
		return response()->json([
			'access_token' => $token,
			'token_type'   => 'bearer',
			'expires_in'   => auth()->factory()->getTTL() * 60,
		]);
	}
}
