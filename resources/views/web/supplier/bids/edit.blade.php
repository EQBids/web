<?php
/**
 * Created by PhpStorm.
 * User: smith
 */
?>

@extends('web.supplier.layout')

@section('content')

    <div class="row mb-20">
        <div class="col-lg-12">
            <a href="{{ url()->previous() }}" class="btn btn-primary">{{__("Back")}}</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{ route('supplier.bids.update',[$bid->id]) }}" id="form">
                @include('web.partials.show_errors')
                <h2>{{ __('Editing Bid #') }}{{ $bid->id }}</h2>

                <div class="card mt-20">
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
                                <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $bid->order->site->phone }}" />
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
                                    <th >
                                        {{ __('Unit Price') }}
                                    </th>
                                </tr>

                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Notes') }}</h4>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" name="notes" rows="10">{{ old('notes',$bid->details['notes']) }}</textarea>
                    </div>

                </div>
                <div class="row mt-50">

                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <div class="col-lg-6">
                        <a class="btn btn-warning pull-right" href="{{ url()->previous() }}" ><i class="fa fa-angle-double-left" ></i> {{ __('Back') }}</a>
                    </div>
                    <div class="col-lg-4">
                        <input type="submit" class="btn btn-success" value="{{ __('Update Bid') }}" />
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

        var equipments = {!! \App\Http\Resources\Buyer\orderItemResource::collection($bid->items)->toJson() !!}
        var equipment_route = "{{ route('contractor.equipment.show',[-1]) }}";

        function format ( data ) {
            var out = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; width: 100%"  id="child_'+data.oid+'">'+
                '<tr>'+
                '<td>{{ __('Delivery date') }}:</td>'+
                '<td><input type="text" style="position:relative" class="form-control date-from"\n' +
                '                                           data-index="'+data.oid+'"\n' +
                '                                           value="'+data.deliv_date+'"' +
                '                                           name="equipments['+data.oid+'][from]"\n' +
                '                                    /></td>' +
                '</tr>'+
                '<tr>'+
                '<td>{{ __('Return date') }}:</td>'+
                '<td><input type="text" style="position:relative" class="form-control date-to"\n' +
                '                                           data-index="'+data.oid+'"\n' +
                '                                           name="equipments['+data.oid+'][to]"\n' +
                '                                           value="'+data.return_date+'"' +
                '                                    /></td>' +
                '</tr>'+
                '<tr>'+
                '<td>{{ __('Delivery fee') }}:</td>'+
                '<td> <div class="input-group mb-3">' +
                '<div class="input-group-prepend" style="padding: 0px">' +
                '<span class="input-group-text">$</span>' +
                '</div>' +
                '<input type="text" class="form-control money" name="equipments['+data.oid+'][delivery]"' +
                '                                           value="'+data.delivery_fee+'"' +
                ' />' +
                '</div>' +
                '</td>'+
                '</tr>'+
                '<tr>'+
                '<td>{{ __('Pick up fee') }}:</td>'+
                '<td> <div class="input-group mb-3">' +
                '<div class="input-group-prepend" style="padding: 0px">' +
                '<span class="input-group-text">$</span>' +
                '</div>' +
                '<input type="text" class="form-control money" name="equipments['+data.oid+'][pick]" ' +
                '                                           value="'+data.pickup_fee+'" ' +
                ' />' +
                '</div>' +
                '</td>'+
                '</tr>'+
                '<tr>'+
                '<td>{{ __('Insurance fee') }}:</td>'+
                '<td>' +
                '  <input type="hidden" value="0" name="equipments['+data.oid+'][insurance]" />\n' +
                '  <input type="checkbox" class="checkbox-switch" value="1" name="equipments['+data.oid+'][insurance]"' + ((data.insurance>0)?'checked':'') +
                ' />\n' +
                '</td>' +
                '</tr>'+
                '<tr>'+
                '<td>{{ __('Notes') }}:</td>'+
                '<td><textarea class="form-control"\n' +
                '                                           name="equipments['+data.oid+'][notes]"\n' +
                '                                    >'+(data.notes!=null?data.notes:'')+'</textarea></td>' +
                '</tr>';
                if(data.allow_attachments){
                    out=out+'<tr>'+
                        '<td>{{ __('Attachments') }}:</td>'+
                        '<td>' +
                        '<div id="drop_'+data.oid+'" class="dropzone"> ' +
                        '</div>'+
                        '</td>' +
                        '</tr>';
                }

                out=out+'</table>';
                return out;
        }

        $(document).ready(function() {
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
                    { "data": "qty" },
                    { "data": function (data) {
                            return '<div class="input-group mb-3">\n' +
                                '  <div class="input-group-prepend" style="padding: 0px">\n' +
                                '    <span class="input-group-text">$</span>\n' +
                                '  </div>\n' +
                                '  <input type="text" required class="form-control money" value="'+data.price+'" name="equipments['+data.oid+'][price]" />\n' +
                                '  <input type="hidden" required  value="'+data.oid+'" name="equipments['+data.oid+'][id]" />\n' +
                                '</div>'
                        }  }
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
                else if(row.data().exists){
                    row.child.show();
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

                    var id=$('table',row.child()).attr('id');
                    var el = document.querySelector('#'+id+' .checkbox-switch');
                    var mySwitch = new window.Switch(el,{});
                    row.data().exists=true;
                    var idNum = id.split("_");
                    var zdrop= $("div#drop_"+idNum[1]).dropzone(
                        {
                            url: "{{ route('supplier.bids.attachments.store',$bid->order_id) }}",
                            uploadMultiple: true,
                            parallelUploads: 2,
                            maxFilesize: 16,
                            addRemoveLinks: true,
                            dictRemoveFile: "{{ __('Remove file')}}",
                            dictFileTooBig: "{{ __('Image is larger than 16MB')}}",
                            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            timeout: 10000,
                            init: function () {
                                this.on("removedfile", function (file) {
                                    $.ajax({
                                        type: 'DELETE',
                                        url: "{{ route('supplier.bids.attachments.destroy',$bid->order_id) }}",
                                        data: {id: file.name, _token: $('[name="_token"]').val()},
                                        dataType: 'json',
                                        success: function (data) {

                                        }
                                    });
                                });
                            },
                            success: function (file, done) {
                                for(var i=0;i<done.names.length;i++) {
                                    $('#form').append('<input type="hidden" name="equipments[' + idNum[1] + '][attachments][]" value="' +done.names[i] + '" />');
                                }
                            }
                        }
                    );
                    var eq_data=row.data();
                    for(var i=0;i<eq_data.attachments.length;i++){
                        var eq_file = eq_data.attachments[i];
                        var mockFile = { name: eq_file.name, size: 12345 };
                        Window.dropzone.forElement("div#drop_"+idNum[1]).emit("addedfile", mockFile);
                        Window.dropzone.forElement("div#drop_"+idNum[1]).emit("thumbnail", mockFile, eq_file.thumbnail);
                        Window.dropzone.forElement("div#drop_"+idNum[1]).emit("complete", mockFile);
                        Window.dropzone.forElement("div#drop_"+idNum[1]).emit("success", null,{names:[eq_data.server_name]});
                    }


                }
            } );

            $('.money').mask('#,##0.00', {reverse: true});
        } );
    </script>
@endpush