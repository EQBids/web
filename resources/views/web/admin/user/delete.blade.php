<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/19/18
 * Time: 9:51 PM
 */
?>
@extends('web.admin.layout')

@section('content')
    <div class="col-lg-8 offset-lg-2">
        <h1>Delete user</h1>
        <form action="{{ route('admin.users.destroy',[$user->id]) }}" method="post" data-parsley-validate>
            <input type="hidden" name="_method" value="DELETE" />
            {{ csrf_field() }}
            <p>
            <label>Full name: </label>{{ $user->full_formal_name }}<br/>
            <label>Role: </label> {{ $user->rols()->first()?$user->rols()->first()->name:'' }}
            </p>
            <div class="alert alert-danger">
                {{__('Do you really wan\'t to delete this user?')}}
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="{{__('Delete user')}}" />
            </div>
        </form>
    </div>
@endsection
