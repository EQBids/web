<p>
The Contract made to the order #{{ $bid->order->id }} has been sent. click <a href="{{ route('supplier.bids.index') }}">here</a> to sign your contract.
</p>

@extends('emails.footer')
