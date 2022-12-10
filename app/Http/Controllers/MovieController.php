<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Requests\DeleteMovieRequest;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
	public function myMovies(Movie $movie): JsonResponse
	{
		$user = auth()->user();
		$movies = $user->movies()->with('quotes')->get();
		return response()->json(['movies'=>$movies], 200);
	}

	public function getMovie(Movie $movie): JsonResponse
	{
		return response()->json(['movie'=>$movie->load('categories', 'user')]);
	}

	public function create(StoreMovieRequest $request): JsonResponse
	{
		$data = $request->validated();
		$data['user_id'] = auth()->user()->id;
		$data['title'] = json_decode($data['title'], true);
		$data['description'] = json_decode($data['description'], true);
		$data['director'] = json_decode($data['director'], true);
		$categories = explode(',', $data['categories']);
		unset($data['categories']);
		$path = $data['avatar']->store('avatars');
		$data['avatar'] = env('BACK_STORAGE_URL') . '/' . $path;
		$movie = null;
		DB::transaction(function () use ($data, $categories, &$movie) {
			$movie = Movie::create($data);
			$movie->categories()->attach($categories);
		});
		return response()->json(['movie'=>$movie], 201);
	}

	public function update(UpdateMovieRequest $request, Movie $movie)
	{
		$data = $request->validated();
		Storage::delete($movie->avatar);
		$data['user_id'] = auth()->user()->id;
		$data['title'] = json_decode($data['title'], true);
		$data['description'] = json_decode($data['description'], true);
		$categories = explode(',', $data['categories']);
		unset($data['categories']);
		$path = $data['avatar']->store('avatars');
		$data['avatar'] = env('BACK_STORAGE_URL') . '/' . $path;
		DB::transaction(function () use ($data, $categories, &$movie) {
			Movie::where('id', $movie->id)->update($data);
			$movie->categories()->detach();
			$movie->categories()->attach($categories);
		});
		$updatedMovie = Movie::find($movie->id);
		return response()->json(['movie'=>$updatedMovie->load('categories')]);
		// return response()->json(['message'=>'Not implemented yet.'], 501);
	}

	public function delete(DeleteMovieRequest $request, Movie $movie): JsonResponse
	{
		$movie->delete();
		return response()->json(['message'=>'Movie deleted successfully.'], 204);
	}

	public function movieQuotes(Movie $movie): JsonResponse
	{
		return response()->json(['movie'=>$movie, 'quotes'=>$movie->quotes()->withCount('comments', 'likes')->get()]);
	}
}
