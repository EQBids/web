@extends('web.admin.layout')

@section('content')
    <h2>{{ __('Quotes Status Report') }}</h2>
    <form method="get" action="{{ route('admin.reports.quotesStatus') }}">
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
                
            </div>
        </div>
    </form>
    <div class="row mt-50 table-responsive" >
        <table class="table table-striped table-bordered" id="report_table">
            <thead>
            <tr>
                <th>{{ __('Quotes Received') }}</th>
                <th>{{ __('Quotes Replied') }}</th>
                <th>{{ __('Quotes Active') }}</th>
                <th>{{ __('Quotes Cancel') }}</th>
                <th>{{ __('Quotes Closed') }}</th>
            </tr>
            </thead>
            <tbody>
          
                
                <td>{{ $quotesReceived[0]->result }}</td>
                <td>{{ $quotesReplied[0]->result }}</td>
                <td>{{ $quotesActive[0]->result }}</td>
                <td>{{ $quotesCancel[0]->result }}</td>
                <td>{{ $quotesClosed[0]->result }}</td>
                
            </tbody>
        </table>
    </div>
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