<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 4/8/18
 * Time: 3:54 PM
 */
?>
@extends('web.contractor.orders.edit.layout')

@push('styles')
    <style type="text/css">
        .table td{
            position:relative;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow:visible;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }

    </style>
@endpush

@section('process_content')
    <h2 class="text-center mb-60">{{ __('Quantities and dates') }}</h2>
    @include('web.partials.show_errors')
    <form method="post" action="{{ route('contractor.orders.edit.details.store',[$order->id]) }}" id="form" data-parsley-validate>
        {{ csrf_field() }}
        {{ method_field('put') }}
        <div class="row mb-80">
            <div class="col-md-12">
                <label>Dates for all:</label>
            </div>
            <div class="col-md-1">
                <label>From: </label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="general_from_date" />
            </div>
            <div class="">
                <label> To:</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="general_to_date" />
            </div>
            <div class="col-md-1">
                <button id="btndates" type="button" class="btn btn-info">{{ __('Set') }}</button>
            </div>
        </div>
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
                            <tr data-equipment-id="{{$item['id']}}">
                                <td></td>
                                <td class="image">
                                    <a class="media-link" href="{{ route('contractor.equipment.show',[$item['id']]) }}">
                                        <img class="img-fluid" src="{{ asset($item['image']) }}" alt="">
                                    </a>
                                </td>
                                <td>
                                    <a  href="{{ route('contractor.equipment.show',[$item['id']]) }}"> {{ $item['name'] }} </a>
                                </td>
                                <td>
                                    <input type="hidden" required name="equipments[{{ $item['id'] }}][id]" value="{{ old('equipments.'.$item['id'].'.id',$item['id']) }}" />
                                    <input type="text" required class="form-control date-from"
                                           data-index="{{ $item['id'] }}"
                                           name="equipments[{{ $item['id'] }}][from]"
                                           value="{{ old('equipments.'.$item['id'].'.from',$item['from']) }}"
                                    />
                                </td>
                                <td>
                                    <input type="text" required class="form-control date-to"
                                           data-index="{{ $item['id'] }}"
                                           name="equipments[{{ $item['id'] }}][to]"
                                           value="{{ old('equipments.'.$item['id'].'.to',$item['to']) }}"
                                    />
                                </td>
                                <td>
                                    <input type="number" min="0" max="1000" class="form-control qty"
                                        data-parsley-required="true"
                                       @if($loop->index==0) data-parsley-qtyrequired=".qty" @endif
                                           name="equipments[{{ $item['id'] }}][qty]"
                                           value="{{ old('equipments.'.$item['id'].'.qty',$item['qty']) }}"
                                    />
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mt-50">
            <div class="col-offset-sm-8 col-sm-4">

            </div>
        </div>

        <div class="row mt-80">
            <div class="col-lg-6">
                <a class="btn btn-warning pull-right" href="{{ route('contractor.orders.process.suppliers') }}" ><i class="fa fa-angle-double-left" ></i> {{ __('Back') }}</a>
            </div>
            <div class="col-lg-4">
                <input type="submit" class="btn btn-success" value="{{ __('Continue') }}" />
            </div>
        </div>
    </form>
@endsection



@push('before_footer_scripts')
    <script type="text/javascript">
        var highlight_url="{{  route('contractor.cart') }}";
    </script>

    <script type="text/javascript">
        var stepwizard_step = 3;
    </script>

@endpush

@push('footer_scripts')
    <script src="{{ asset('js/plugins/bootstrap-datetimepicker/moment-datepicker.js') }}" type="application/javascript"></script>
    <script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}" type="application/javascript"></script>
    <script type="text/javascript">

        var equipments_map={!! json_encode(\App\Http\Resources\Buyer\orderItemResource::collection($order->items)->keyBy('equipment.id'))  !!};

        function format ( id ) {
            var template= '<div>' +
                '<label>{{'Notes:'}}</label><br/>'+
                '<textarea class="input-control w-100" max-length="1000" name="equipments['+id+'][notes]" id="equipments_'+id+'_notes">'+((equipments_map[id]!=undefined)?equipments_map[id].order_notes:'')+'</textarea>'+
                '</div>';
            return template;

        }

        var table = $('#equipment-table').DataTable({
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
            ]
        });

        $('#equipment-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );

            var first_col =  $('td:first i',tr).toggleClass('fa-plus-circle fa-minus-circle text-success text-danger');
            if ( row.child.isShown() ) {
                // This row is already open - close it
                if(equipments_map[tr.data('equipment-id')]==undefined){
                    equipments_map[tr.data('equipment-id')]=[];
                }
                equipments_map[tr.data('equipment-id')].order_notes=$('#equipments_'+tr.data('equipment-id')+'_notes').val();
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child(format(tr.data('equipment-id'))).show();
                tr.addClass('shown');
            }
        } );

        $("#general_from_date").datetimepicker({
            'format':'YYYY-MM-DD',
        });
        $("#general_to_date").datetimepicker({
            'format':'YYYY-MM-DD',
            useCurrent: false
        });

        $(".date-from").datetimepicker({
            'format':'YYYY-MM-DD',
        });
        $(".date-to").datetimepicker({
            'format':'YYYY-MM-DD',
            useCurrent: false
        });


        $("#general_from_date").on("dp.change", function (e) {
            $('#general_to_date').datetimepicker('minDate', e.date.startOf('day'));
        });
        $("#general_to_date").on("dp.change", function (e) {
            $('#general_from_date').datetimepicker('maxDate', e.date.startOf('day'));
        });

        $(".date-from").on("dp.change", function (e) {
            var id=$(this).attr('data-index');
            $('.date-to[data-index="'+id+'"]').datetimepicker('minDate', e.date.startOf('day'));
        });

        $(".date-to").on("dp.change", function (e) {
            var id=$(this).attr('data-index');
            $('.date-from[data-index="'+id+'"]').datetimepicker('maxDate', e.date.startOf('day'));
        });

        $('#btndates').on('click',function () {
            if($("#general_from_date").datetimepicker('date')!=null) {
                $('.date-from').datetimepicker('date', $("#general_from_date").datetimepicker('date'));
            }
            if($("#general_to_date").datetimepicker('date')!=null) {
                $('.date-to').datetimepicker('date',$("#general_to_date").datetimepicker('date'));
            }

        });

        window.Parsley.addValidator('qtyrequired',{
            validate:function (value,selector) {
                var response = false;

                var qtts = 0;
                $(selector).each(function(){
                    if($(this).val() != '') {
                        qtts+=$(this).val();
                    }
                });


                response=qtts>0;
                if(response){
                    $(selector).removeClass('parsley-error').addClass('parsley-success');
                }else{
                    $(selector).addClass('parsley-error').removeClass('parsley-success');
                    alertify.alert("{{ __('At leats one equipment must have a quantity greater that 0') }}");
                }

                return response;
            },
            messages:{
                en:' '
            }
        },1);



    </script>
@endpush