<?php

namespace App\Mail\orders;

use App\Models\Supplier\Bid;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class contractBid extends Mailable
{
    use Queueable, SerializesModels;

    private $bid;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Bid $bid)
    {
        $this->bid=$bid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    $this->subject('Contract Sent');
	    return $this->view('emails.orders.supplier.contract')->with(['bid'=>$this->bid]);
    }
}
