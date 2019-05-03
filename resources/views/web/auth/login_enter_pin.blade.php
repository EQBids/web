@extends('web.public')

@section('content')

    <div class="col-lg-4 offset-lg-4 col-sm-12">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif

        <h3 class="text-center">{{ __('Continue the login process...') }}</h3>

        @if(Session::has('pin'))
            <div class="alert alert-success">
                <p>A pin code was sent to {{ old('email') }}, use it to complete the login</p>
            </div>
        @endif
        {!! Form::open(['method'=>'POST','route'=>'loginWithPin']) !!}

        <div class="form-group">
            <label for="" class="label">Email</label>
            <input type="text" name="email" placeholder="Enter your email" value="{{ old('email') }}" class="form-control">

        </div>
        <div class="form-group">
            <label for="" class="label">Pin</label>
            <input type="text" name="pin" placeholder="Enter your 6-digit pin" class="form-control">
            <p class="text-muted">{{ __('Enter the pin code we sent to your email') }}</p>
        </div>

        <div class="form-group">
            <button class="btn btn-success">LOGIN</button>
        </div>
        {!! Form::close() !!}
    </div>




@endsection