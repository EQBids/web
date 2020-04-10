<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 4/7/18
 * Time: 5:00 PM
 */
?>


@extends('web.contractor.orders.process.layout')


@section('process_content')
    <h2 class="text-center mb-60">{{ __('Available suppliers') }}</h2>
    @if($suppliers->count()==0)
        <div class="alert alert-danger">
            <p>
                {{ __('No suppliers found in your area') }}
            </p>
        </div>
    @else
        @include('web.partials.show_errors')
        <h3 class="text-center" id="parsley_error"></h3>
        <form method="post" action="{{ route('contractor.orders.process.suppliers.store') }}" data-parsley-validate>
            {{ csrf_field() }}

        <table class="table table-striped table-bordered" >
            <thead>
                <tr>
                    <th>Distance (km)</th>
                    <th>Name</th>
                    <th>{{ __('Details') }}</th>
                    <th>
                            {{ __('Select/unselect all') }} <input type="checkbox" id="select_all" class="ml-20">
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                    <tr>
                        <td>
                            {{ $supplier->distance == 0 ? 'Local' : round($supplier->distance,1) }}
                        </td>
                        <td>
                            {{ $supplier->name }}
                        </td>
                        <td>
                            <a href="{{ route('contractor.suppliers.view',[$supplier->id]) }}" class="btn-primary btn text-center" target="_blank" >View</a>
                        </td>
                        <td>
                            <div class="form-check text-center">
                                <input type="checkbox" class="form-check-input" name="suppliers[]" value="{{ $supplier->id }}"
                                       data-parsley-required data-parsley-mincheck="1" data-parsley-multiple
                                       data-parsley-required-message="You must select at least one supplier",
                                       data-parsley-errors-container="#parsley_error"
                                />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row mt-80">
            <div class="col-lg-6">
                <a class="btn btn-warning pull-right" href="{{ route('contractor.orders.process.location') }}" ><i class="fa fa-angle-double-left" ></i> {{ __('Back') }}</a>
            </div>
            <div class="col-lg-4">
                <button type="submit" class="btn btn-primary" ><i class="fa fa-angle-double-right" ></i> {{ __('Continue') }}</button>
            </div>
        </div>
    @endif

@endsection

@push('before_footer_scripts')
    <script type="text/javascript">
        var highlight_url="{{  route('contractor.cart') }}";
    </script>

    <script type="text/javascript">
        var stepwizard_step = 3;
    </script>

@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $('.table').dataTable({
            columnDefs:[
                { targets: [2,3], orderable: false }
            ]
        });


        $('#select_all').change(function(){
            $('[name="suppliers[]"]').prop('checked',this.checked);
        });

        var old_suppliers = {!! json_encode(old('suppliers',isset($old_suppliers)?$old_suppliers:[])) !!};
        if(old_suppliers.length==0){
            $('[name="suppliers[]"]').prop('checked',true);
        }
        for(var idx=0;idx<old_suppliers.length;idx++){
            $('[name="suppliers[]"][value="'+old_suppliers[idx]+'"]').prop('checked',true);
        }

    </script>
@endpush