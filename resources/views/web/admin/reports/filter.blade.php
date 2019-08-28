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
    <div class="col-lg-8">
        <div class="form-group">
            <label>{{ __('Industry') }}:</label>
            <select class="" name="industry_id" id="industry">
                <option value="">----</option>
                @foreach($industries as $industry)
                    <option value="{{ $industry->id }}" {{ request()->get('industry_id')==$industry->id?'selected':'' }}>{{ $industry->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-4">
        <button type="submit" class="btn btn-primary btn-lg mt-30">{{ __('Filter') }}</button>
        <button type="submit" class="btn btn-primary btn-lg mt-30" name="export" value="pdf">{{ __('Export') }}</button>
    </div>
</div>

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