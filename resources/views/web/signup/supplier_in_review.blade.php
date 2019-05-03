@extends('web.public')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success mt-100">
                    <p>{{ __('Your account is under review, you will receive a notification email once approved') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection