<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/10/18
 * Time: 8:16 PM
 */
?>

<div>
    <p>Hi {{ $user->full_normal_name }}</p>
    <p>To complete your registration, <a href="{{ route('show_login') }}">click here </a> to log-in for the first time. </p>
</div>

@extends('emails.footer')