<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
	public function create(StoreCommentRequest $request)
	{
		$data = $request->validated();
		$data['user_id'] = auth()->user()->id;
		$comment = Comment::create($data);
		return response()->json(['comment'=>$comment], 201);
	}
}
