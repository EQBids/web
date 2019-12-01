<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/20/18
 * Time: 9:55 PM
 */
?>
@extends('web.contractor.layout')


@if($sites==null)
@section('content')
    <div class="col-lg-12">
        <div class="alert alert-danger">
            <p>{{ __('You don\'t have or belong to any contractor') }}</p>
        </div>
    </div>
@endsection
@else
@section('content')

    <div class="col-lg-12">
        <h1>Job sites</h1>
        <div class="row" style="margin-bottom: 30px">
            <div class="col-lg-12">
                <a class="btn btn-primary pull-right" href="{{ route('contractor.sites.create') }}">{{ __('Create new job site') }}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table id="dttable" class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('City') }}</th>
                            <th>{{ __('State/Province') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sites as $site)
                            <tr>
                                <td>{{ $site->name }}</td>
                                <td>{{ $site->city?$site->city->name:'' }}</td>
                                <td>{{ $site->state?$site->state->iso_code:'' }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ route('contractor.sites.show',[$site->id]) }}">{{__('View')}}</a>
                                    <a class="btn btn-primary btn-sm" href="{{ route('contractor.sites.edit',[$site->id]) }}">{{__('Edit')}}</a>
                                    <a class="btn btn-danger btn-sm" href="{{ route('contractor.sites.delete',[$site->id]) }}">{{__('Delete')}}</a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('footer_scripts')
    <script>
        $(document).ready(function () {
            $('#dttable').dataTable();
        });
    </script>
@endpush

@endif
