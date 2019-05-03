<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/26/18
 * Time: 10:54 AM
 */

if (!isset($current_group)){
	$current_group = $categories[""];
}

?>

<div class="{{ isset($ul_classes)?$ul_classes:'' }}">
    <ul class="nav navbar-nav flex-column">
        @foreach($current_group as $category)
            <li class="nav-item" >
                <a class="" href="{{ route('supplier.equipment.category',[$category['slug']]) }}">
                    @if (isset($categories[$category['id']]))
                        <i class="fa fa-arrow-circle-right"></i>
                    @else
                        <i class="fa fa-circle-o"></i>
                    @endif
                    {{ $category['name'] }} ({{ isset($category['subcategories_items'])?$category['subcategories_items']:$category['equipments_count']}})

                </a>
                @if(isset($categories[$category['id']]))
                    @include('web.supplier.equipment.category.menu_list',['current_group'=>$categories[$category['id']],'ul_classes'=>'collapse'])
                @endif
            </li>
        @endforeach
    </ul>
</div>