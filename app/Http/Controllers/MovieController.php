<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreMovieRequest;
use Illuminate\Support\Facades\Log;

class MovieController extends Controller
{
	public function myMovies(): JsonResponse
	{
		$user = auth()->user();
		$movies = $user->movies()->with('quotes')->get();
		foreach ($movies as $movie)
		{
			$movie->avatar = env('BACK_STORAGE_URL') . '/' . $movie->avatar;
		}
		return response()->json(['movies'=>$movies], 200);
	}

	public function create(StoreMovieRequest $request): JsonResponse
	{
		$data = $request->validated();
		$data['user_id'] = auth()->user()->id;
		$data['title'] = json_decode($data['title'], true);
		$data['description'] = json_decode($data['description'], true);
		//$data['director'] = json_decode($data['director'], true);
		$categories = explode(',', $data['categories']);
		unset($data['categories']);
		$path = $data['avatar']->store('avatars');
		$data['avatar'] = $path;
		$movie = null;
		//Log::info($categories);
		DB::transaction(function () use ($data, $categories, &$movie) {
			Log::info($data);
			$movie = Movie::create($data);
			$movie->categories()->attach($categories);
		});
		$movie->avatar = env('BACK_STORAGE_URL') . '/' . $movie->avatar;
		return response()->json(['movie'=>$movie], 201);
	}

	public function movieQuotes(): JsonResponse
	{
		$movie = Movie::find(request()->id)->firstOrFail();
		$quotes = $movie->quotes()->get();
		return response()->json(['quotes'=>$quotes]);
	}
}
