<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/19/18
 * Time: 9:51 PM
 */
?>
@extends('web.supplier.layout')

@section('content')
    <div class="col-lg-12">
        <a class="btn btn-warning" href="{{ route('supplier.users.index') }}">{{ __('Back') }}</a>
    </div>

    <div class="col-lg-8 offset-lg-2">
        <h1>Edit user</h1>
        <form action="{{ route('supplier.users.update',[$user->id]) }}" method="post" data-parsley-validate>
            <input type="hidden" name="_method" value="PUT" />
            @include('web.supplier.user.form')

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="{{__('Update user')}}" />
                <a class="btn btn-warning" href="{{ route('supplier.users.index') }}">{{ __('Back') }}</a>
            </div>
        </form>
    </div>
@endsection
