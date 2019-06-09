{{ $user->full_formal_name }} {{_('Your email has been changed to this one')}}
<a href="{{route('show_login')}}">Login</a>

@extends('emails.footer')