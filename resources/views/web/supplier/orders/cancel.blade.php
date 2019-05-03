@extends('web.supplier.layout')

@section('content')
    <form method="post" action="{{ route('supplier.orders.destroy',[$order->id]) }}">
        {{ csrf_field() }}
        {{ method_field('delete') }}
    <div class="alert alert-warning">
        <p>{{'Are you sure you want to REJECT this order?'}}</p>
        <div class="row">
            <div class="col-sm-12">
                <input type="submit" class="btn btn-success btn-sm float-right" value="{{ __('REJECT the order') }}">
                <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-danger btn-sm float-right" >{{ __('go back') }}</a>

            </div>
        </div>
    </div>
    </form>

    @include('web.contractor.orders.details')
@endsection
