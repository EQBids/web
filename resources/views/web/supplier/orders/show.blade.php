<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 5/3/18
 * Time: 12:01 AM
 */
?>
@extends('web.supplier.layout')

@section('content')
    <div class="row mb-20">
        <div class="col-lg-12">
            <a href="{{ url()->previous() }}" class="btn btn-primary">{{__("Back")}}</a>
        </div>
    </div>
    @include('web.supplier.orders.details')
@endsection
