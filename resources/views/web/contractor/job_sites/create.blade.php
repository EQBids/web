@extends('web.contractor.layout')

@section('content')
    <div class="col-lg-12">
        <a class="btn btn-danger" href="{{ route('contractor.sites.index') }}">{{__('Back')}}</a>
    </div>
    <div class="col-lg-12">

        <h1>Create new Job site</h1>

        {!! Form::open(
            [
                'method'=>'POST',
                'route'=>'contractor.sites.store',
                'data-parsley-validate'=>true,
            ]
        ) !!}

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">

                <div class="col-lg-12">

                    <div class="form-group">
                        <label for="">Contractor<span class="required-symbol">*</span></label>
                        <select name="contractor" id="contrator" class="form-control" data-parsley-required>
                            <option value="">Choose</option>
                            @foreach($contractors as $contractor)
                                <option value="{{$contractor->id}}">{{$contractor->address}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="">Job site nickname</label>
                        {!! Form::text('nickname',null,[
                            'class' =>  'form-control'
                        ]) !!}
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="">Job site name<span class="required-symbol">*</span></label>
                        {!! Form::text('name',null,[
                            'class' =>  'form-control'
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="">Street address<span class="required-symbol">*</span></label>
                        {!! Form::text('address',null,[
                            'class' =>  'form-control',
                            'data-parsley-required'=>true,
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-3">

                    <div class="form-group">

                        <label for="">City<span class="required-symbol">*</span></label>
                        <select name="city" id="city" class="form-control" data-parsley-required>
                            <option value="">Choose</option>
                            @foreach($cities as $city)
                                <option value="{{$city->id}}">{{$city->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-3">

                    <div class="form-group">

                        <label for="">Metro area</label>
                        <select name="metro" id="metro" class="form-control">
                            <option value="">Choose</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">

                        <label for="">State / Province<span class="required-symbol">*</span></label>

                        <select name="state" id="state" class="form-control" data-parsley-required>
                            <option value="">Choose</option>
                            @foreach($states as $state)
                                <option value="{{$state->id}}">{{$state->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">

                        <label for="">Country<span class="required-symbol">*</span></label>
                        <select name="country" id="country" class="form-control" data-parsley-required>
                            <option value="">Choose</option>
                            @foreach($countries as $country)
                                <option value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="">Postal code</label>
                        {!! Form::text('zip',null,[
                            'class' =>  'form-control zip'
                        ]) !!}
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="">Phone<span class="required-symbol">*</span></label>
                        {!! Form::text('phone',null,[
                            'class' =>  'form-control',
                            'data-parsley-required'=>true,
                            'data-mask'=>'(000) 000-0000',
                        ]) !!}
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="">Site contact name</label>
                        {!! Form::text('contact',null,[
                            'class' =>  'form-control'
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="">Special Instructions</label>
                        {!! Form::textarea('special_instructions',null,[
                            'class' =>  'form-control',
                            'rows'  =>  5,
                            'id'    =>  'special_instructions'
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary pull-right">Create</button>
                    <a class="btn btn-danger pull-right" href="{{ route('contractor.sites.index') }}">{{__('Back')}}</a>
                </div>
            </div>

        {!! Form::close() !!}
    </div>
@endsection


@push('footer_scripts')
    @include('web.partials.geo')
    @include('web.partials.zip')
@endpush
