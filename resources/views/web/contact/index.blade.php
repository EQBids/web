@extends('web.public')

@section('content')

    <div class="col-lg-4 offset-lg-4 col-sm-12">

        <h3 class="text-center">{{ __('Contact Us') }}</h3>

        <h6 class="text-center">
            <p>{{__('Anything on your mind? We love questions and comments! Please fill out the form below. Mandatory fields are indicated with an asterisk.')}}</p>
        </h6>
        @if ($errors->any())
            <div class="alert alert-danger">
                <p> Snap! There was an error. Please correct the errors as indicated below. If all else fails, please contact us directly at support@eqbids.com</p>
            </div>
        @endif
        <?php if(Session::has('success')): ?>
            <div class="alert alert-success">
                <p>Thank you! Your request has been sent and we will respond as soon as possible!</p>
            </div>
        <?php endif; ?>
        <?php if(Session::has('error')): ?>
            <div class="alert alert-danger">
                <p> Snap! There was an error. Please correct the errors as indicated below. If all else fails, please contact us directly at support@eqbids.com</p>
            </div>
        <?php endif; ?>
        <form action="{{ route('contactMessage') }}" method="post" data-parsley-validate>
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="" class="label">Name <span class="required-symbol">*</span></label>
                <input type="text" name="name" placeholder="Enter your name" 
                value="{{ old('name') }}" class="form-control"
                data-parsley-required data-parsley-minlength="8" data-parsley-maxlength="100">
            </div>
            <div class="form-group">
                <label for="" class="label">Email <span class="required-symbol">*</span></label>
                <input type="text" name="email" placeholder="Enter your email" 
                value="{{ old('email') }}" class="form-control"
                data-parsley-required data-parsley-type="email" data-parsley-maxlength="100">
            </div>
            <div class="form-group">
                <label for="" class="label">Company</label>
                <input type="text" name="company" placeholder="Enter the name of the company" 
                class="form-control" data-parsley-maxlength="100" value="{{ old('company') }}">
            </div>
            <div class="form-group">
                <label for="" class="label">Telephone, Day</label>
                <input type="text" name="telephone-day" placeholder="Enter your phone" 
                class="form-control phone-mask" data-parsley-maxlength="20" value="{{ old('telephone-day') }}">
            </div>
            <div class="form-group">
                <label for="" class="label">Telephone, Night</label>
                <input type="text" name="telephone-night" placeholder="Enter your phone" 
                class="form-control phone-mask" data-parsley-maxlength="20" value="{{ old('telephone-night') }}">
            </div>
            <div class="form-group">
                <label for="" class="label">Your Message <span class="required-symbol">*</span></label>
                <textarea rows="4" cols="50" name="message"
                placeholder="Enter your message" class="form-control"
                data-parsley-required data-parsley-minlength="50" data-parsley-maxlength="250">{{ old('message') }}</textarea>
            </div>
            {!! Captcha::display() !!}
            {!! $errors->first('g-recaptcha-response', '<p class="alert alert-danger">:message</p>') !!}
            <div class="form-group">
                <button class="btn btn-success">SEND</button>
            </div>
        </form>
    </div>




@endsection