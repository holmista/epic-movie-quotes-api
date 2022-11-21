<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Models\Quote;
use App\Http\Requests\UpdateQuoteRequest;
use App\Http\Requests\DeleteQuoteRequest;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
	public function create(StoreQuoteRequest $request): JsonResponse
	{
		$data = $request->validated();
		$data['user_id'] = auth()->user()->id;
		$path = $data['avatar']->store('avatars');
		$data['avatar'] = $path;
		$quote = Quote::create($data);
		$quote->avatar = env('BACK_STORAGE_URL') . '/' . $quote->avatar;
		return response()->json(['quote'=>$quote], 201);
	}

	public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse
	{
		$data = $request->validated();
		if ($request->hasFile('avatar'))
		{
			$path = $data['avatar']->store('avatars');
			$data['avatar'] = $path;
		}
		$quote->update($data);
		$updated_quote = Quote::find($quote->id);
		$updated_quote->avatar = env('BACK_STORAGE_URL') . '/' . $updated_quote->avatar;
		return response()->json(['quote'=>$updated_quote]);
	}

	public function delete(DeleteQuoteRequest $request, Quote $quote): JsonResponse
	{
		$quote->delete();
		return response()->json(['message'=>'Quote deleted successfully'], 200);
	}

	public function get(): JsonResponse
	{
		$quote = Quote::where('id', request()->id)->with('comments')->firstOrFail();
		return response()->json(['quotes'=>$quote], 200);
	}
}
