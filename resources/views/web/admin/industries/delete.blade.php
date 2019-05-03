<?php
?>
@extends('web.admin.layout')

@section('content')
    <div class="col-lg-8 offset-lg-2">
        <h1>{{__("Delete industry")}}</h1>
        <form action="{{ route('admin.industries.destroy',[$industry->id]) }}" method="post" data-parsley-validate>
            <input type="hidden" name="_method" value="DELETE" />
            {{ csrf_field() }}
            <p>
                <label>{{__("Name")}}: </label> {{ $industry->name }}<br/>
                <label>{{__("Parent")}}: </label><br> {{ $industry->parent?$industry->parent->name:'' }}<br/>
            </p>
            <div class="alert alert-danger">
                {{__('Do you really wan\'t to delete this industry?')}}
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="{{__('Delete')}}" />
            </div>
        </form>
    </div>
@endsection
