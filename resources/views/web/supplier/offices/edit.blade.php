@extends('web.supplier.layout')

@section('content')
    <div class="col-lg-12">
        <a class="btn btn-danger" href="{{ route('supplier.offices.index') }}">{{__('Back')}}</a>
    </div>
    <div class="col-lg-12">

        <h1>{{__("Edit an office")}}</h1>

        {!! Form::open(
            [
                'method'=>'PUT','route'=>['supplier.offices.update',$office->id],
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
                    {!! Form::text('location',$office->name,[
                        'class' =>  'form-control',
                        'data-parsley-required'=>true
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="company_name">{{__("Address")}}</label>
                    {!! Form::text('address',$office->address,[
                        'class' =>  'form-control',
                        'data-parsley-required'=>true
                    ]) !!}
                </div>
            </div>
        </div>

        @if(isset($office->details['image']))
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="">Current image</label>
                        <img src="{{asset('storage/'.$office->details['image'])}}" alt="">
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <label for="image">{{__("Replace image")}}</label>
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
                    {!! Form::textarea('notes',isset($office->details['notes']) ? $office->details['notes'] : '',[
                         'class' =>  'form-control',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary">{{__("Update")}}</button>
                <a class="btn btn-danger" href="{{ route('supplier.offices.index') }}">{{__('Back')}}</a>
            </div>
        </div>


        {!! Form::close() !!}
    </div>
@endsection


@push('footer_scripts')

    @include('web.partials.geo')
@endpush
