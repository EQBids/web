@extends('web.admin.layout')

@section('content')
    <h2>{{ __('Quotes Report') }}</h2>
    <form method="get" action="{{ route('admin.reports.quotes') }}">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>{{ __('Supplier') }}:</label>
                    <select class="form-control" name="supplier_id" id="supplier">
                        <option></option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request()->get('supplier_id')==$supplier->id?'selected':'' }}>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="offset-lg-8 col-lg-4">
                <button type="submit" class="btn btn-primary btn-lg mt-30">{{ __('Filter') }}</button>
                <button type="submit" class="btn btn-primary btn-lg mt-30" name="export" value="pdf">{{ __('Export') }}</button>
            </div>
        </div>
    </form>
    @if(isset($items)  )
    <div class="row mt-50 table-responsive" >
        <table class="table table-striped table-bordered" id="report_table">
            <thead>
            <tr>
                <th>{{ __('Bid') }}</th>
                <th>{{ __('Contractor') }}</th>
                <th>{{ __('Equipment') }}</th>
                <th>{{ __('Dollar Value') }}</th>
                <th>{{ __('Quantity') }}</th>
                <th>{{ __('Total') }}</th>
                <th>{{ __('Order Date at') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Delivery Data') }}</th>
                <th>{{ __('Return Data') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->bid_id }}</td>
                    <td>{{ $item->contractor }}</td>
                    <td>{{ $item->equipment }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->total_value }}</td>
                    <td>{{ $item->orderdate }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->deliv_date }}</td>
                    <td>{{ $item->return_date }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endsection

@push('footer_scripts')

    <script>
        $(function () {
            $('#report_table').dataTable({
                @if(isset($sort))
                "order": {!! json_encode($sort) !!}
                @endif
            });
        });
    </script>
@endpush