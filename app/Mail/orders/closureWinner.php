<?php

namespace App\Mail\orders;

use App\Models\Buyer\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class closureWinner extends Mailable
{
    use Queueable, SerializesModels;

    protected $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order=$order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    $this->subject('Bid accepted');
	    return $this->view('emails.orders.supplier.accepted')->with(['order'=>$this->order]);
    }
}
