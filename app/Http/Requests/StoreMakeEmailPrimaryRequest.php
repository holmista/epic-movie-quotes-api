<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StoreMakeEmailPrimaryRequest extends FormRequest
{
	public function authorize(Request $request)
	{
		Log::info($this->input('email'));
		$email = Email::where('email', $request->input('email'))->firstOrFail();
		if (!$email->email_verified_at)
		{
			return false;
		}
		if ($email->user_id !== auth()->user()->id)
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
