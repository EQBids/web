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

    <div class="col-lg-6">

        <div class="form-group">
            <label for="">Name</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ $supplier->name }}">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="">Address</label>
            <input type="text" readonly class="form-control-plaintext" value="{{ $supplier->address }}">
        </div>
    </div>
</div>

