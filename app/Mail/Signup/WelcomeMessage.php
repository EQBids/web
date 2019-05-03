<?php

namespace App\Mail\Signup;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeMessage extends Mailable
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
        $this->subject(__("Welcome to EQBIDS"))->from('eqbids@coderscoop.com')
                    ->with('user',$this->user);

        if($this->user->status==User::STATUS_ACTIVE){
        	$this->view('emails.signup.welcome');
        }

	    if($this->user->status==User::STATUS_PENDING || $this->user->status==User::STATUS_ON_APPROVAL ){
		    $this->view('emails.signup.review');
	    }

        return $this;
    }
}
