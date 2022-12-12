<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;

class SwaggerController extends Controller
{
	public function login(): JsonResponse
	{
		$authenticated = auth()->attempt(
			[
				'email'    => request()->email,
				'password' => request()->password,
			]
		);

		if (!$authenticated)
		{
			return response()->json(['message'=>'invalid credentials'], 401);
		}

		if (!$authenticated->email_verified_at)
		{
			return response()->json(['email'=>'email not verified'], 422);
		}

		$payload = [
			'exp' => Carbon::now()->addMinutes(60)->timestamp,
			'uid' => $authenticated->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

		return response()->json(['access_token'=>$jwt]);
	}
}
