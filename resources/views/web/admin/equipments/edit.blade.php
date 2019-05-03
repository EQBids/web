@extends('web.admin.layout')

@section('content')

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            {!! Form::open(
                [
                    'method'=>'PUT','route'=>['admin.equipment.update',$equipment->id],'files'=>true,
                    'data-parsley-validate'=>true
            ]) !!}

            <h1>{{__("Edit Equipment")}}</h1>

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
                                <option {{$category->id == $equipment->categories->first()->id ? "selected" : ""}} value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="brand">{{__("Name")}}</label>
                        {!! Form::text('name',$equipment->name,[
                            'class'                 =>  'form-control',
                            'id'                    =>  'name',
                            'data-parsley-required' =>  true,
                        ]) !!}
                    </div>
                </div>
            </div>

            @if(isset($equipment->details['image']))
                <div class="row">
                    <div class="col-lg-12">
                        <p class="help-block">{{__("Image chosen")}}</p>
                        <img style="width:230px;height: 160px;" src="{{asset($equipment->details['image'])}}" alt="Equipment Image">
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="image">{{__("Replace Image")}}</label>
                        {!! Form::file('image',[
                            'class'                 =>  'form-control',
                            'id'                    =>  'image',
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="email_cost_code">{{__("Email cost code")}}</label>
                        <select name="email_cost_code" id="email_cost_code" class=" fancyselect wide">
                            @for($i = 1 ; $i <= 10 ; $i++)
                                <option {{$equipment->email_cost_code == $i ? "selected" : ""}} value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="bid_cost_code">{{__("Bid cost code")}}</label>
                        <select name="bid_cost_code" id="bid_cost_code" class=" fancyselect wide">
                            @for($i = 1 ; $i <= 10 ; $i++)
                                <option {{$equipment->bid_cost_code == $i ? "selected" : ""}} value="{{$i}}">{{$i}}</option>
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
                        <input type="checkbox" class="checkbox-switch" value="1" name="allow_attachments" {{ old('allow_attachments',(isset($equipment->details['allow_attachments']) && $equipment->details['allow_attachments']))?'checked':'' }}/>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-lg-12 mt-20">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <label for="">{{__("Excerpt")}}</label>
                    </div>
                </div>
                <textarea id="excerpt" name="excerpt">{{ clean(old('excerpt',isset($equipment->details['excerpt'])?clean(html_entity_decode($equipment->details['excerpt'])):'')) }}</textarea>
            </div>
        </div>


        <div class="col-lg-12 mt-20">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <label for="bid_cost_code">{{__("Description")}}</label>
                    </div>
                </div>
                <textarea id="description" name="description">{{ clean(old('description',isset($equipment->details['description'])?clean(html_entity_decode($equipment->details['description'])):'')) }}</textarea>
            </div>
        </div>
        <div class="col-lg-8 offset-lg-2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{$equipment->status == 1 ? "checked": ""}}>
                        <label class="form-check-label" for="status_active">Active</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{$equipment->status == 0 ? "checked": ""}}>
                        <label class="form-check-label" for="status_inactive">Inactive</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <a href="{{route('admin.equipment.index')}}" class="btn btn-warning ">{{__("Back")}}</a>
                    <button type="submit" class="btn btn-primary">{{__("Save")}}</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@push('footer_scripts')

    <script>
        $(function(){
            /*
            $('#brand').select2({
                placeholder:"{{__("Choose")}}",
                allowClear:true,
            });
            */
            $('#category').select2({
                placeholder:"{{__("Choose")}}",
                allowClear:true,
            });

            $('#description').summernote({
                placeholder: 'Equipment description',
                tabsize: 2,
                height: 600
            });

            $('#excerpt').summernote({
                placeholder: 'Equipment excerpt',
                tabsize: 2,
                height: 200
            });
        });
    </script>
@endpush