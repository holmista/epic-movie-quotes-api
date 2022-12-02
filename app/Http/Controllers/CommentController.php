<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use App\Models\Notification;
use App\Events\NotificationCreated;

class CommentController extends Controller
{
	public function create(StoreCommentRequest $request): JsonResponse
	{
		$data = $request->validated();
		$data['user_id'] = auth()->user()->id;
		$comment = Comment::create($data);
		if ($comment->quote->user_id !== $data['user_id'])
		{
			$notification = Notification::create([
				'receiver_id' => $comment->quote->user_id,
				'trigerer_id' => $data['user_id'],
				'quote_id'    => $data['quote_id'],
				'type'        => 0,
				'is_read'     => false,
			]);
			NotificationCreated::dispatch($notification->load('trigerer'));
		}
		return response()->json(['comment'=>$comment->load('user')], 201);
	}
}
