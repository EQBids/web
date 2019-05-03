@extends('web.contractor.layout')

@section('content')
    <div class="col-lg-12">
        <h1>{{__("Offices")}}</h1>
        <div class="row" style="margin-bottom: 30px">
            <div class="col-lg-12">
                <a class="btn btn-primary pull-right" href="{{ route('contractor.offices.create') }}">{{ __('Create') }}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table id="dttable" class="table table-bordered table-striped dataTable">
                    <thead>
                    <tr>
                        <th>{{ __('Location') }}</th>
                        <th>{{ __('Address') }}</th>

                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($offices as $office)
                        <tr>
                            <td>{{ $office->company_name }}</td>
                            <td>{{ $office->address }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{ route('contractor.offices.workers',[$office->id]) }}">{{__('Workers')}}</a>
                                <a class="btn btn-primary btn-sm" href="{{ route('contractor.offices.edit',[$office->id]) }}">{{__('Edit')}}</a>
                                <a class="btn btn-danger btn-sm" href="{{ route('contractor.offices.delete',[$office->id]) }}">{{__('Delete')}}</a>
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