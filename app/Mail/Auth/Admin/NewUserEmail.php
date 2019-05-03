<?php

namespace App\Mail\Auth\Admin;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class NewUserEmail extends Mailable
{
	use Queueable, SerializesModels;

	protected $user;
	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->subject(__("Welcome to EQBIDS"))->from('eqbids@coderscoop.com')
		            ->view('emails.admin.users.welcome')
		            ->with('user',$this->user);
	}
}
