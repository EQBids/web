<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/21/18
 * Time: 1:54 PM
 */
?>
<div class="row">

    {{ csrf_field() }}

    <div class="col-lg-12">

        <div class="form-group">
            <label for="">Contractor</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ $site->contractor->company_name }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <input type="hidden" readonly class="form-control-plaintext" value="{{ $site->nickname }}">
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group">
            <label for="">Job site name</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ $site->name }}">
        </div>
    </div>
</div>

<div class="row">

    <div class="col-lg-12">
        <div class="form-group">
            <label for="">Street address</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ $site->address }}">
        </div>
    </div>
</div>

<div class="row">

    <div class="col-lg-3">
        <div class="form-group">

            <label for="">Country</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ $site->country?$site->country->name:'' }}">
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">

            <label for="">State</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ $site->state?$site->state->name:'' }}">
        </div>
    </div>

    <div class="col-lg-3">

        <div class="form-group">

            <label for="">Metro area</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ $site->metro?$site->metro->name:'' }}">
        </div>
    </div>

    <div class="col-lg-3">

        <div class="form-group">

            <label for="">City</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ $site->city?$site->city->name:'' }}">
        </div>
    </div>

</div>

<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            <label for="">Postal code</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ $site->zip }}">
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
            <label for="">Phone</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ $site->phone }}">
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
            <label for="">Site contact name</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ $site->contact }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label for="">Special Instructions</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ $site->details['special_instructions'] }}">
        </div>
    </div>
</div>