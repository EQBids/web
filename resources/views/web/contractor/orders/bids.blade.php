@extends('web.contractor.layout')


@section('content')

    <div class="row mb-20">
        <div class="col-lg-12">
            <a href="{{ url()->route('contractor.orders.index') }}" class="btn btn-primary">{{__("Back")}}</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{ route('contractor.orders.bid',[$order->id]) }}">
                @include('web.partials.show_errors')
                <h2>{{ __('Accept bids for order#') }} {{$order->id}}</h2>

                <div class="card mt-20">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">{{ __('Creation date:') }}</label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $order->created_at->format('Y/m/d H:i') }}" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">{{ __('Company name:') }}</label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $order->creator->contractor->company_name }}" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-30">
                    <div class="card-header">
                        <h3>{{ __('Equipment') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="equipment-table" class="table shopping-cart-page table-bordered">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th >
                                        {{ __('Product') }}
                                    </th>
                                    <th >
                                        {{ __('Product name') }}
                                    </th>
                                    <th >
                                        {{ __('Quantity') }}
                                    </th>
                                    <th>
                                        {{ __('Bids #') }}
                                    </th>
                                </tr>

                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-50">

                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <div class="col-lg-4">
                        <a class="btn btn-warning pull-right" href="{{ url()->previous() }}" ><i class="fa fa-angle-double-left" ></i> {{ __('Back') }}</a>
                    </div>
                    <div class="offset-1 col-lg-3">
                        <input type="submit" class="btn btn-success" value="{{ __('Update accepted bids') }}" />
                    </div>
                    <div class="col-lg-4">
                        @if($order->has_accepted_bids)
                            <a href="{{ route('contractor.orders.close',[$order->id]) }}" class="btn btn-info" >{{ __('Close the order') }}</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script src="{{ asset('js/plugins/bootstrap-datetimepicker/moment-datepicker.js') }}" type="application/javascript"></script>
    <script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}" type="application/javascript"></script>

    <script type="application/javascript">

        var equipments = {!! \App\Http\Resources\Buyer\orderItemResource::collection($order->items)->toJson() !!}
        var equipment_route = "{{ route('contractor.equipment.show',[-1]) }}";
        var suppliers = {{ $order->suppliers->count() }};

        function bid_chosen(id,order_id){
            $(".bid_id_" + order_id).val(id);
        }
        function format ( data ,index) {
            console.log('');
            var accepted_bid_id=undefined;
            var html = '<div id="bids_'+data.id+'">' +
                    '<div class="card" style="width: 100%">';


                for(var idx=0;idx<data.bids.length;idx++){
                    var bid = data.bids[idx];
                    if (bid.status=='ACCEPTED') {
                        accepted_bid_id = bid.id;
                    }
                    var qtde = document.getElementsByClassName('qtde')[index] != undefined ? document.getElementsByClassName('qtde')[index].innerText : 0
                   
                    html+='<div class="card-body row '+(bid.status=='ACCEPTED'?'picked-bid':'')+'">' +
                            '<div class="col-md-5">'+
                        '<p>' +
                        '<b>{{__('Supplier:') }} '+bid.supplier.name+'</b><br/>'+
                        '{{__('Price:')}} '+bid.price +'<br/>'+
                        '{{__('Delivery fee:')}} '+bid.delivery_fee +'<br/>'+
                        '{{__('Pickup fee:')}} '+bid.pickup_fee +'<br/>'+
                        '{{__('Insurance:')}} '+ (bid.insurance ) +'<br/>'+
                        '<b>{{__('Total:') }} $'+( bid.insurance  * 1 + (bid.price  * parseFloat(qtde) ) + parseFloat(bid.delivery_fee) + parseFloat(bid.pickup_fee) )+'</b><br/>'+               
                        '</p>'+
                        '</div>' +
                            '<div class="col-md-4">'+
                                '<p>'+
                                    '{{__('Delivary date:')}} '+(bid.deliv_date?bid.deliv_date:'') +'<br/>'+
                                    '{{__('Return date:')}} '+(bid.return_date?bid.return_date:'') +'<br/>'+
                                '</p>'+
                            '</div>'+
                        '<div class="col-md-3">' +
                        '<button type="button" onclick="bid_chosen('+bid.id+','+data.oid+')"; class="btn btn-success pick_btn pull-right" role="button" data-id="'+bid.id+'" >{{ __('Accept') }}</button>' +
                        '</div>' +
                        '</div>'+
                            '<div class="card-body row '+(bid.status=='ACCEPTED'?'picked-bid':'')+'">' +
                            '<p>'+
                        '{{__('Notes:')}} '+
                        '</p>' +
                        '<p>'+bid.notes+'</p>'+
                    '</div>' ;
                    
                }
                html+='<input type="hidden"  class="bid_id_' + data.oid + '" name="bids['+data.oid+'][bid_id]" />';
                html+='<input type="hidden" name="bids['+data.oid+'][id]" value="'+data.oid+'" />';
                html+='<input type="hidden" class="bid" name="bids['+data.oid+'][bid]" value="'+((accepted_bid_id!=undefined)?accepted_bid_id:'')+' />';

                html += '</div>'+
                '</div>';
                return html;
        }

        

        $(document).ready(function() {
            $(".btn-success").hide();
            var table = $('#equipment-table').DataTable( {
                data:equipments,
                "columns": [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           function () {
                            return '<i class="fa fa-plus-circle text-success"></i>'
                        },
                        "defaultContent": ''
                    },
                    { "orderable":      false,
                        "data": function (data) {
                            return ' <div class="image"> <a class="media-link" href="'+equipment_route.replace('-1',data.id)+'">\n' +
                                '       <img class="img-fluid" src="'+data.image+'" alt="">\n' +
                                '</a></div>'
                        }
                    },
                    { "data": function (data) {
                            return '<a  href="'+equipment_route.replace('-1',data.id)+'"> '+data.name+' </a>'
                        }  },
                    { "data": function (data) {
                            return '<label class="qtde">'+data.qty+'</label>';
                        }
                    
                    },
                    { "data": function (data) {
                            return data.bids.length+'/'+suppliers;
                        }
                    }
                ],
                "order": [[1, 'asc']]
            } );

            $( "tbody tr" ).each(function( index ) {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                row.child( format(row.data() , index ) ).show();
                
                $('.pick_btn',row.child()).on('click',function(){
                
                    $(".btn-success").show();
                    var _tr = $(this).closest('tr');
                    //var _row = table.row( _tr );
                    $('input.bid',_tr).val($(this).attr('data-id'));
                    $('.card-body',_tr).removeClass('picked-bid');
                    $(this).closest('.card-body').addClass('picked-bid');
                });

            });

            
            // Add event listener for opening and closing details
            $('#equipment-table tbody').on('click', 'td.details-control', function () {
                console.log("x");
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                var first_col =  $('td:first i',tr).toggleClass('fa-plus-circle fa-minus-circle text-success text-danger');
                //if ( row.child.isShown() ) {
                    // This row is already open - close it
                    //row.child.hide();
                    //tr.removeClass('');
                //}
                //else {
                    // Open this row
                    //row.child( format(row.data()) ).show();
                /*$('.pick_btn',row.child()).on('click',function(){
                    alert("x");
                    var _tr = $(this).closest('tr');
                    //var _row = table.row( _tr );
                    $('input.bid',_tr).val($(this).attr('data-id'));
                    $('.card-body',_tr).removeClass('picked-bid');
                    $(this).closest('.card-body').addClass('picked-bid');
                });*/
                //}
            } );

        } );
    </script>
@endpush