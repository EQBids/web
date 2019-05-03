@extends('web.admin.layout')

@section('content')
    <div class="col-lg-12">
        <a class="btn btn-warning" href="{{ route('admin.sites.index') }}">{{ __('Back') }}</a>
    </div>
    <div class="col-lg-12" style="margin-top: 30px">
        @include('web.contractor.job_sites.details')
    </div>
@endsection