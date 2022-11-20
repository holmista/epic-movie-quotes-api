<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected $casts = [
		'name'       => 'array',
	];

	public function movies()
	{
		return $this->belongsToMany(Movie::class);
	}
}
