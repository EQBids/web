@extends('web.contractor.layout')

@section('content')
    <form method="post" action="{{ route('contractor.orders.approve',[$order->id]) }}">
        {{ csrf_field() }}
    <div class="alert alert-warning">
        <p>{{'Are you sure you want to approve this order?'}}</p>
        <div class="row">
            <div class="col-sm-12">
                <input type="submit" class="btn btn-success btn-sm float-right" value="{{ __('Approve') }}">
                <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-danger btn-sm float-right" >{{ __('Cancel') }}</a>

            </div>
        </div>
    </div>
    </form>

    @include('web.contractor.orders.details')
@endsection
