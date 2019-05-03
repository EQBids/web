<div class="row">
    <div class="col-md-12">
        @include('web.contractor.equipment.category.breadcrum',['current_category'])
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h3 class="text-center">@yield('category_title')</h3>
    </div>
</div>

<div class="row">
    @if((isset($current_category) && isset($categories[$current_category->id])) || !isset($current_category))
        @foreach($categories[isset($current_category)?$current_category->id:""] as $category)
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="product mb-40">
                    <div class="product-image">
                        <a href="{{ route('contractor.equipment.category',[$category['slug']]) }}">
                            <img class="img-fluid mx-auto" src="{{ $category['thumbnail']?asset($category['thumbnail']):'http://via.placeholder.com/350x150' }}" alt="">
                        </a>
                    </div>
                    <div class="product-des">
                        <div class="product-title">
                            <a href="{{ route('contractor.equipment.category',[$category['slug']]) }}">{{ $category['name'] }} ({{ isset($category['subcategories_items'])?$category['subcategories_items']:$category['equipments_count']}})</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<div class="row mt-50">
    <div class="col-lg-12">
        <h3 class="text-center">Equipments</h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        @if($equipments->count()>0)
            @foreach($equipments as $equipment)
                @if($equipment->status==1)
                    <div class="product listing">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="product-image">
                                    <a href="{{ route('contractor.equipment.show',[$equipment->id]) }}">
                                        <img class="img-fluid mx-auto" src="{{ $equipment->thumbnail?asset($equipment->thumbnail):'http://via.placeholder.com/350x150' }}" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="product-des text-left">
                                    <div class="product-title">
                                        <a href="{{ route('contractor.equipment.show',[$equipment->id]) }}">{{ $equipment->name }}</a>
                                    </div>
                                    <div class="product-info mt-20">
                                        {!! isset($equipment->details['excerpt'])?clean(html_entity_decode($equipment->details['excerpt'])):'' !!}
                                    </div>
                                    <div class="add-to-cart row">
                                        <div class="col-sm-3 offset-sm-9">
                                            <button  class="add-item-cart btn btn-sm btn-primary d-none" data-equipment-id="{{$equipment['id']}}" ><i class="fa fa-cart-plus"></i>Add to order</button>
                                            <button  class="remove-item-cart btn btn-sm btn-danger d-none" data-equipment-id="{{$equipment['id']}}" ><i class="fa fa-cart-plus"></i>Remove from order</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (!$loop->last)
                    <div class="divider mt-30 mb-30"></div>
                @endif
            @endforeach
        @else
            <div class="col-lg-12">
                <div class="alert alert-danger">
                    <p>{{ __('Not available yet') }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <nav aria-label="Page navigation">
            {{$equipments->links() }}
        </nav>
    </div>
</div>