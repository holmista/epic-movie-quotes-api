<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
	public function redirect()
	{
		return  response()->json(['url'=>Socialite::driver('google')->stateless()->redirect()->getTargetUrl()]);
	}

	public function authenticate()
	{
		$googleUser = Socialite::driver('google')->stateless()->user();
		$user = User::updateOrCreate([
			'google_id' => $googleUser->id,
		], [
			'name'              => $googleUser->name,
			'email'             => $googleUser->email,
			'password'          => bcrypt(strtolower(Str::random(15))),
			'email_verified_at' => now(),
		]);
		$token = auth()->attempt([
			'email'     => $user->email,
			'password'  => $user->password,
		]);
		return response()->json([
			'access_token' => $token,
			'token_type'   => 'bearer',
			'expires_in'   => auth()->factory()->getTTL() * 60,
		]);
	}
}
