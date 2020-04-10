<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/18/18
 * Time: 5:23 PM
 */
?>
@extends('web.admin.layout')

@section('content')


    <div class="col-lg-12">
        <h1>{{__('Users')}}</h1>
        <div class="row" style="margin-bottom: 30px">
            <div class="col-lg-12">
                <a class="btn btn-primary pull-right" href="{{ route('admin.users.create') }}">{{ __('Create user') }}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table id="dttable" class="table table-striped table-bordered" style="width: 100%">
                    <thead>
                        <tr>
                            <th>{{ __('Date')}}</th>
                            <th>{{ __('Company name')}}</th>
                            <th>{{ __('Full name')}}</th>
                            <th>{{ __('City') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td>{{ $item->created_at?$item->created_at->format('Y-m-d H:i'):'' }}</td>
                                <td>
                                    @if($item->contractor)
                                        {{ $item->contractor->company_name }}
                                    @endif
                                    @if($item->supplier)
                                        {{ $item->supplier->name }}
                                    @endif
                                    @if($item->is_admin)
                                        {{ __('EQBIDS staff') }}
                                    @endif
                                </td>
                                <td>{{ $item->full_name }}</td>
                                <td>
                                    @if($item->city)
                                        {{ $item->city->name }}
                                    @endif
                                </td>
                                <td>
                                    {{ $item->getStatusName() }}
                                </td>
                                <td>
                                        <a class="btn btn-primary" href="{{ route('admin.users.edit',[$item->id]) }}">{{__('Edit')}}</a>
                                        <a class="btn btn-danger" href="{{ route('admin.users.delete',[$item->id]) }}">{{__('Delete')}}</a>

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