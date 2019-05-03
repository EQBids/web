@extends('web.admin.layout')

@section('content')

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            {!! Form::open(
                [
                    'method'=>'POST','route'=>'admin.settings.store',
                    'data-parsley-validate'=>true
            ]) !!}

            <h1>{{__("Create Setting")}}</h1>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="brand">{{__("Name")}}</label>
                        {!! Form::text('name',null,[
                            'class'                 =>  'form-control',
                            'id'                    =>  'name',
                            'data-parsley-required' =>  true,
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="value">{{__("Value")}}</label>
                        {!! Form::text('value',null,[
                            'class'                 =>  'form-control',
                            'id'                    =>  'value',
                            'data-parsley-required' =>  true,
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="description">{{__("Description")}}</label>
                        {!! Form::textarea('description',null,[
                            'class'     =>  'form-control',
                            'id'        =>  'value',
                            'rows'      =>  2,
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <a href="{{route('admin.settings.index')}}" class="btn btn-warning ">{{__("Back")}}</a>
                    <button type="submit" class="btn btn-primary">{{__("Create")}}</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
