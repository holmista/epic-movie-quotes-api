<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Lower;

class StoreSignupRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'name'               => ['required', 'min:3', 'max:15', new Lower, 'unique:users,name'],
			'email'              => ['required', 'email', 'unique:emails,email', 'unique:users,email'],
			'password'           => ['required', 'min:8', 'max:15', new Lower],
			'confirmPassword'    => ['required', 'same:password'],
		];
	}
}
