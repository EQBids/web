<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/26/18
 * Time: 3:53 PM
 */
?>

@extends('web.contractor.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="stepwizard">
                <div class="stepwizard-row">
                    <div class="stepwizard-step">
                        <button type="button" class="btn btn-secondary btn-circle" data-step="1">1</button>
                        <p>Destination</p>
                    </div>
                    <div class="stepwizard-step">
                        <button type="button" class="btn btn-secondary btn-circle" data-step="2">2</button>
                        <p>{{ __('Available suppliers') }}</p>
                    </div>

                    <div class="stepwizard-step">
                        <button type="button" class="btn btn-secondary btn-circle" data-step="3">3</button>
                        <p>{{ __('Quantities and dates') }}</p>
                    </div>
                    <div class="stepwizard-step">
                        <button type="button" class="btn btn-secondary btn-circle" data-step="4">4</button>
                        <p>Final</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @yield('process_content')
@endsection




