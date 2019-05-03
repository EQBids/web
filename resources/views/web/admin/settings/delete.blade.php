<?php
?>
@extends('web.admin.layout')

@section('content')
    <div class="col-lg-8 offset-lg-2">
        <h1>{{__("Delete setting")}}</h1>
        <form action="{{ route('admin.settings.destroy',[$setting->id]) }}" method="post" data-parsley-validate>
            <input type="hidden" name="_method" value="DELETE" />
            {{ csrf_field() }}
            <p>
                <label>{{__("Name")}}: </label> {{$setting->name}}<br/>
                <label>{{__("Description")}}: </label><br> {{$setting->description}}<br/>
            </p>
            <div class="alert alert-danger">
                {{__('Do you really wan\'t to delete this setting?')}}
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="{{__('Delete')}}" />
            </div>
        </form>
    </div>
@endsection
