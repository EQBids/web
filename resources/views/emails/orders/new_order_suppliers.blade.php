<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 4/11/18
 * Time: 11:55 PM
 */
?>

<p>
    A new order has been received for which your company qualifies. To review the order and place a bid, please click the link below.
    <a href="{{ route('supplier.orders.index') }}">LINK TO PLATFORM</a>
</p>

@extends('emails.footer')