<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label for="category">{{__("Category")}}</label>
            <select name="category" id="category" class="form-control" data-parsley-required>
                @foreach($categories as $category)
                    <option {{$category->id == (isset($material)?$material->categories->first()->id:-1) ? "selected" : ""}} value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label for="brand">{{__("Name")}}</label>
            {!! Form::text('name',(isset($material)?$material->name:''),[
                'class'                 =>  'form-control',
                'id'                    =>  'name',
                'data-parsley-required' =>  true,
            ]) !!}
        </div>
    </div>
</div>

@if(isset($material->details['image']))
    <div class="row">
        <div class="col-lg-12">
            <p class="help-block">{{__("Image chosen")}}</p>
            <img style="width:230px;height: 160px;" src="{{asset($material->details['image'])}}" alt="Equipment Image">
        </div>
    </div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label for="image">{{__("Image")}}</label>
            {!! Form::file('image',[
                'class'                 =>  'form-control',
                'id'                    =>  'image',
                'data-parsley-required' =>  (!isset($material)),
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
                        <option {{ (isset($material) && $material->email_cost_code == $i) ? "selected" : ""}} value="{{$i}}">{{$i}}</option>
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
                        <option {{ (isset($material) && $material->bid_cost_code == $i) ? "selected" : ""}} value="{{$i}}">{{$i}}</option>
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
                <input type="checkbox" class="checkbox-switch" value="1" name="allow_attachments" {{ old('allow_attachments',(isset($material->details['allow_attachments']) && $material->details['allow_attachments']))?'checked':'' }}/>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label for="">{{__("Excerpt")}}</label>
            <textarea id="excerpt" name="excerpt">{{ old('excerpt',isset($material->details['excerpt'])?clean(html_entity_decode($material->details['excerpt'])):'') }}</textarea>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label for="bid_cost_code">{{__("Description")}}</label>
            <textarea id="description" name="description">{{ old('description',isset($material->details['description'])?clean(html_entity_decode($material->details['description'])):'') }}</textarea>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ old('status',isset($material)?$material->status:-1)==1? "checked": ""}}>
            <label class="form-check-label" for="status_active">Active</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ old('status',isset($material)?$material->status:-1)==0? "checked": ""}}>
            <label class="form-check-label" for="status_inactive">Inactive</label>
        </div>
    </div>
</div>