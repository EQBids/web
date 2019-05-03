@extends('web.admin.layout')

@section('content')
    <div class="col-lg-12">
        <H1>ADMIN DASHBOARD!</H1>
        <div class="card">
            <div class="card-body">
                <h5>{{ __('Contractors') }}</h5>
                <div>
                    {!! $contractors_chart->container() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script src="{{ asset('js/Chart.min.js') }}" charset="utf-8"></script>
    {!! $contractors_chart->script(); !!}
@endpush