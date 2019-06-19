@extends('web.admin.layout')

@section('content')
    <h1>{{ __('Orders') }}</h1>
    <table class="table table-striped table-bordered" id="orders-table">
        <thead>
            <th>{{ __('Id') }}</th>
            <th>{{ __('Created at') }}</th>
            <th>{{ __('Contractor') }}</th>
            <th>{{ __('Status') }}</th>
            <th>{{ __('Actions') }}</th>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ isset($order->site->contractor) ? $order->site->contractor->company_name : '' }}</td>
                    <td>{{ $order->getStatusName() }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show',[$order->id]) }}"><span class="fa fa-eye"></span></a>
                        @if($order->status!=\App\Models\Buyer\Order::STATUS_CANCELLED)
                            <a href="{{ route('admin.orders.delete',[$order->id]) }}"><span class="fa fa-ban text-danger"></span></a>
                        @endif
                    </td>
            @endforeach
        </tbody>

    </table>
@endsection

@push('footer_scripts')
    <script type="application/javascript">
        $('#orders-table').DataTable({
            order:[[1,'desc']]
        });
    </script>
@endpush