<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/26/18
 * Time: 12:52 PM
 */
?>
@extends('web.contractor.equipment.category.index')

@section('category_content')
    @include('web.contractor.equipment.category.category_content')
@endsection

@push('before_footer_scripts')
    <script type="application/javascript">
        var highlight_categories = "{{ Request::url() }}";
        var highlight_url="{{  route('contractor.equipment.index') }}";
    </script>
@endpush