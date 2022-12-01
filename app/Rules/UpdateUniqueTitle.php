<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use App\Models\Movie;
use Illuminate\Support\Facades\Log;

class UpdateUniqueTitle implements InvokableRule
{
	/**
	 * Run the validation rule.
	 *
	 * @param string $attribute
	 * @param mixed  $value
	 * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	 *
	 * @return void
	 */
	public function __invoke($attribute, $value, $fail)
	{
		$enMovie = Movie::where('title->en', '=', json_decode($value, true)['en'])->get();
		// Log::info(request()->route('movie')->id);
		if ($enMovie->count() > 0 && $enMovie->first()->id != request()->route('movie')->id)
		{
			$fail('The English title has already been taken.');
		}
		$kaMovie = Movie::where('title->ka', '=', json_decode($value, true)['ka'])->get();
		if ($kaMovie->count() > 0 && $kaMovie->first()->id != request()->route('movie')->id)
		{
			$fail('The Georgian title has already been taken.');
		}
	}
}
