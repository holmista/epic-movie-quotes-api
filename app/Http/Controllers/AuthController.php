<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSignupRequest;

class AuthController extends Controller
{
	public function signup(StoreSignupRequest $request)
	{
		return response()->json([
			'message' => 'Signup successful',
		], 201);
	}
}
