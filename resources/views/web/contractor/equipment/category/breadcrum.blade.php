<?php
/**
 * Created by PhpStorm.
 * User: smith
 */
?>

@if(!isset($breadcrum_first))
    <ul class="page-breadcrumb">
        @include('web.contractor.equipment.category.breadcrum',['breadcrum_first'=>false,'current_category'])
    </ul>
@elseif(!isset($current_category) || !$current_category)
    <li><a href="{{ route('contractor.equipment.index') }}"><i class="fa fa-home"></i> Equipment</a></li>
@else
    @include('web.contractor.equipment.category.breadcrum',['breadcrum_first'=>false,'current_category'=>$current_category->parent,'categories'])
    <li><i class="fa fa-angle-double-right"></i> <a href="{{ route('contractor.equipment.category',[$current_category['slug']]) }}">{{ $current_category['name'] }}</a></li>
@endif