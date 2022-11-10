<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
	use HasFactory;

	protected $fillable = [
		'email',
		'is_primary',
		'email_verified_at',
		'user_id',
	];
}
