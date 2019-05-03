@extends('web.admin.layout')

@section('content')
    <div class="col-lg-12">
        <a class="btn btn-warning" href="{{ route('admin.users.index') }}">{{ __('Back') }}</a>
    </div>

    <div class="col-lg-8 offset-lg-2">
        <h1>{{__("Applicant review")}}</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {!! Form::open(['method'=>'POST','url'=>route('admin.applicants.accept',$user->id),'id'=>'applicant-form','data-parsley-validate']) !!}
            <div class="form-group">
                <label>{{__("Email")}}</label>
                {!! Form::email('email',$user->email,[
                    'class' =>  'form-control',
                    'data-parsley-required',
                    'data-parsley-type' =>  'email'
                ]) !!}
            </div>

            <div class="form-group">
                <label>{{__('First name')}}:</label>
                {!! Form::text('first_name',$user->first_name,[
                    'class' =>  'form-control',
                    'data-parsley-required',
                    'data-parsley-maxlength'    =>  100
                ]) !!}
            </div>

            <div class="form-group">
                <label>{{__('Last name')}}:</label>
                {!! Form::text('last_name',$user->last_name,[
                    'class' =>  'form-control',
                    'data-parsley-required',
                    'data-parsley-maxlength'    =>  100
                ]) !!}
            </div>

            <div class="form-group">
                <label>{{ __('Company name') }}:</label>
                {!! Form::text('company_name',isset($user->settings['company_name']) ? $user->settings['company_name'] : '',[
                    'class' =>  'form-control',
                    'data-parsley-maxlength'    =>  150
                ]) !!}
            </div>

            <div class="form-group">
                <label>{{ __('Position/title') }}:</label>
                {!! Form::text('company_position',isset($user->settings['company_position']) ? $user->settings['company_position'] : '',[
                    'class' =>  'form-control',
                    'data-parsley-maxlength'    =>  100
                ]) !!}
            </div>

            <div class="form-group">
                <label>{{ __('Address') }}:</label>
                {!! Form::textarea('address',isset($user->settings['address']) ? $user->settings['address'] : '',[
                    'class' =>  'form-control',
                    'data-parsley-required',
                    'data-parsley-maxlength'    =>  200,
                    'rows'  =>  2
                ]) !!}
            </div>

            <div class="form-group">
                <label>{{ __('Country') }}:</label>
                <select class="form-control" name="country" id="country" data-parsley-required></select>
            </div>

            <div class="form-group">
                <label>{{ __('State') }}:</label>
                <select class="form-control" name="state" id="state" data-parsley-required></select>
            </div>

            <div class="form-group">
                <label>{{ __('City') }}:</label>
                <select class="form-control" name="city" id="city" data-parsley-required></select>
            </div>

            <div class="form-group">
                <label>{{ __('Zip/Postal Code') }}</label>
                {!! Form::text('postal_code', isset($user->settings['postal_code']) ? $user->settings['postal_code'] : '',[
                    'class' =>  'form-control zip',
                ]) !!}
            </div>

            <div class="form-group">
                <label>{{ __('Main Phone') }}:</label>
                {!! Form::text('phone', $user->phone,[
                    'class' =>  'form-control phone-mask',
                    'data-parsley-required',
                ]) !!}
            </div>

            <div class="form-group">
                <label>{{ __('Secondary Phone') }}:</label>
                {!! Form::text('secondary_phone', isset($user->settings['secondary_phone']) ? $user->settings['secondary_phone'] : '',[
                    'class' =>  'form-control phone-mask',
                ]) !!}
            </div>


        {!! Form::close() !!}

        <form action="{{route('admin.applicants.reject',$user->id)}}" method="post" id="reject-form">{{ csrf_field() }}</form>
        <form action="{{route('admin.applicants.destroy',$user->id)}}" method="post" id="destroy-form">{{ csrf_field() }}</form>

        <div class="row">
            <div class="col-lg-12">
                <a href="{{route('admin.applicants.index')}}" class="btn btn-warning">{{__("Cancel")}}</a>
                <button type="submit" form="destroy-form" class="btn btn-dark">{{__("Reject and delete")}}</button>
                <button type="submit" form="reject-form" class="btn btn-danger">{{__("Temporary Reject")}}</button>
                <button type="submit" form="applicant-form" class="btn btn-primary">{{__("Accept")}}</button>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    @include('web.partials.geo')
    @include('web.partials.zip')

@endpush