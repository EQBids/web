@extends('web.supplier.layout')

@section('content')
    <div class="row">

        <div class="col-lg-12">
            <a class="btn btn-warning" href="{{ route('supplier.offices.index') }}">{{__('Back')}}</a>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-8 offset-2">

            <h1>{{__("Create a new office")}}</h1>

            {!! Form::open(
                [
                    'method'=>'POST','route'=>'supplier.offices.store',
                    'data-parsley-validate'=>true,
                    'files'=>true,
                ])
            !!}

            <div class="row">
                <div class="col-lg-12">
                    @include('web.partials.show_errors')
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="location">{{__("Location name")}}</label>
                        {!! Form::text('location',null,[
                            'class' =>  'form-control',
                            'data-parsley-required'=>true
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="address">{{__("Address")}}</label>
                        {!! Form::text('address',null,[
                            'class' =>  'form-control',
                            'data-parsley-required'=>true
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <label for="image">{{(__("Image"))}}</label>
                    {!! Form::file('image',['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="country">{{__("Country")}}</label>
                        <select name="country" id="country" class="form-control" data-parsley-required>
                            <option value="">{{__("Choose")}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="state">{{__("Province/State")}}</label>
                        <select name="state" id="state" class="form-control" data-parsley-required>
                            <option value="">{{__("Choose")}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="city">{{__("City")}}</label>
                        <select name="city" id="city" class="form-control" data-parsley-required>
                            <option value="">{{__("Choose")}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="details">{{__("Notes")}}</label>
                        {!! Form::textarea('notes',null,[
                             'class' =>  'form-control',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary">{{__("Create")}}</button>
                    <a class="btn btn-warning" href="{{ route('supplier.offices.index') }}">{{__('Back')}}</a>
                </div>
            </div>


            {!! Form::close() !!}
        </div>
    </div>
@endsection


@push('footer_scripts')

    @include('web.partials.geo')
@endpush
