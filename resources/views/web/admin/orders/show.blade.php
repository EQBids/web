@extends('web.admin.layout')

@section('content')
    <div class="row mb-20">
        <div class="col-lg-12">
            <a href="{{ url()->previous() }}" class="btn btn-primary">{{__("Back")}}</a>
        </div>
    </div>

    @include('web.admin.orders.details')


@endsection