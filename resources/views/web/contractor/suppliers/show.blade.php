<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/21/18
 * Time: 4:11 PM
 */
?>

@extends('web.contractor.layout')

@section('content')
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <a class="btn btn-warning" href="{{ route('contractor.suppliers.index')  }}">{{ __('Back') }}</a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12" style="margin-top: 40px">
                @include('web.contractor.suppliers.details')
            </div>
        </div>
    </div>





@endsection
