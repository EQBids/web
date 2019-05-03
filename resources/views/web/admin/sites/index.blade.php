@extends('web.admin.layout')

@section('content')
    <div class="col-lg-12">
        <h1>Job sites</h1>
        <div class="row" style="margin-bottom: 30px">
            <div class="col-lg-12">
                <a class="btn btn-primary pull-right" href="{{ route('admin.sites.create') }}">{{ __('Create new job site') }}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table id="dttable" class="table table-bordered table-striped dataTable">
                    <thead>
                    <tr>
                        <th>{{ __('Contractor') }}</th>
                        <th>{{ __('Nickname') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('City') }}</th>
                        <th>{{ __('State/Province') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sites as $site)
                        <tr>
                            <td>{{ $site->contractor->company_name }}</td>
                            <td>{{ $site->nickname }}</td>
                            <td>{{ $site->name }}</td>
                            <td>{{ $site->city?$site->city->name:'' }}</td>
                            <td>{{ $site->state?$site->state->iso_code:'' }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{ route('admin.sites.show',[$site->id]) }}">{{__('View')}}</a>
                                <a class="btn btn-primary btn-sm" href="{{ route('admin.sites.edit',[$site->id]) }}">{{__('Edit')}}</a>
                                <a class="btn btn-danger btn-sm" href="{{ route('admin.sites.delete',[$site->id]) }}">{{__('Delete')}}</a>

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
    <script type="text/javascript">
        $('#dttable').dataTable();
    </script>
@endpush