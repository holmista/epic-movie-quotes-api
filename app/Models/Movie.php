<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
	use HasFactory;

	public $guarded = [];

	protected $casts = [
		'title'       => 'array',
		'description' => 'array',
	];

	public function quotes()
	{
		return $this->hasMany(Quote::class);
	}

	public function categories()
	{
		return $this->belongsToMany(Category::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
