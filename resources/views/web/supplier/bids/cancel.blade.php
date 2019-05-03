@extends('web.supplier.layout')

@section('content')
    <form method="post" action="{{ route('supplier.bids.destroy',[$bid->id]) }}">
        {{ csrf_field() }}
        {{ method_field('delete') }}
    <div class="alert alert-warning">
        <p>{{'Are you sure you want to CANCEL this bid?'}}</p>
        <div class="row">
            <div class="col-sm-12">
                <input type="submit" class="btn btn-danger btn-sm float-right" value="{{ __('CANCEL this bid') }}">
                <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-warning btn-sm float-right" >{{ __('go back') }}</a>

            </div>
        </div>
    </div>
    </form>

    @include('web.supplier.bids.details')
@endsection
