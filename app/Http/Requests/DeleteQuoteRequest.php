<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Quote;

class DeleteQuoteRequest extends FormRequest
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
			$quote_user_id = Quote::find(request()->quote->id)->user_id;
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
		];
	}
}
