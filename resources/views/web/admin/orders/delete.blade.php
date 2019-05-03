@extends('web.admin.layout')

@section('content')

    <div class="col-lg-12">
        <h1>{{ __('Cancel Order') }}</h1>
        <form action="{{ route('admin.orders.destroy',[$order->id]) }}" method="post" data-parsley-validate>
            <input type="hidden" name="_method" value="DELETE" />
            {{ csrf_field() }}
            <div class="alert alert-danger">
                {{__('Do you really wan\'t to cancel this order? you can\'t undo this.')}}
            </div>
            <div class="form-group pull-right">
                <a href="{{ url()->previous() }}" class="btn btn-primary">{{__("Back")}}</a>
                <input type="submit" class="btn btn-danger" value="{{ __('cancel order')}}" />
            </div>
        </form>
    </div>


    @include('web.admin.orders.details')


@endsection