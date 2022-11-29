<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Like;
use App\Http\Requests\DeleteLikeRequest;
use App\Http\Requests\StoreLikeRequest;

class LikeController extends Controller
{
	public function create(StoreLikeRequest $request): JsonResponse
	{
		$like = Like::create([
			'user_id' => auth()->user()->id,
			'quote_id'=> $request->quote_id,
		]);
		return response()->json(['like' => $like], 201);
	}

	public function delete(DeleteLikeRequest $request, Like $like): JsonResponse
	{
		$like->delete();
		return response()->json(['message' => 'Like deleted successfully'], 204);
	}
}
