<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
	use HasFactory;

	public $guarded = [];

	protected $casts = [
		'title' => 'array',
	];

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function likes()
	{
		return $this->hasMany(Like::class);
	}

	public function movie()
	{
		return $this->belongsTo(Movie::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
