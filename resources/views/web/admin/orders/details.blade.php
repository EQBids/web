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
                @foreach($order->items as $item)
                    @if($item->equipment)
                        <tr data-equipment-id="{{$item->id}}">
                            <td></td>
                            <td class="image">
                                <a class="media-link" href="{{ route('admin.equipment.listing.show',[$item->equipment->id]) }}">
                                    <img class="img-fluid" src="{{ asset($item->equipment->image_path) }}" alt="">
                                </a>
                            </td>
                            <td>
                                <a  href="{{ route('admin.equipment.listing.show',[$item->equipment->id]) }}"> {{ $item->equipment->name }} </a>
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
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<h4 class="mt-50">{{ __('Suppliers') }}</h4>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="table-responsive">
            <table id="suppliers-table" class="table table-bordered table-striped">
                <thead>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('City') }}</th>
                    <th>{{ __('Has bid') }}</th>
                </thead>
                <tbody>
                    @foreach($order->suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->city?$supplier->city->name:'' }}</td>
                            <td class="{{ $supplier->pivot->bid?'table-success':'table-danger' }}">{{ $supplier->pivot->bid?__('YES'):__('NO') }}</td>
                        </tr>
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