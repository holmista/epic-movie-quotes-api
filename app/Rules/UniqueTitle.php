<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use App\Models\Movie;

class UniqueTitle implements InvokableRule
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
		if ($enMovie->count() > 0)
		{
			$fail('The English title has already been taken.');
		}
		$kaMovie = Movie::where('title->ka', '=', json_decode($value, true)['ka'])->get();
		if ($kaMovie->count() > 0)
		{
			$fail('The Georgian title has already been taken.');
		}
	}
}
