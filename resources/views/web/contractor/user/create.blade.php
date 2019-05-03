<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/18/18
 * Time: 8:20 PM
 */
?>
@extends('web.contractor.layout')

@section('content')
    <div class="col-lg-12">
        <a class="btn btn-warning" href="{{ route('contractor.users.index') }}">{{ __('Back') }}</a>
    </div>
    <div class="col-lg-8 offset-lg-2">
        <h1>CREATE USER</h1>
        <form action="{{ route('contractor.users.store') }}" method="post" data-parsley-validate>

            @include('web.contractor.user.form')

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="{{__('Create user')}}" />
                <a class="btn btn-warning" href="{{ route('contractor.users.index') }}">{{ __('Back') }}</a>
            </div>
        </form>
    </div>
@endsection
