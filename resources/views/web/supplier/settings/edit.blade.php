@extends('web.supplier.layout')

@section('content')
    <div class="row">

        <div class="col-lg-12">
            <a class="btn btn-warning" href="{{ route('supplier.settings.index') }}">{{__('Back')}}</a>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-8 offset-2">

            <h1>{{__("Update setting")}}</h1>

            {!! Form::open(
                [
                    'method'=>'PUT',
                    'route'=>['supplier.settings.update',$setting->id],
                    'data-parsley-validate'=>true,
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
                        <label for="name">{{__("Name")}}</label>
                        {!! Form::text('name',$setting->label,[
                            'class' =>  'form-control',
                            'data-parsley-required'=>true,
                            'readonly','disabled'
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="value">{{__("Value")}}</label>
                        {!! Form::text('value',$setting->value,[
                            'class' =>  'form-control',
                            'data-parsley-required'=>true
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary">{{__("Save")}}</button>
                    <a class="btn btn-warning" href="{{ route('supplier.offices.index') }}">{{__('Back')}}</a>
                </div>
            </div>


            {!! Form::close() !!}
        </div>
    </div>
@endsection
