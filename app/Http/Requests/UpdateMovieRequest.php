<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UpdateUniqueTitle;
use App\Models\Movie;

class UpdateMovieRequest extends FormRequest
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
			$movie_user_id = Movie::find(request()->movie->id)->user_id;
			return auth()->user()->id === $movie_user_id;
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
			'title'       => ['required', 'json', new UpdateUniqueTitle],
			'description' => ['required', 'json'],
			'avatar'      => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
			'release_year'=> ['required'],
			'categories'  => ['required'],
			'budget'      => ['required', 'numeric'],
			'director'    => ['required', 'json'],
		];
	}
}
