<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
	use HasFactory;

	protected $guarded = [];

	protected $casts = [
		'email_verified_at' => 'datetime',
	];
}
