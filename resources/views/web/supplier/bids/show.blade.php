@extends('web.supplier.layout')

@section('content')

    <div class="row mb-20">
        <div class="col-lg-12">
            <a href="{{ url()->previous() }}" class="btn btn-primary">{{__("Back")}}</a>
        </div>
    </div>

    @include('web.supplier.bids.details')
    
    <div class="row mt-60">
        <div class="col-lg-4 offset-4">
            @if($bid->is_accepted && $bid->status==\App\Models\Supplier\Bid::STATUS_ACTIVE)
                
                <a href="{{ route('supplier.bids.close',[$bid->id]) }}" class="btn btn-info" >{{ __('Confirm and close the bid') }}</a>
            @endif
        </div>
    </div>

@endsection
