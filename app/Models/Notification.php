<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	use HasFactory;

	public function receiver()
	{
		return $this->belongsTo(User::class, 'receiver_id');
	}

	public function trigerer()
	{
		return $this->belongsTo(User::class, 'trigerer_id');
	}

	public function quote()
	{
		return $this->belongsTo(Quote::class);
	}
}
