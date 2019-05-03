@extends('web.admin.layout')

@section('content')

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            {!! Form::open(
                [
                    'method'=>'POST',
                    'route'=>'admin.equipment.store',
                    'files'=>true,
                    'data-parsley-validate'=>true
            ]) !!}

                <h1>{{__("Create Equipment")}}</h1>

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
                            <label for="category">{{__("Category")}}</label>
                            <select name="category" id="category" class="form-control" data-parsley-required>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

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
                            <label for="image">{{__("Image")}}</label>
                            {!! Form::file('image',[
                                'class'                 =>  'form-control',
                                'id'                    =>  'image',
                                'data-parsley-required' =>  true,
                            ]) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="email_cost_code">{{__("Email cost code")}}</label>
                            <div class="box">
                                <select name="email_cost_code" id="email_cost_code" class="fancyselect wide">
                                    @for($i = 1 ; $i <= 10 ; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="bid_cost_code">{{__("Bid cost code")}}</label>
                            <div class="box">
                                <select name="bid_cost_code" id="bid_cost_code" class=" fancyselect wide" >
                                    @for($i = 1 ; $i <= 10 ; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-20">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{__("Allow attachments")}}</label>
                                <input type="hidden" value="0" name="allow_attachments" />
                                <input type="checkbox" class="checkbox-switch" value="1" name="allow_attachments" {{ old('allow_attachments')?'checked':'' }}/>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="">{{__("Excerpt")}}</label>
                        <textarea id="excerpt" name="excerpt">{{ old('excerpt') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="bid_cost_code">{{__("Description")}}</label>
                        <textarea id="description" name="description">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ old('status')==1? "checked": ""}}>
                        <label class="form-check-label" for="status_active">Active</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ old('status')==0? "checked": ""}}>
                        <label class="form-check-label" for="status_inactive">Inactive</label>
                    </div>
                </div>
            </div>

                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('admin.equipment.index')}}" class="btn btn-warning ">{{__("Back")}}</a>
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