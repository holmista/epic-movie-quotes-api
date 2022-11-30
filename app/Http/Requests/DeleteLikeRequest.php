<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class DeleteLikeRequest extends FormRequest
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
			Log::info('user id - ' . request()->user()->id . 'like user id - ' . request()->like->user_id);
			return request()->user()->id == request()->like->user_id;
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
