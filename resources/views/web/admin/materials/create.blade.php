@extends('web.admin.layout')

@section('content')

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            {!! Form::open(
                [
                    'method'=>'POST',
                    'route'=>'admin.materials.store',
                    'files'=>true,
                    'data-parsley-validate'=>true
            ]) !!}

                <h1>{{__("Create Material")}}</h1>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @include('web.admin.materials.form')

                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('admin.materials.index')}}" class="btn btn-warning ">{{__("Back")}}</a>
                        <button type="submit" class="btn btn-primary">{{__("Create")}}</button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@push('footer_scripts')

    <script>
        $(function(){
            /*$('#brand').select2({
                placeholder:"{{__("Choose")}}",
                allowClear:true,
            });*/

            $('#category').select2({
                placeholder:"{{__("Choose")}}",
                allowClear:true,
            });

            $('#description').summernote({
                placeholder: 'Equipment description',
                tabsize: 2,
                height: 300
            });

            $('#excerpt').summernote({
                placeholder: 'Equipment excerpt',
                tabsize: 2,
                height: 200
            });
        });
    </script>
@endpush