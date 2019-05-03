@extends('web.admin.layout')

@section('content')
    <h2>{{ __('Suppliers Report') }}</h2>

    <form method="get" action="{{ route('admin.reports.suppliers') }}">
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
                    <label>{{ __('From') }}:</label>
                    <input type="text" id="from" class="form-control date-from"
                           name="from"
                           value="{{ request()->get('from') }}"
                    />
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label>{{ __('To') }}:</label>
                    <input type="text" id="to" class="form-control date-from"
                           name="to"
                           value="{{ request()->get('to') }}"
                    />
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label>{{ __('Country') }}:</label>
                    <select class="form-control" name="country_id" id="country" data-parsley-required>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label>{{ __('State') }}:</label>
                    <select class="form-control" name="state_id" id="state">
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label>{{ __('City') }}:</label>
                    <select class="form-control" name="city_id" id="city">
                    </select>
                </div>
            </div>
        </div>

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
            <div class="col-lg-6">
                <div class="form-group">
                    <label>{{ __('Equipment') }}:</label>
                    <select class="form-control" name="equipment_id" id="equipment">
                        <option></option>
                        @foreach($equipments as $equipment)
                            <option value="{{ $equipment->id }}" {{ request()->get('equipment_id')==$equipment->id?'selected':'' }}>{{ $equipment->name }}</option>
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
    <div class="row mt-50">
        <table class="table table-striped table-bordered" id="report_table">
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Bid invitations') }}</th>
                <th>{{ __('Total Bids') }}</th>
                <th>{{ __('Leads') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->invitations }}</td>
                    <td>{{ $item->bids }}</td>
                    <th>{{ $item->leads }}</th>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('footer_scripts')

    <script src="{{ asset('js/plugins/bootstrap-datetimepicker/moment-datepicker.js') }}" type="application/javascript"></script>
    <script src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}" type="application/javascript"></script>


    @include('web.partials.geo')


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
                "order": [
                        [1,'desc'],
                        [0,'asc']
                    ]
            });

            $('#equipment').select2({ placeholder: 'Select an equipment',allowClear:true});
            $('#supplier').select2({ placeholder: 'Select an supplier',allowClear:true});
        });


    </script>
@endpush