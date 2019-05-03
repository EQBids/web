<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 4/21/18
 * Time: 9:11 PM
 */
?>


@extends('web.supplier.layout')

@section('content')
    <div class="row">

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ url()->previous() }}" class="btn btn-primary">{{__("Back")}}</a>
            </div>
        </div>

        <div class="col-lg-12">
            <h1>{{__("Equipment Details")}}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <img src="{{ isset($equipment->details['image']) && $equipment->details['image']?asset($equipment->details['image']):'http://via.placeholder.com/350x150' }}" style="width:100%;" alt="">
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
