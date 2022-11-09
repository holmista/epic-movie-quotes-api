<?php

namespace App\Http\Requests;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class CustomEmailVerificationRequest extends FormRequest
{
	public function getTheUser()
	{
		return User::find($this->route('id'));
	}

	public function authorize()
	{
		$user = $this->getTheUser();
		if (!hash_equals(
			(string) $this->route('id'),
			/*this line is casing the issue*/
			(string) $user->getKey()
		))
		{
			return false;
		}

		if (!hash_equals(
			(string) $this->route('hash'),
			sha1($user->getEmailForVerification())
		))
		{
			return false;
		}

		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		];
	}

	/**
	 * Fulfill the email verification request.
	 *
	 * @return void
	 */
	public function fulfill()
	{
		$user = $this->getTheUser();
		if (!$user->hasVerifiedEmail())
		{
			$user->markEmailAsVerified();

			event(new Verified($user));
		}
	}

	/**
	 * Configure the validator instance.
	 *
	 * @param \Illuminate\Validation\Validator $validator
	 *
	 * @return void
	 */
	public function withValidator($validator)
	{
		return $validator;
	}
}
