<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Models\Quote;
use App\Http\Requests\UpdateQuoteRequest;
use App\Http\Requests\DeleteQuoteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class QuoteController extends Controller
{
	public function create(StoreQuoteRequest $request): JsonResponse
	{
		$data = $request->validated();
		$data['user_id'] = auth()->user()->id;
		$path = $data['avatar']->store('avatars');
		$data['avatar'] = $path;
		$data['title'] = json_decode($data['title'], true);
		$data['avatar'] = env('BACK_STORAGE_URL') . '/' . $data['avatar'];
		$quote = Quote::create($data);
		return response()->json(['quote'=>$quote->withCount('comments', 'likes')->find($quote->id)], 201);
	}

	public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse
	{
		$data = $request->validated();
		$data['title'] = json_decode($data['title'], true);
		if ($request->hasFile('avatar'))
		{
			Storage::delete($quote->avatar);
			$path = $data['avatar']->store('avatars');
			$data['avatar'] = env('BACK_STORAGE_URL') . '/' . $path;
		}
		$quote->update($data);
		$updated_quote = Quote::find($quote->id);
		return response()->json(['quote'=>$updated_quote->withCount('comments', 'likes')->find($quote->id)]);
	}

	public function delete(DeleteQuoteRequest $request, Quote $quote): JsonResponse
	{
		$quote->delete();
		return response()->json(['message'=>'Quote deleted successfully'], 204);
	}

	public function get(Quote $quote): JsonResponse
	{
		return response()->json(['quote'=>$quote->load('comments.user', 'user')]);
	}

	public function getAll(): JsonResponse
	{
		$quotes = Quote::with('comments.user', 'likes', 'user', 'movie')->withCount('comments', 'likes')->orderBy('created_at', 'desc')->paginate(5);
		return response()->json(['quotes'=>$quotes]);
	}
}
