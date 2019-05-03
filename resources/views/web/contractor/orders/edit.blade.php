@extends('web.contractor.layout')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('contractor.orders.edit.begin',[$order->id]) }}">
        {{ csrf_field() }}
        <div class="alert alert-success">
            <p>{{'Are you sure you want to edit this order?'}}</p>
            <div class="row">
                <div class="col-sm-12">
                    <input type="submit" class="btn btn-success btn-sm float-right" value="{{ __('Edit') }}">
                    <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-danger btn-sm float-right" >{{ __('Cancel') }}</a>

                </div>
            </div>
        </div>
    </form>

    @include('web.contractor.orders.details')
@endsection
