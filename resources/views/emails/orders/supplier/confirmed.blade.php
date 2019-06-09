<p>
    The Bid #{{ $bid->id }} made by <b>{{ $bid->supplier->name }}</b> to the order #{{ $bid->order->id }} has been confirmed.
</p>

@extends('emails.footer')
