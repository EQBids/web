<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/20/18
 * Time: 10:47 PM
 */
?>
@extends('web.contractor.layout')

@section('content')

    <div class="col-lg-12">
        <a href="{{ route('contractor.sites.index') }}" class="btn btn-lg btn-warning">{{ __('Back') }}</a>
    </div>
    <div class="col-lg-12">
        <h1>Edit Site</h1>
        <form action="{{ route('contractor.sites.update',[$site->id]) }}" method="post" data-parsley-validate>
            <input type="hidden" name="_method" value="PUT" />
            @include('web.contractor.job_sites.form')

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="{{__('Update Site')}}" />
                <a href="{{ route('contractor.sites.index') }}" class="btn btn-warning">{{ __('Back') }}</a>
            </div>
        </form>
    </div>

@endsection
