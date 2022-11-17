<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Models\Email;

class DeleteEmailRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(Request $request)
	{
		$email = Email::where('email', $request->email)->firstOrFail();
		$user = auth()->user();
		if ($email->user_id !== $user->id)
		{
			return false;
		}
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'email'=> ['required', 'email', 'exists:emails,email'],
		];
	}
}
