<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\CustomVerifyEmailNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomPasswordResetNotification;

class User extends Authenticatable implements MustVerifyEmail
{
	use HasApiTokens;

	use HasFactory;

	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'google_id',
		'email_verified_at',
		'avatar',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function sendEmailVerificationNotification()
	{
		$this->notify(new CustomVerifyEmailNotification);
	}

	public function sendPasswordResetNotification($token)
	{
		$url = env('BACK_BASE_URL') . '/reset-password/' . $token;
		$this->notify(new CustomPasswordResetNotification($url));
	}

	public function emails()
	{
		return $this->hasMany(Email::class);
	}

	public function quotes()
	{
		return $this->hasMany(Quote::class);
	}

	public function movies()
	{
		return $this->hasMany(Movie::class);
	}

	public function notifications()
	{
		return $this->hasMany(Notification::class, 'receiver_id');
	}
}
