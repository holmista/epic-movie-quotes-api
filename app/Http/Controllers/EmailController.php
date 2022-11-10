<?php

namespace App\Http\Controllers;

use App\Events\AddEmail;
use App\Http\Requests\StoreEmailRequest;
use App\Models\Email;

class EmailController extends Controller
{
	public function create(StoreEmailRequest $request)
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
		], 201);
	}
}
