
@extends('web.contractor.equipment.category.index')

@section('category_content')
    <div class="row">

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ url()->previous() }}" class="btn btn-primary">{{__("Back")}}</a>
            </div>
        </div>

        <div class="col-lg-12">
            <h1>{{__("Equipment Details")}}</h1>
        </div>
        <?php
	        $current_category=$equipment->categories()->first();
	        $categories_stack=[[$current_category->name,$current_category->slug]];
	        while($current_category->parent){
	        	$categories_stack=array_prepend($categories_stack,[$current_category->parent->name,$current_category->parent->slug]);
	        	$current_category=$current_category->parent;
            }
        ?>
        <div class="col-lg-12 text-right">
            <a href="{{ route('contractor.equipment.index') }}"><i class="fa fa-home"></i> Equipment</a>
            @for($index=0;$index<sizeof($categories_stack);$index++)
                <i class="fa fa-angle-double-right"></i> <a href="{{ route('contractor.equipment.category',[$categories_stack[$index][1]]) }}"> {{ $categories_stack[$index][0] }}</a>
                @endfor
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <img src="{{ isset($equipment->details['image']) && $equipment->details['image']?asset($equipment->details['image']):'http://via.placeholder.com/350x150' }}" style="width:100%;" alt="">
                <div class="add-to-cart">
                    <button  class="add-item-cart btn btn-sm btn-primary d-none" data-equipment-id="{{$equipment->id}}" ><i class="fa fa-cart-plus"></i>Add to order</button>
                    <button  class="remove-item-cart btn btn-sm btn-danger d-none" data-equipment-id="{{$equipment->id}}" ><i class="fa fa-cart-plus"></i>Remove from order</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <p><strong>{{__("Name")}}</strong></p>
            </div>

            <div class="col-lg-9">
                <p>{{$equipment->name}}</p>
            </div>

        </div>

        @foreach($equipment->details as $detailName => $value)
            @if (in_array($detailName,['model']))
                <div class="row">
                    <div class="col-lg-3">
                        <p><strong>{{$detailName}}</strong></p>
                    </div>

                    <div class="col-lg-9">
                        <p>{!!  clean(html_entity_decode($value)) !!}</p>
                    </div>
                </div>
            @endif
        @endforeach
        <div class="row">
            <h3>{{ __('Description') }}</h3>
            <hr/>
            <div class="col-lg-12">
                {!! isset($equipment->details['description'])?clean(html_entity_decode($equipment->details['description'])):'' !!}
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <a href="{{ url()->previous() }}" class="btn btn-primary">{{__("Back")}}</a>
            </div>
        </div>

@endsection