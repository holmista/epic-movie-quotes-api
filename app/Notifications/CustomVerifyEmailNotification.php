<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use App\Mail\AccountVerification;

class CustomVerifyEmailNotification extends Notification
{
	/**
	 * The callback that should be used to create the verify email URL.
	 *
	 * @var \Closure|null
	 */
	public static $createUrlCallback;

	/**
	 * The callback that should be used to build the mail message.
	 *
	 * @var \Closure|null
	 */
	public static $toMailCallback;

	/**
	 * Get the notification's channels.
	 *
	 * @param mixed $notifiable
	 *
	 * @return array|string
	 */
	public function via($notifiable)
	{
		return ['mail'];
	}

	/**
	 * Build the mail representation of the notification.
	 *
	 * @param mixed $notifiable
	 *
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable)
	{
		$verificationUrl = $this->verificationUrl($notifiable);
		$frontverificationUrl = str_replace('http://127.0.0.1:8000/api/', env('FRONT_BASE_URL'), $verificationUrl);
		return (new AccountVerification($frontverificationUrl))->to($notifiable->email);
	}

	/**
	 * Get the verify email notification mail message for the given URL.
	 *
	 * @param string $url
	 *
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	// protected function buildMailMessage($url)
	// {
	// 	return new AccountVerification($url);
	// 	// ->subject(Lang::get('Verify Email Address'))
	// 	// ->line(Lang::get('Please click the button below to verify your email address.'))
	// 	// ->action(Lang::get('Verify Email Address'), $url)
	// 	// ->line(Lang::get('If you did not create an account, no further action is required.'));
	// }

	/**
	 * Get the verification URL for the given notifiable.
	 *
	 * @param mixed $notifiable
	 *
	 * @return string
	 */
	protected function verificationUrl($notifiable)
	{
		if (static::$createUrlCallback)
		{
			return call_user_func(static::$createUrlCallback, $notifiable);
		}

		return URL::temporarySignedRoute(
			'verification.verify',
			Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
			[
				'id'   => $notifiable->getKey(),
				'hash' => sha1($notifiable->getEmailForVerification()),
			]
		);
	}

	/**
	 * Set a callback that should be used when creating the email verification URL.
	 *
	 * @param \Closure $callback
	 *
	 * @return void
	 */
	public static function createUrlUsing($callback)
	{
		static::$createUrlCallback = $callback;
	}

	/**
	 * Set a callback that should be used when building the notification mail message.
	 *
	 * @param \Closure $callback
	 *
	 * @return void
	 */
	public static function toMailUsing($callback)
	{
		static::$toMailCallback = $callback;
	}
}
