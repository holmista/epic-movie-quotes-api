<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
	public function get()
	{
		return response()->json(['categories'=>Category::all()], 200);
	}
}
