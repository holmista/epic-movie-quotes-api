<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
	public function redirect()
	{
		return  response()->json(['url'=>Socialite::driver('google')->stateless()->redirect()->getTargetUrl()]);
	}
}
