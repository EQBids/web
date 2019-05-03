<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/26/18
 * Time: 12:52 PM
 */
?>
@extends('web.admin.equipments.category.index')

@section('category_content')
    @include('web.admin.equipments.category.category_content')
@endsection

@push('before_footer_scripts')
    <script type="application/javascript">
        var highlight_categories = "{{ Request::url() }}";
        var highlight_url="{{  route('admin.equipment.listing.index') }}";
    </script>
@endpush