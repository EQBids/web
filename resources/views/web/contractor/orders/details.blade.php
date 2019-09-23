
@include('web.contractor.orders.order_details')

<h4 class="mt-50">{{ __('Suppliers') }}</h4>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="table-responsive">
            <table id="suppliers-table" class="table table-bordered table-striped">
                <thead>
                    <th class="w-40">{{ __('Name') }}</th>
                    <th class="w-40">{{ __('City') }}</th>
                    <th class="w-20">{{ __('Has bid') }}
                        @if($order->can_assign_bids)
                            <a class="btn btn-sm btn-info pull-right" href="{{ route('contractor.orders.bids',[$order->id]) }}">
                                <i class="fa fa-gavel fa-bold "></i>Accept bids
                            </a>
                        @endif
                    </th>
                    <th>{{ __('Bid Total') }}</th>
                </thead>
                <tbody>
                    @foreach($order->suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->city?$supplier->city->name:'' }}</td>
                            <td class="{{ $supplier->pivot->bid?'table-success':'table-danger' }}">{{ $supplier->pivot->bid?__('YES'):__('NO') }}</td>
                            <td>{{ isset( $supplier->pivot->bid) ? '$' . $supplier->pivot->bid->amount : ''}}</td>
                         
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('footer_scripts')
    <script type="application/javascript">
        $('#suppliers-table').DataTable({
            "columnDefs": [
                { "width": "40%", "targets": 0 },
                { "width": "30%", "targets": 1 },
                { "width": "30%", "targets": 2 }
            ]
        });
    </script>
@endpush