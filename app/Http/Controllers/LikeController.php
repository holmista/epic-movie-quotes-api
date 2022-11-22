<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
	public function create(): JsonResponse
	{
		DB::table('likes')->insert([
			'user_id'  => auth()->user()->id,
			'quote_id' => request()->quote_id,
		]);
		return response()->json(['message' => 'Like created successfully.']);
	}

	public function delete(): JsonResponse
	{
		DB::table('likes')->where([
			'id' => request()->id,
		])->delete();
		return response()->json(['message' => 'Like deleted successfully.']);
	}
}
