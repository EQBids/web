@extends('web.public')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center">
                    <h6></h6>
                    <h2 class="title-effect">{{__("Signup as a supplier")}}</h2>
                </div>
            </div>

            <div class="col-lg-12" style="margin-top: 30px">

                {!! Form::open(['method'=>'POST','route'=>'signup','data-parsley-validate']) !!}

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
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="" class="label">@lang('signup.first_name_label') <span class="required-symbol">*</span></label>
                            <div class="control">
                                {!! Form::text('first_name',null,[
                                    'class'=>'form-control',
                                    'data-parsley-required'=>'true'
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="" class="label">@lang('signup.last_name_label') <span class="required-symbol">*</span></label>
                            <div class="control">
                                {!! Form::text('last_name',null,[
                                    'class'=>'form-control',
                                    'data-parsley-required'=>'true'
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="" class="label">@lang('signup.company_name_label') <span class="required-symbol">*</span></label>
                            <div class="control">
                                {!! Form::text('company_name',null,[
                                    'class'=>'form-control',
                                    'data-parsley-required'=>'true'
                                ]) !!}
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="" class="label">@lang('signup.street_address_label')</label>
                            <div class="control">
                                {!! Form::text('address',null,[
                                    'class'=>'form-control',
                                    'data-parsley-required'=>'true'
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="" class="label">@lang('signup.postal_code_label')</label>
                            <div class="control">
                                {!! Form::text('postal_code',null,[
                                    'class'=>'form-control zip'
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="" class="label" style="display: block">@lang('signup.country_label') <span class="required-symbol">*</span></label>
                            <select name="country" id="country" class="form-control" data-parsley-required>
                                <option value="">Choose</option>
                            </select>
                        </div></div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="" class="label" style="display: block">@lang('signup.state_label') <span class="required-symbol">*</span></label>
                            <select name="state" id="state" class="form-control" data-parsley-required>
                                <option value="">Choose</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="" class="label" style="display: block">@lang('signup.city_label') <span class="required-symbol">*</span></label>
                            <select name="city" id="city" class="form-control" data-parsley-required>
                                <option value="">Choose</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="" class="label">@lang('signup.main_phone_label') <span class="required-symbol">*</span></label>
                            <div class="control">
                                {!! Form::text('phone',null,[
                                    'class'=>'form-control phone-mask',
                                    'data-parsley-required',
                                    'data-mask'=>'(000) 0000-0000',
                                    'data-parsley-pattern'=>'\(\d{3}\) \d{3}-\d{4}'
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="" class="label">@lang('signup.email_label') <span class="required-symbol">*</span></label>
                            <div class="control">
                                {!! Form::email('email',null,[
                                    'class'=>'form-control',
                                    'data-parsley-required'
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-12">

                        <button class="btn btn-primary pull-right">@lang('signup.register_label')</button>
                        <a href="{{ route('show_login') }}" class="btn btn-warning pull-right">{{ __('Back') }}</a>

                    </div>
                </div>

                <input type="hidden" name="role" value="supplier">
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

@push('footer_scripts')
    @include('web.partials.zip')
    @include('web.partials.geo')
@endpush
