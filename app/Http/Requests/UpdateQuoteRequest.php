<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Quote;

class UpdateQuoteRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		try
		{
			$quote_user_id = Quote::find(request()->id)->user_id;
			return auth()->user()->id === $quote_user_id;
		}
		catch(\Exception $e)
		{
			return false;
		}
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'id'    => ['required', 'exists:quotes,id'],
			'title' => ['json'],
			'avatar'=> ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
		];
	}
}
