<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/26/18
 * Time: 3:39 PM
 */
?>
@extends('web.contractor.orders.edit.layout')

@section('process_content')
    <h2 class="text-center">{{ __('Please check everything is as you need!') }}</h2>
    <hr/>
    <div class="card">
        <div class="card-header">
            {{ __('Destination') }}
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('Name') }}:</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $site->name }}</p>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('Address') }}:</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $site->address }}</p>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('City/State') }}:</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $site->city->name }}/{{ $site->state->name }}</p>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('Phone') }}:</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $site->phone }}</p>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('Contact name') }}:</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $site->contact }}</p>
                </div>
            </div>

        </div>

    </div>

    <div class="card mt-50">
        <div class="card-header">
            {{ __('Desired suppliers') }}
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered" >
                <thead>
                <tr>
                    <th>Distance (km)</th>
                    <th>Name</th>
                    <th>{{ __('Details') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($suppliers as $supplier)
                    <tr>
                        <td>
                            {{ round($supplier->distance,1) }}
                        </td>
                        <td>
                            {{ $supplier->name }}
                        </td>
                        <td>
                            <a href="#" class="btn-primary btn text-center" target="_blank" >View</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-50">
        <div class="card-header">
            {{ __('Equipment') }}
        </div>
        <div class="card-body table-responsive">
            <table id="equipment-table" class="table shopping-cart-page table-bordered">
                <thead>
                <tr>
                    <th rowspan="2">
                        {{ __('Product') }}
                    </th>
                    <th rowspan="2">
                        {{ __('Product name') }}
                    </th>
                    <th colspan="2" class="text-center">
                        {{ __('Dates') }}
                    </th>
                    <th rowspan="2">
                        {{ __('Quantity') }}
                    </th>
                </tr>
                <tr>
                    <th>
                        {{ __('From') }}:
                    </th>
                    <th>
                        {{ __('To') }}:
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td class="image">
                            <a class="media-link" href="{{ route('contractor.equipment.show',[$item['id']]) }}">
                                <img class="img-fluid" src="{{ asset($item['image']) }}" alt="">
                            </a>
                        </td>
                        <td>
                            <a  href="{{ route('contractor.equipment.show',[$item['id']]) }}"> {{ $item['name'] }} </a>
                        </td>
                        <td>
                            <p class="form-control-plaintext">
                                {{ $item['from']?$item['from']:'' }}
                            </p>
                        </td>
                        <td>
                            <p class="form-control-plaintext">
                                {{ $item['to']?$item['to']:''  }}
                            </p>
                        </td>
                        <td>
                            <p class="form-control-plaintext">
                                {{ $item['qty'] }}
                            </p>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <form method="post" action="{{ route('contractor.orders.update',[$order->id]) }}">
        <div class="row mt-50">

                {{ csrf_field() }}
                {{ method_field('put') }}
                <div class="col-lg-6">
                    <a class="btn btn-warning pull-right" href="{{ route('contractor.orders.edit.details',[$order->id]) }}" ><i class="fa fa-angle-double-left" ></i> {{ __('Back') }}</a>
                </div>
                <div class="col-lg-4">
                    <input type="submit" class="btn btn-success" value="{{ __('Finish the edit process') }}" />
                </div>

        </div>
    </form>
@endsection

@push('before_footer_scripts')
    <script type="text/javascript">
        var stepwizard_step = 4;
    </script>
@endpush