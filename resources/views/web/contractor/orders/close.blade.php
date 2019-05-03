@extends('web.contractor.layout')



@section('content')

    <div class="alert alert-warning">
        <h3>{{ __('Close the order') }}</h3>
        <p>
            {{ __('The order cannot be edited after closing it; suppliers will be notified and must confirm to fully end the process. if a supplier retract the bid, you will be notified.') }}
        </p>
        <form method="post" action="{{ route('contractor.orders.close',[$order->id]) }}">
            {{ method_field('put') }}
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-1 offset-8">
                    <a  class="btn btn-danger" href="{{ url()->previous() }}">{{ __('Back') }}</a>
                </div>
                <div class="col-md-3">
                    <input type="submit" class="btn btn-success" value="{{ __('Close the order') }}">
                </div>
            </div>

        </form>
    </div>

    <h3> {{ __('Order #').$order->id }}</h3>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">{{ __('Creation date:') }}</label>
        <div class="col-sm-10">
            <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $order->created_at->format('Y/m/d H:i') }}" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">{{ __('Created by:') }}</label>
        <div class="col-sm-10">
            <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $order->creator->full_name }}" />
        </div>
    </div>
    <h4>{{ __('Equipment\'s') }}</h4>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="table-responsive">
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
                            {{ __('Qty') }}
                        </th>
                        <th rowspan="2">
                            {{ __('Accepted bid') }}
                        </th>
                    </tr>
                    <tr>
                        <th>
                            {{ __('From') }}:
                        </th>
                        <th >
                            {{ __('To') }}:
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->items as $item)
                        @if($item->equipment)
                            <tr data-equipment-id="{{$item->id}}">
                                <td></td>
                                <td class="image">
                                    <a class="media-link" href="{{ route('contractor.equipment.show',[$item->equipment->id]) }}">
                                        <img class="img-fluid" src="{{ asset($item->equipment->image_path) }}" alt="">
                                    </a>
                                </td>
                                <td>
                                    <a  href="{{ route('contractor.equipment.show',[$item->equipment->id]) }}"> {{ $item->equipment->name }} </a>
                                </td>
                                <td>
                                    <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $item->deliv_date->format('Y/m/d') }}" />
                                </td>
                                <td>
                                    <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $item->return_date->format('Y/m/d') }}" />
                                </td>
                                <td>
                                    <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $item->qty }}" />
                                </td>
                                <td>
                                    {{ $item->accepted_bid?'YES':'NO' }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('footer_scripts')
        <script type="application/javascript">

            var equipments_map={!! json_encode(\App\Http\Resources\Buyer\orderItemResource::collection($order->items)->keyBy('id'))  !!};

            function format ( id ) {
                var template= '<div>' +
                    '<label>{{'Notes:'}}</label><br/>'+
                    '<div>'+((equipments_map[id]!=undefined)?equipments_map[id].order_notes:'')+'</div>'+
                    '</div>';

                if(equipments_map[id].accepted_bid){
                    var bid=equipments_map[id].accepted_bid;
                    template+=
                        '<h5 class="mt-30">Accepted Bid:</h5>'+
                        '<div class="card-body row">' +
                        '<div class="col-md-5">'+
                        '<p>' +
                        '<b>{{__('Supplier:') }} '+bid.supplier.name+'</b><br/>'+
                        '{{__('Price:')}} '+bid.price +'<br/>'+
                        '{{__('Delivery fee:')}} '+bid.delivery_fee +'<br/>'+
                        '{{__('Pickup fee:')}} '+bid.pickup_fee +'<br/>'+
                        '{{__('Insurance:')}} '+bid.insurance +'<br/>'+
                        '<b>{{__('Total:') }} $'+bid.total+'</b><br/>'+
                        '</p>'+
                        '</div>' +
                        '<div class="col-md-4">'+
                        '<p>'+
                        '{{__('Delivary date:')}} '+(bid.deliv_date?bid.deliv_date:'') +'<br/>'+
                        '{{__('Return date:')}} '+(bid.return_date?bid.return_date:'') +'<br/>'+
                        '</p>'+
                        '</div>'+
                        '<div class="col-md-3">' +
                        '</div>' +
                        '</div>'+
                        '<div class="card-body row">' +
                        '<p>'+
                        '{{__('Notes:')}} '+
                        '</p>' +
                        '<p>'+bid.notes+'</p>'+
                        '</div>';
                }
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

            $('#suppliers-table').DataTable();
        </script>
    @endpush


@endsection
