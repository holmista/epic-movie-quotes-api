<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Http\Requests\StoreMovieRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
	public function myMovies(): JsonResponse
	{
		$user = auth()->user();
		$movies = $user->movies()->with('quotes')->get();
		return response()->json(['movies'=>$movies], 200);
	}

	public function create(StoreMovieRequest $request): JsonResponse
	{
		$data = $request->validated();
		$data['user_id'] = auth()->user()->id;
		$categories = json_decode($data['categories'], true);
		unset($data['categories']);
		$path = $data['avatar']->store('avatars');
		$data['avatar'] = $path;
		$movie = null;
		DB::transaction(function () use ($data, $categories, &$movie) {
			$movie = Movie::create($data);
			foreach ($categories as $category)
			{
				DB::insert('insert into category_movie (movie_id, category_id) values (?, ?)', [$movie->id, $category]);
			}
		});
		$movie->avatar = env('BACK_STORAGE_URL') . '/' . $movie->avatar;
		return response()->json(['movie'=>$movie]);
	}

	public function movieQuotes(): JsonResponse
	{
		$movie = Movie::find(request()->id)->firstOrFail();
		$quotes = $movie->quotes()->get();
		return response()->json(['quotes'=>$quotes]);
	}
}
