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
            <h2>Your Bids</h2>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <table class="table table-striped" id="orders-table">
                <thead>
                <tr>
                    <th scope="col">{{__("Order")}}</th>
                    <th scope="col">{{__("Creation date")}}</th>
                    <th scope="col">{{__("Created by")}}</th>
                    <th scope="col">{{__("Amount")}}</th>
                    <th scope="col">{{__("Status")}}</th>
                    <th scope="col">{{__("Contract")}}</th>
                    <th scope="col">{{__("Actions")}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($bids as $bid)
                    <tr>
                        <td>{{$bid->order_id }}</td>
                        <td>{{$bid->created_at->format('Y/m/d') }}</td>
                        <td>{{$bid->user->full_name }}</td>
                        <td>$ {{ money_format('%.2n',$bid->price_w_fee) }}</td>
                        <td>{{$bid->getStatusName() }}</td>
                        <td>{{isset($bid->contract) ? "Sent" : "Not Sent"}}</td>
                        <td>
                            <a href="{{ route('supplier.bids.show',[$bid->id]) }}" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>
                            @if($bid->is_editable)
                                <a class="btn btn-info btn-sm" href="{{ route('supplier.bids.edit',[$bid->id]) }}"><i class="fa fa-wrench"></i></a>
                            @endif

                            @if($bid->is_cancelable)
                                <a class="btn btn-danger btn-sm" href="{{ route('supplier.bids.cancel',[$bid->id]) }}"><i class="fa fa-ban"></i></a>
                            @endif

                            @if($bid->is_approvable)
                                <a class="btn btn-info btn-sm" href="{{ route('supplier.bids.approval',[$bid->id]) }}"><i class="fa fa-repeat"></i></a>
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