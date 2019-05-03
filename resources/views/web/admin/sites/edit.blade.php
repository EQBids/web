@extends('web.admin.layout')

@section('content')
    <div class="col-lg-12">
        <a class="btn btn-danger" href="{{ route('admin.sites.index') }}">{{__('Back')}}</a>
    </div>
    <div class="col-lg-12">
        <h1>{{ __('Edit job site') }}</h1>
        <form method="post" action="{{ route('admin.sites.update',[$site->id]) }}" data-parsley-validate>
            {{ method_field('put') }}

            @include('web.contractor.job_sites.form')

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="{{__('Update Site')}}" />
                <a href="{{ route('admin.sites.index') }}" class="btn btn-warning">{{ __('Back') }}</a>
            </div>
        </form>
    </div>
@endsection