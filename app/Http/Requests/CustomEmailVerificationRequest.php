<?php

namespace App\Http\Requests;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Models\Email;
use Illuminate\Support\Facades\Log;

class CustomEmailVerificationRequest extends FormRequest
{
	public function getTheUser()
	{
		return User::find($this->route('id'));
	}

	public function authorize()
	{
		$user = User::find((int)($this->route('id')));
		$this->email = $user->getEmailForVerification();
		if ($this->query('emailId') !== '0')
		{
			$this->email = Email::find((int)($this->query('emailId')));
		}
		if (!hash_equals(
			(string) $this->route('id'),
			/*this line is casing the issue*/
			(string) $user->getKey()
		))
		{
			return false;
		}
		if (gettype($this->email) === 'string')
		{
			if (!hash_equals((string) $this->route('hash'), sha1($this->email)))
			{
				return false;
			}
		}
		else
		{
			if (!hash_equals((string) $this->route('hash'), sha1($this->email->email)))
			{
				return false;
			}
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
		Log::info($this->query('emailId'));
		if ($this->query('emailId') === '0')
		{
			if (!$user->hasVerifiedEmail())
			{
				$user->markEmailAsVerified();

				event(new Verified($user));
			}
		}
		else
		{
			Log::info('emailId is not 0');
			// $email = Email::find((int)$this->query('emailId'))->first();
			if (!$this->email->email_verified_at)
			{
				$this->email->email_verified_at = now();
				$this->email->save();
			}
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
