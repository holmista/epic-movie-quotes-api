<?php

namespace App\Listeners;

use App\Events\AddEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\CustomVerifyEmailNotification;
use Illuminate\Support\Facades\Log;

//use Illuminate\Queue\InteractsWithQueue;

class SendEmailAddVerificationNotification implements ShouldQueue
{
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
	}

	/**
	 * Handle the event.
	 *
	 * @param object $event
	 *
	 * @return void
	 */
	public function handle(AddEmail $event)
	{
		// $event->user->sendEmailVerificationNotification();
		// Log::info('in listener' . $event->user->email);
		$event->user->notify(new CustomVerifyEmailNotification($event->newEmail));
		// new CustomVerifyEmailNotification($event->user);
	}
}
