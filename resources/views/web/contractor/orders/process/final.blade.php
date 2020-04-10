<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/26/18
 * Time: 3:39 PM
 */
?>
@extends('web.contractor.orders.process.layout')

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
                        {{ $supplier->distance == 0 ? 'Local' : round($supplier->distance,1) }}
                        </td>
                        <td>
                            {{ $supplier->name }}
                        </td>
                        <td>
                            <a href="{{ route('contractor.suppliers.view',[$supplier->id]) }}" class="btn-primary btn text-center" target="_blank" >View</a>
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
                    <th rowspan="2"></th>
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
                @foreach($cart->items as $item)
                    <tr data-equipment-id="{{$item->id}}">
                        <td></td>
                        <td class="image">
                            <a class="media-link" href="{{ route('contractor.equipment.show',[$item->id]) }}">
                                <img class="img-fluid" src="{{ asset($item->image_path) }}" alt="">
                            </a>
                        </td>
                        <td>
                            <a  href="{{ route('contractor.equipment.show',[$item->id]) }}"> {{ $item->name }} </a>
                        </td>
                        <td>
                            <p class="form-control-plaintext">
                                {{ $item->pivot->from?$item->pivot->from->format('Y-m-d'):'' }}
                            </p>
                        </td>
                        <td>
                            <p class="form-control-plaintext">
                                {{ $item->pivot->to?$item->pivot->to->format('Y-m-d'):''  }}
                            </p>
                        </td>
                        <td>
                            <p class="form-control-plaintext">
                                {{ $item->pivot->qty }}
                            </p>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <form method="post" action="{{ route('contractor.orders.store') }}">
        <div class="row mt-50">

                {{ csrf_field() }}
                {{ method_field('post') }}
                <div class="col-lg-6">
                    <a class="btn btn-warning pull-right" href="{{ route('contractor.orders.process.details') }}" ><i class="fa fa-angle-double-left" ></i> {{ __('Back') }}</a>
                </div>
                <div class="col-lg-4">
                    <input type="submit" class="btn btn-success finish-order" value="{{ __('Finish the order') }}" />
                </div>

        </div>
    </form>
@endsection

@push('before_footer_scripts')
    <script type="text/javascript">
        var stepwizard_step = 5;

    </script>
@endpush

@push('footer_scripts')
    <script type="application/javascript">
        function setCookie(name,value,days) {
				var expires = "";
				if (days) {
						var date = new Date();
						date.setTime(date.getTime() + (days*24*60*60*1000));
						expires = "; expires=" + date.toUTCString();
				}
				document.cookie = name + "=" + (value || "")  + expires + "; path=/";
        }
        $(".finish-order").click(function(){
            setCookie("shopping_cart_count", "0", 365);
        })
        

        var equipments_map={!! json_encode(\App\Http\Resources\Product\CartEquipmentResource::collection($cart->items)->keyBy('id'))  !!};

        function format ( id ) {

            var template= '<div>' +
                '<label>{{'Notes:'}}</label><br/>'+
                '<div>'+((equipments_map[id]!=undefined)?equipments_map[id].order_notes:'')+'</div>'+
                '</div>';
            return template;

        }

        var table=$('#equipment-table').DataTable({
            columnDefs:[
                {
                    targets:[0],
                    "className":      'details-control',
                    "orderable":      false,
                    "data":           function () {
                        return '<i class="fa fa-plus-circle text-success"></i>'
                    },
                    "defaultContent": '',
                    orderable:false,
                },
                { targets: [3,4], orderable: false, width:'20%' },
                {targets: [5], orderable: false,width:'15%'}
            ],

        });

        $('#equipment-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child(format(tr.data('equipment-id'))).show();
                tr.addClass('shown');
            }
        } );
    </script>
@endpush