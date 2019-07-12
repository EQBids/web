@extends('web.admin.layout')

@section('content')
    <h2>{{ __('Whom quotes are coming from') }}</h2>
    <form method="get" action="{{ route('admin.reports.whoQuoted') }}">
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
    @if(isset($items)  )
    <div class="row mt-50 table-responsive" >
        <table class="table table-striped table-bordered" id="report_table">
            <thead>
            <tr>
                <th>{{ __('Contractor') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Total Quotes') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endsection

@push('footer_scripts')

    <script src="{{ asset('js/plugins/bootstrap-datetimepicker/moment-datepicker.js') }}" type="application/javascript"></script>
    <script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}" type="application/javascript"></script>

    <script type="application/javascript">
        $("#from").datetimepicker({
            'format':'YYYY-MM-DD',
        });
        $("#to").datetimepicker({
            'format':'YYYY-MM-DD',
            useCurrent: false
        });
    </script>
@endpush

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