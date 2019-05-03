@extends('web.contractor.layout')

@section('content')
    <div class="col-lg-12">
        <h1>{{__("Suppliers")}}</h1>
        <div class="row">
            <div class="col-lg-12">
                <table id="dttable" class="table table-bordered table-striped dataTable">
                    <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Address') }}</th>

                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->address }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{ route('contractor.suppliers.view',[$supplier->id]) }}">{{__('More')}}</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('footer_scripts')
    <script>
        $(document).ready(function () {
            $('#dttable').dataTable();
        });
    </script>
@endpush