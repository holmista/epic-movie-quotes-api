<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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
		Log::info($credentials);
		User::where('id', $user->id)->update($credentials);
		return response()->json([
			'message' => 'User updated successfully',
		], 200);
	}

	public function me()
	{
		$emails = User::find(auth()->user()->id)->emails()->get();
		$user = auth()->user();
		return response()->json([
			'name'             => $user->name,
			'email'            => $user->email,
			'socondary_emails' => $emails,
		], 200);
	}
}
