
<style>
input[type="file"] {
    display: none;
}
.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
</style>

<h2 class="text-center">{{ __('Bid #').$bid->id }}</h2>
<div class="card">
    <div class="card-header">
        <h6 class="card-title">Bid details</h6>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Creation date:') }}</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $bid->created_at->format('Y/m/d H:i') }}" />
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Created by:') }}</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $bid->user->full_name }}" />
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Amount:') }}</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext text-dark" value="{{ money_format('%.2n',$bid->price_w_fee) }}" />
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Status:') }}</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $bid->getStatusName() }}" />
            </div>
        </div>


        <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Notes:') }}</label>
            <div class="col-sm-10">
                <p type="text" readonly class="form-control-plaintext text-dark" >{{ $bid->details['notes'] }}</p>
            </div>
        </div>
        @if($bid->contract_signed != "")
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Signed Contract:') }}</label>
            <div class="col-sm-10">
                <a target="_blank"  href="../../storage/{{ isset( $bid->contract_signed) ?  $bid->contract_signed : ''}}">
                    <label class="custom-file-upload">
                        <i class="fa fa-cloud-download"></i>Download
                    </label>
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Order details</h5>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-sm-3 text-right col-form-label">{{ __('Creation Date:') }}</label>
            <div class="col-sm-3">
                <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $bid->order->created_at->format('Y/m/d H:i') }}" />
            </div>
            <label class="col-sm-3 text-right col-form-label">{{ __('Company Name:') }}</label>
            <div class="col-sm-3">
                <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $bid->order->creator->contractor->company_name }}" />
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 text-right col-form-label">{{ __('Site Contact:') }}</label>
            <div class="col-sm-3">
                <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $bid->order->site->contact }}" />
            </div>
            <label class="col-sm-3 text-right col-form-label">{{ __('Cell Number:') }}</label>
            <div class="col-sm-3">
                <input type="text" readonly class="phone_number form-control-plaintext text-dark" value="{{ $bid->order->site->phone }}" />
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 text-right col-form-label">{{ __('Site Address:') }}</label>
            <div class="col-sm-3">
                <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $bid->order->site->address }}" />
            </div>
            <label class="col-sm-3 text-right col-form-label">{{ __('Cell City:') }}</label>
            <div class="col-sm-3">
                <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $bid->order->site->city->name }}" />
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Details</h5>
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
                    <th >
                        {{ __('Unit Price') }}
                    </th>
                    <th >
                        {{ __('Sub Total') }}
                    </th>
                    <th>
                        {{ __('Status') }}
                    </th>
                </tr>

                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('footer_scripts')
    <script src="{{ asset('js/plugins/bootstrap-datetimepicker/moment-datepicker.js') }}" type="application/javascript"></script>
    <script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}" type="application/javascript"></script>

    <script type="application/javascript">

        var equipments = {!! \App\Http\Resources\Buyer\orderItemResource::collection($bid->items)->toJson() !!}
        var equipment_route = "{{ route('contractor.equipment.show',[-1]) }}";

        function format ( data ) {
            console.log(data);
            var template= '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; width: 100%">'+
                '<tr>'+
                '<td>{{ __('Delivery date') }}:</td>'+
                '<td><p>'+(data.from!=null?data.from:'')+'</p></td>' +
                '</tr>'+
                '<tr>'+
                '<td>{{ __('Return date') }}:</td>'+
                '<td><p>'+(data.to!=null?data.to:'')+'</p></td>' +
                '</tr>'+
                '<tr>'+
                '<td>{{ __('Delivery fee') }}:</td>'+
                '<td><p>'+(data.delivery_fee!=null?data.delivery_fee:'')+'</p></td>' +
                '</tr>'+
                '<tr>'+
                '<td>{{ __('Pickup fee') }}:</td>'+
                '<td><p>'+(data.pickup_fee!=null?data.pickup_fee:'')+'</p></td>' +
                '</tr>'+
                '<tr>'+
                '<td>{{ __('Insurance fee') }}:</td>'+
                '<td><p>'+(data.insurance!=null?data.insurance:'')+'</p></td>' +
                '</tr>'+
                '<tr>'+
                '<td>{{ __('Market Place Fee') }}:</td>'+
                '<td><p>'+( ( parseFloat(data.price * data.qty) + parseFloat(data.insurance ) + parseFloat(data.pickup_fee) + parseFloat(data.delivery_fee) ) * (<?php print_r( $fee/100);?>) ).toFixed(2)+'</p></td>' +
                '</tr>'+
                '<tr>'+
                '<td>{{ __('Notes') }}:</td>'+
                '<td><p>'+(data.notes!=null?data.notes:'')+'</p></td>' +
                '</tr>';
                if(data.allow_attachments){
                    template=template +'<tr>'+
                    '<td>{{ __('Attachments') }}:</td>'+
                    '<td><ul>';
                    for(var idx=0;idx<data.attachments.length;idx++){
                        template+='<li><a href="'+data.attachments[idx].url+'">'+data.attachments[idx].name+'</a></li>';
                    }
                    template=template+'</ul></td>' +
                        '</tr>';
                }
             template=template +'</table>';
             return template;
        }

        
        $(document).ready(function() {
            $(".phone_number").hide();
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
                            $(".phone_number").hide();
                            if(data.status == "ACCEPTED"){
                                $(".phone_number").show();
                            }
                            return '<a  href="'+equipment_route.replace('-1',data.id)+'"> '+data.name+' </a>'
                        }  },
                    { "data": "qty"  },
                    { "data": function (data) {
                        return '<span class="">'+data.price+'</span>\n'  ;
                     }},
                    { "data" : function (data) {
                            var market_place_fee = ( ( parseFloat(data.price * data.qty) + parseFloat(data.insurance ) + parseFloat(data.pickup_fee) + parseFloat(data.delivery_fee) ) * (<?php print_r( $fee/100);?>) );
                            var total =  ( parseFloat(data.price * data.qty) + parseFloat(data.insurance ) + parseFloat(data.pickup_fee) + parseFloat(data.delivery_fee) );
                            total = parseFloat(total.toFixed(20)) + parseFloat( market_place_fee.toFixed(2));
                            return  '<div class="input-group mb-3">\n' +
                                    '  <div class="input-group-prepend" style="padding: 0px">\n' +
                                    '<span class="input-group-text">$</span>\n' +
                                    '  </div>\n' +
                                    '<input type="text" class="form-control money sub_total" disabled value="'+(total.toFixed(2))+'" />\n' +
                                    '</div>';
                    }},
                    { "data": "status" },

                ],
                "order": [[1, 'asc']]
            } );

            // Add event listener for opening and closing details
            $('#equipment-table tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                
                var first_col =  $('td:first i',tr).toggleClass('fa-plus-circle fa-minus-circle text-success text-danger');
                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                    //dirty trick
                    $('.money',row.child()).mask('#,##0.00', {reverse: true});
                    $(".date-from").datetimepicker({
                        'format':'YYYY-MM-DD',
                    });
                    $(".date-to").datetimepicker({
                        'format':'YYYY-MM-DD',
                        useCurrent: false
                    });

                    $(".date-from").on("dp.change", function (e) {
                        var id=$(this).attr('data-index');
                        $('.date-to[data-index="'+id+'"]').datetimepicker('minDate', e.date.startOf('day'));
                    });

                    $(".date-to").on("dp.change", function (e) {
                        var id=$(this).attr('data-index');
                        $('.date-from[data-index="'+id+'"]').datetimepicker('maxDate', e.date.startOf('day'));
                    });


                }
            } );

            $('.money').mask('#,##0.00', {reverse: true});
        } );
    </script>
@endpush