<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/23/18
 * Time: 8:48 PM
 */
?>
@extends('web.admin.layout')

@section('content')
    <div class="col-lg-12">
        <a href="{{ route('admin.sites.index') }}" class="btn btn-lg btn-warning">{{ __('Back') }}</a>
    </div>
    <div class="col-lg-8 offset-lg-2">
        <h1>Delete Site</h1>
        <form action="{{ route('admin.sites.destroy',[$site->id]) }}" method="post" data-parsley-validate>
            <input type="hidden" name="_method" value="DELETE" />
            {{ csrf_field() }}
            <div class="alert alert-danger">
                {{__('Do you really wan\'t to delete this site?')}}
            </div>
            @include('web.contractor.job_sites.details')

            <div class="form-group">
                <input type="submit" class="btn btn-danger" value="{{__('Delete site')}}" />
                <a href="{{ route('contractor.sites.index') }}" class="btn btn-warning">{{ __('Back') }}</a>
            </div>
        </form>
    </div>
@endsection
