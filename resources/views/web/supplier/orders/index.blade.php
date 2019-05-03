<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 4/12/18
 * Time: 12:21 AM
 */
?>

@extends('web.supplier.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h2>Your Orders</h2>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <table class="table table-striped" id="orders-table">
                <thead>
                <tr>
                    <th scope="col">{{__("Creation date")}}</th>
                    <th scope="col">{{ 'Company' }}</th>
                    <th scope="col">{{__("Destination")}}</th>
                    <th scope="col">{{__("City")}}</th>
                    <th scope="col">{{__("Status")}}</th>
                    <th scope="col">{{__("Actions")}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order_suppliers as $order_supplier)
                    <tr>
                        <td>{{$order_supplier->order->created_at->format('Y/m/d') }}</td>
                        <td>{{ $order_supplier->order->site->contractor->company_name }}</td>
                        <td>{{$order_supplier->order->site->nickname }}</td>
                        <td>{{$order_supplier->order->site->city->name }}</td>
                        <td>{{$order_supplier->status==\App\Models\OrderSupplier::STATUS_REJECTED?$order_supplier->getStatusName():$order_supplier->order->getStatusName() }}</td>
                        <td>
                            @if($order_supplier->is_bidable)
                                <a href="{{ route('supplier.bids.create',[$order_supplier->order_id]) }}" class="btn btn-sm btn-success"><i class="fa fa-gavel"></i>{{ __('Bid') }}</a>
                            @endif
                            @if($order_supplier->bid)
                                <a href="{{ route('supplier.bids.show',[$order_supplier->bid->id]) }}" class="btn btn-sm btn-success"><i class="fa fa-gavel"></i>{{ __('Show Bid') }}</a>
                            @endif
                            <a href="{{ route('supplier.orders.show',[$order_supplier->order->id]) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                            @if($order_supplier->is_cancelable)
                                <a href="{{ route('supplier.orders.delete',[$order_supplier->order_id]) }}" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i></a>
                            @endif

                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('footer_scripts')

    <script>
        $(function () {
            $('#orders-table').dataTable({
                order:[[0,'desc']]
            });
        });
    </script>
@endpush