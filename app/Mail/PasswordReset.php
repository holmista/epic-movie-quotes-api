<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct(public string $url)
	{
	}

	/**
	 * Get the message envelope.
	 *
	 * @return \Illuminate\Mail\Mailables\Envelope
	 */
	public function envelope()
	{
		return new Envelope(
			from: 'jeffrey@example.com',
			subject: 'Password Reset',
		);
	}

	/**
	 * Get the message content definition.
	 *
	 * @return \Illuminate\Mail\Mailables\Content
	 */
	public function content()
	{
		return new Content(
			view: 'emails.PasswordReset',
		);
	}

	/**
	 * Get the attachments for the message.
	 *
	 * @return array
	 */
	public function attachments()
	{
		return [];
	}
}
