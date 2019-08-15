<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PinGenerated extends Mailable
{
    use Queueable, SerializesModels;

    public $pin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pin)
    {
        $this->pin=$pin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	//TODO change
        return $this->from('team@eqbids.com')->view('emails.auth.pin');
    }
}
