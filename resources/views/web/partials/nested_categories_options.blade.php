<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 4/28/18
 * Time: 12:12 PM
 */

if(!isset($level)){
	$level=0;
}


?>
@foreach($categories as $categoryItem)
    @if(!(isset($category) && $category && $category->id == $categoryItem->id))
        <option style="padding-left: {{$level}}em;" value="{{$categoryItem->id}}" {{ ((isset($category) && $category && $category->parent && $category->parent->id == $categoryItem->id) || (isset($category_id) && $category_id== $categoryItem->id))?'selected':'' }}>{{$categoryItem->name}}</option>
        @include('web.partials.nested_categories_options',['categories'=>$categoryItem->childs,'level'=>$level+1, 'category'=>(isset($category)?$category:null), 'category_id'=>(isset($category_id)?$category_id:null)])
    @endif
@endforeach

@if($level==0)
    @push('footer_scripts')
        <script type="application/javascript">
            $('{{$container}}').select2({
                templateResult: function (data) {
                    // We only really care if there is an element to pull classes from
                    if (!data.element) {
                        return data.text;
                    }

                    var $element = $(data.element);

                    var $wrapper = $('<span></span>');
                    $wrapper.addClass($element[0].className);
                    $wrapper.css('padding-left',$(data.element).css('padding-left'));

                    $wrapper.text(data.text);

                    return $wrapper;
                }
            });
        </script>

    @endpush

@endif