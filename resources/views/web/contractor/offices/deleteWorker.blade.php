@extends('web.contractor.layout')

@section('content')
    <div class="col-lg-12">
        <a href="{{ route('contractor.offices.index') }}" class="btn btn-lg btn-warning">{{ __('Back') }}</a>
    </div>
    <div class="col-lg-8 offset-lg-2">
        <h1>{{__("Delete office")}}</h1>
        {!! Form::open(['method'=>'delete','route'=>['contractor.offices.destroyWorker',$id]]) !!}
            <div class="alert alert-danger">
                {{__('Do you really wan\'t to delete this worker?')}}
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-danger" value="{{__('Delete')}}" />
                <a href="{{ route('contractor.sites.index') }}" class="btn btn-warning">{{ __('Back') }}</a>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

