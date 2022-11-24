<?php

namespace App\Guards;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Hash;

class JwtGuard
{
	public static $user = null;

	public function attempt(array $credentials = []): bool | User
	{
		$emailUser = User::where('email', $credentials['email'])->first();
		$nameUser = User::where('name', $credentials['email'])->first();
		$user = $emailUser ?? $nameUser;
		if (!$user)
		{
			return false;
		}
		if (Hash::check($credentials['password'], $user->password))
		{
			return $user;
		}
		return false;
	}

	public function user()
	{
		if (self::$user)
		{
			return self::$user;
		}
		try
		{
			if (!request()->cookie('access_token') && !request()->header('Authorization'))
			{
				throw new \ErrorException('access token is not provided');
			}

			$decoded = JWT::decode(
				request()->cookie('access_token') ?? substr(request()->header('Authorization'), 7),
				new Key(config('auth.jwt_secret'), 'HS256')
			);
			self::$user = User::find($decoded->uid);
			return self::$user;
		}
		catch (\Exception $e)
		{
			return null;
		}
	}
}
