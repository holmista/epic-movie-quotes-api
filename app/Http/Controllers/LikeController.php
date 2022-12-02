<?php

namespace App\Http\Controllers;

use App\Events\NotificationCreated;
use Illuminate\Http\JsonResponse;
use App\Models\Like;
use App\Http\Requests\DeleteLikeRequest;
use App\Http\Requests\StoreLikeRequest;
use App\Models\Notification;

class LikeController extends Controller
{
	public function create(StoreLikeRequest $request): JsonResponse
	{
		$user = auth()->user();
		$like = Like::create([
			'user_id' => $user->id,
			'quote_id'=> $request->quote_id,
		]);
		if ($like->quote->user_id !== $user->id)
		{
			$notification = Notification::create([
				'receiver_id' => $like->quote->user_id,
				'trigerer_id' => $user->id,
				'quote_id'    => $request->quote_id,
				'type'        => 1,
				'is_read'     => false,
			]);
			event((new NotificationCreated($notification->load('trigerer')))->dontBroadcastToCurrentUser());
		}
		return response()->json(['like' => $like], 201);
	}

	public function delete(DeleteLikeRequest $request, Like $like): JsonResponse
	{
		$like->delete();
		return response()->json(['message' => 'Like deleted successfully'], 204);
	}
}
