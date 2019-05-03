
@extends('web.supplier.layout')

@section('content')
    <div class="col-lg-12">
        <h1>{{__("Settings")}}</h1>
        <div class="row" style="margin-bottom: 30px">
            <div class="col-lg-12">
                <table id="dttable" class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Value') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($settings as $setting)
                            <tr>
                                <td>{{ $setting->label }}</td>
                                <td>{{ substr($setting->value,0,150) }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ route('supplier.settings.edit',[$setting->id]) }}">{{__('Edit')}}</a>
                                    <a class="btn btn-danger btn-sm" href="{{ route('supplier.settings.delete',[$setting->id]) }}">{{__('Delete')}}</a>
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