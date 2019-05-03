@extends('web.admin.layout')

@section('content')

    <div class="col-lg-12">
        <a class="btn btn-warning" href="{{ route('admin.categories.index') }}">{{ __('Back') }}</a>
    </div>


    <div class="col-lg-6 offset-lg-3">

        <h1>{{__("Create a new category")}}</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {!! Form::open(['method'=>'POST','route'=>'admin.categories.store','files'=>true]) !!}
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label style="display: block" for="">{{__("Parent")}}</label>
                        <select name="parent" id="parent" class="form-control">
                            <option value="">{{__("Choose")}}</option>
                            @include('web.partials.nested_categories_options',['categories'=>$categories,'container'=>'#parent','category_id'=>$category_id])
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="image">{{__("Image")}}</label>
                        {!! Form::file('image',[
                            'class'=>'form-control',
                            'data-parsley-required'
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="name">{{__("Name")}}</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status_active" value="1" checked>
                        <label class="form-check-label" for="status_active">Active</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status_inactive" value="2">
                        <label class="form-check-label" for="status_inactive">Inactive</label>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary">{{__("Create")}}</button>
                    <a class="btn btn-warning" href="{{ route('admin.categories.index') }}">{{ __('Back') }}</a>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('footer_scripts')

@endpush