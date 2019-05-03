@extends('web.supplier.layout')

@section('content')
    <form method="post" action="{{ route('supplier.bids.approve',[$bid->id]) }}">
        {{ csrf_field() }}
        {{ method_field('post') }}
    <div class="alert alert-warning">
        <p>{{'Are you sure you want to REACTIVATE this bid?'}}</p>
        <div class="row">
            <div class="col-sm-12">
                <input type="submit" class="btn btn-success btn-sm float-right" value="{{ __('ACTIVATE') }}">
                <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-warning btn-sm float-right" >{{ __('go back') }}</a>

            </div>
        </div>
    </div>
    </form>

    @include('web.supplier.bids.details')
@endsection
