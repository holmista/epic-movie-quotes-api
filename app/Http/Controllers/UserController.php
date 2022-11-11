<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
	public function update(UpdateUserRequest $request)
	{
		$user = auth()->user();
		$credentials = $request->validated();
		if (array_key_exists('password', $credentials))
		{
			$credentials['password'] = bcrypt($credentials['password']);
			unset($credentials['confirmPassword']);
		}
		User::where('id', $user->id)->update($credentials);
		return response()->json([
			'message' => 'User updated successfully',
		], 200);
	}
}
