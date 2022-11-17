<?php

namespace App\Http\Controllers;

use App\Events\AddEmail;
use App\Http\Requests\StoreEmailRequest;
use App\Models\Email;
use App\Http\Requests\StoreMakeEmailPrimaryRequest;
use App\Models\User;
use App\Http\Requests\DeleteEmailRequest;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
	public function create(StoreEmailRequest $request): JsonResponse
	{
		$user = auth()->user();
		$email = $request->input('email');
		$email = Email::create([
			'email'      => $email,
			'user_id'    => $user->id,
		]);
		event(new AddEmail($user, $email->email));
		return response()->json([
			'message' => 'Verification email sent',
			'email'   => $email,
		], 201);
	}

	public function makePrimary(StoreMakeEmailPrimaryRequest $request): JsonResponse
	{
		$user = auth()->user();
		$oldEmail = $user->email;
		$newEmail = Email::where('email', $request->input('email'))->first();
		Email::where('email', $newEmail->email)->delete();
		$oldEmail = Email::create([
			'email'             => $oldEmail,
			'user_id'           => $user->id,
			'email_verified_at' => $user->email_verified_at,
		]);
		$user = User::find($user->id);
		$user->email = $newEmail->email;
		$user->email_verified_at = $newEmail->email_verified_at;
		$user->save();
		return response()->json([
			'message'            => 'Email updated successfully',
			'new_primary_email'  => $user->email,
			'new_secondary_email'=> $oldEmail,
		], 200);
	}

	public function delete(DeleteEmailRequest $request): JsonResponse
	{
		$email = Email::where('email', $request->input('email'))->first();
		$email->delete();
		return response()->json([
			'message' => 'Email deleted successfully',
		], 204);
	}
}
