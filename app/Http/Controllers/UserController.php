<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
	public function update(UpdateUserRequest $request): JsonResponse
	{
		$user = auth()->user();
		$credentials = $request->validated();
		if (array_key_exists('password', $credentials))
		{
			$credentials['password'] = bcrypt($credentials['password']);
			unset($credentials['confirmPassword']);
		}
		if ($request->hasFile('avatar'))
		{
			$path = $credentials['avatar']->store('avatars');
			$credentials['avatar'] = env('BACK_STORAGE_URL') . '/' . $path;
		}
		User::where('id', $user->id)->update($credentials);
		$updated_user = User::find($user->id);
		return response()->json([
			'message'      => 'User updated successfully',
			'user'         => $updated_user,
		], 200);
	}

	public function me(): JsonResponse
	{
		$emails = User::find(auth()->user()->id)->emails()->get();
		$user = auth()->user();
		return response()->json([
			'id'               => $user->id,
			'name'             => $user->name,
			'email'            => $user->email,
			'google_id'        => $user->google_id,
			'socondary_emails' => $emails,
			'avatar'           => $user->avatar,
		], 200);
	}
}
