@extends('web.admin.layout')

@section('content')

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            {!! Form::open(
                [
                    'method'=>'PUT','route'=>['admin.settings.update',$setting->id],
                    'data-parsley-validate'=>true
            ]) !!}

            <h1>{{__("Edit Setting")}}</h1>

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
                        {!! Form::text('name',$setting->name,[
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
                        <label for="image">{{__("Value")}}</label>
                        {!! Form::text('value',$setting->value,[
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
                        {!! Form::textarea('description',$setting->description,[
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
                    <button type="submit" class="btn btn-primary">{{__("Save")}}</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
