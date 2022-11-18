<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueTitle;

class StoreMovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'title'       => ['required', 'json', new UniqueTitle],
			'description' => ['required', 'json'],
			'avatar'      => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
			'release_year'=> ['required'],
			'user_id'     => ['required', 'exists:users,id'],
			'categories'  => ['required'],
		];
	}
}
