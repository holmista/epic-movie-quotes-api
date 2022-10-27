<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSignupRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

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
}
