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
                    <th scope="col">{{__("Contract Signed?")}}</th>
                    <th scope="col">{{__("Actions")}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        
                        <td>{{$order->created_at->format('Y/m/d') }}</td>
                        <td>{{$order->creator->full_name }}</td>
                        <td>{{$order->site->name }}</td>
                        <td>{{$order->getStatusName() }}</td>
                        <td><?php
                                if($order->getHasAcceptedBidsAttribute() > 0 && 
                                $order->getAcceptedBidsAttribute()->get()[0]->contract_signed != ""){
                                    echo "Yes"; 
                                }else{
                                    echo "No";
                                }
                            ?>
                        </td>
                        <td style="width:100% !important;">
                            @if($order->can_assign_bids)
                                <a class="btn btn-sm btn-info" href="{{ route('contractor.orders.bids',[$order->id]) }}"><i class="fa fa-gavel fa-bold "></i>Accept bids</a>
                                
                            @endif
                            @if($order->is_approvable)
                                <a class="btn btn-sm btn-info" href="{{ route('contractor.orders.approval',[$order->id]) }}"><i class="fa fa-check-circle">Approve</i></a>
                            @endif
                            <a class="btn btn-sm btn-info" href="{{ route('contractor.orders.show',[$order->id]) }}"><i class="fa fa-eye"></i>View</a>

                            @if($order->is_editable)
                                <a class="btn btn-sm btn-info" href="{{ route('contractor.orders.edit',[$order->id]) }}"><i class="fa fa-wrench"></i>Edit</a>
                            @endif
                            @if($order->is_editing)
                                <a class="btn btn-sm btn-info" href="{{ route('contractor.orders.edit.site',[$order->id]) }}"><i class="fa fa-wrench"></i>Edit</a>
                            @endif
                            @if($order->is_cancelable)
                                <a class="btn btn-sm btn-info" href="{{ route('contractor.orders.delete',[$order->id]) }}"><i class="fa fa-ban text-danger"></i>Delete</a>
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