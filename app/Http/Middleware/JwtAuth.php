<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class JwtAuth
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next)
	{
		$token = null;
		if (request()->cookie('access_token'))
		{
			$token = request()->cookie('access_token');
		}
		if (request()->header('Authorization') > 7)
		{
			$token = substr(request()->header('Authorization'), 7);
		}
		Log::info('Token: ' . $token);
		if (!$token)
		{
			return response()->json([
				'message' => 'access token not found',
			], 401);
		}

		try
		{
			$decoded = JWT::decode($token, new Key(config('auth.jwt_secret'), 'HS256'));
		}
		catch (\Exception $e)
		{
			Log::info('Exception: ' . $e->getMessage());
			return response()->json([
				'message' => 'access token is invalid',
			], 401);
		}

		if ($decoded->exp < Carbon::now()->timestamp)
		{
			return response()->json(['message'=>'access token has expired'], 401);
		}
		return $next($request);
	}
}
