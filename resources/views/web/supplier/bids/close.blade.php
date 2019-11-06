@extends('web.supplier.layout')

@section('content')

    <div class="row mb-20">
        <div class="col-lg-12">
            <a href="{{ url()->previous() }}" class="btn btn-primary">{{__("Back")}}</a>
        </div>
    </div>

    <div class="row mt-20">
        <div class="col-lg-12">
            <div class="alert alert-warning">
                <p>
                    {{ __('The order has been closed and you must confirm the bid. only the contractor can reopen the bids') }}

                    <div class="row">
                        <div class="col-lg-12">
                            @if($bid->is_accepted && $bid->status==\App\Models\Supplier\Bid::STATUS_ACTIVE)

                                <form action="{{ route('supplier.bids.close',[$bid->id]) }}" data-parsley-validate enctype="multipart/form-data" method="post">
                                    {{ csrf_field() }}

                                    @if($bid->is_accepted && $bid->status==\App\Models\Supplier\Bid::STATUS_ACTIVE)
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="image">{{(__("Attachment"))}}</label>
                                            {!! Form::file('image',['class'=>'form-control']) !!}
                                        </div>
                                    </div>
                                    @endif
                                    <br>
                                    <input type="submit"  class="btn btn-info pull-right" value="{{ __('Confirm and close the bid') }}" />
                                </form>

                            @endif
                        </div>
                    </div>

                </p>
            </div>

        </div>
    </div>


    @include('web.supplier.bids.details')



@endsection
