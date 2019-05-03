<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 4/12/18
 * Time: 12:21 AM
 */
?>

@extends('web.contractor.layout')

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
                    <th scope="col">{{__("Creator")}}</th>
                    <th scope="col">{{__("Destination")}}</th>
                    <th scope="col">{{__("Status")}}</th>
                    <th scope="col">{{__("Actions")}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{$order->created_at->format('Y/m/d') }}</td>
                        <td>{{$order->creator->full_name }}</td>
                        <td>{{$order->site->nickname }}</td>
                        <td>{{$order->getStatusName() }}</td>
                        <td>
                            @if($order->can_assign_bids)
                                <a class="btn btn-sm btn-info" href="{{ route('contractor.orders.bids',[$order->id]) }}"><i class="fa fa-gavel fa-bold "></i>Assign bids</a>
                                <br/>
                            @endif
                            @if($order->is_approvable)
                                <a href="{{ route('contractor.orders.approval',[$order->id]) }}"><i class="fa fa-check-circle"></i></a>
                            @endif
                            <a href="{{ route('contractor.orders.show',[$order->id]) }}"><i class="fa fa-eye"></i></a>

                            @if($order->is_editable)
                                <a href="{{ route('contractor.orders.edit',[$order->id]) }}"><i class="fa fa-wrench"></i></a>
                            @endif
                            @if($order->is_editing)
                                <a href="{{ route('contractor.orders.edit.site',[$order->id]) }}"><i class="fa fa-wrench"></i></a>
                            @endif
                            @if($order->is_cancelable)
                                <a href="{{ route('contractor.orders.delete',[$order->id]) }}"><i class="fa fa-ban text-danger"></i></a>
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