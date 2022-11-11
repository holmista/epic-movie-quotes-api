<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Lower;

class UpdateUserRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'name'               => ['min:3', 'max:15', new Lower, 'unique:users,name'],
			'password'           => ['min:8', 'max:15', new Lower],
			'confirmPassword'    => ['same:password'],
		];
	}
}
