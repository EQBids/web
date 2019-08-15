<?php

namespace App\Mail\Signup;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContractorSignedUp extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('team@eqbids.com')
                    ->subject(__("Contractor application on EQBids"))
                    ->view('emails.signup.notifications.contractor_signedup');
    }
}
