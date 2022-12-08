<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Illuminate\Support\Carbon;

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
		$user = User::where('google_id', $googleUser->id)->first();
		if (!$user)
		{
			$user = User::create([
				'name'              => $googleUser->name,
				'email'             => $googleUser->email,
				'password'          => bcrypt($googleUser->password),
				'email_verified_at' => now(),
				'avatar'            => env('BACK_STORAGE_URL') . '/' . 'avatars/defaultAvatar.png',
				'google_id'         => $googleUser->id,
			]);
		}

		$payload = [
			'exp' => Carbon::now()->addMinutes(60)->timestamp,
			'uid' => $user->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

		$cookie = cookie('access_token', $jwt, 60, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json(['name'=>$user->name, 'id'=>$user->id], 200)->withCookie($cookie);
	}
}
