@extends('web.admin.layout')

@section('content')
    <div class="col-lg-12">
        <a class="btn btn-danger" href="{{ route('admin.sites.index') }}">{{__('Back')}}</a>
    </div>
    <div class="col-lg-12">
        <h1>{{ __('Create job site') }}</h1>
        <form method="post" action="{{ route('admin.sites.store') }}" data-parsley-validate>
            {{ method_field('post') }}

            @include('web.contractor.job_sites.form')

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="{{__('Create Site')}}" />
                <a href="{{ route('admin.sites.index') }}" class="btn btn-warning">{{ __('Back') }}</a>
            </div>
        </form>
    </div>
@endsection

@push('before_footer_scripts')
    <script type="text/javascript">
        var highlight_sidebar = "{{ route('admin.sites.index') }}";
    </script>
@endpush