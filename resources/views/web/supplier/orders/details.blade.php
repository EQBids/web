<h3> {{ __('Order #').$order->id }}</h3>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">{{ __('Creation date:') }}</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $order->created_at->format('Y/m/d H:i') }}" />
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">{{ __('Company name:') }}</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext text-dark" value="{{ $order->creator->contractor->company_name }}" />
    </div>
</div>
<h4>{{ __('Equipment\'s') }}</h4>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="table-responsive">
            <table id="equipment-table" class="table shopping-cart-page table-bordered">
                <thead>
                <tr>
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
                        <tr>
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
        $('#equipment-table').dataTable({
            columnDefs:[
                { targets: [2,3], orderable: false, width:'20%' },
                {targets: [4], orderable: false,width:'15%'}
            ],

        });
    </script>
@endpush