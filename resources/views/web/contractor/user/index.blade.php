<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/18/18
 * Time: 5:23 PM
 */
?>
@extends('web.contractor.layout')

@section('content')


    <div class="col-lg-12">
        <h1>{{__('Users')}}</h1>
        <div class="row" style="margin-bottom: 30px">
            <div class="col-lg-12">
                <a class="btn btn-primary pull-right" href="{{ route('contractor.users.create') }}">{{ __('Create user') }}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table id="dttable" class="table table-striped table-bordered" style="width: 100%">
                    <thead>
                        <tr>
                            <th>{{ __('Email')}}</th>
                            <th>{{ __('Full name')}}</th>
                            <th>{{ __('Role(s)') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->full_name }}</td>
                                <td>
                                    @foreach($item->rols as $rol)
                                        <li>{{ $rol->name }}</li>
                                    @endforeach
                                </td>
                                <td>
                                    {{ $item->getStatusName() }}
                                </td>
                                <td>
                                        <a class="btn btn-primary" href="{{ route('contractor.users.edit',[$item->id]) }}">{{__('Edit')}}</a>
                                        <a class="btn btn-danger" href="{{ route('contractor.users.delete',[$item->id]) }}">{{__('Delete')}}</a>

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