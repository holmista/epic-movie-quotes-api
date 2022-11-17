<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
	use HasFactory;

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function movie()
	{
		return $this->belongsTo(Movie::class);
	}

	public function users()
	{
		return $this->belongsToMany(User::class);
	}
}
