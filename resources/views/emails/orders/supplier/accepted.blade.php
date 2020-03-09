<p>
    The Bid made to the bid #{{ $order->getAcceptedBidsAttribute()->get()[0]->id }} has been accepted. click <a href="{{ route('supplier.bids.index') }}">here</a> to finish the process.
</p>

@extends('emails.footer')
