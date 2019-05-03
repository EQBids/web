<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/20/18
 * Time: 10:51 PM
 */
?>
@if(!isset($show_errors) || $show_errors)
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endif

{{ csrf_field() }}

@if(isset($contractors))
    <div class="row">

        <div class="col-lg-12">

            <div class="form-group">
                <label style="display:block" for="">Contractor<span class="required-symbol">*</span></label>
                <select name="contractor" id="contrator" class="wide fancyselect" data-parsley-required>
                    <option value="">Choose</option>
                    @foreach($contractors as $contractor)
                        <option {{ old('contractor',isset($site)?$site->contractor_id:'')==$contractor->id?'selected':'' }} value="{{$contractor->id}}">{{$contractor->company_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-lg-6 col-sm-12">
        <div class="form-group">
            <label for="">Job site nickname</label>
            {!! Form::text('nickname',old('nickname',isset($site)?$site->nickname:''),[
                'class' =>  'form-control'
            ]) !!}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="">Job site name<span class="required-symbol">*</span></label>
            {!! Form::text('name',old('name',isset($site)?$site->name:''),[
                'class' =>  'form-control',
                'data-parsley-required'=>'true',
            ]) !!}
        </div>
    </div>
</div>

<div class="row">

    <div class="col-lg-12">
        <div class="form-group">
            <label for="">Street address<span class="required-symbol">*</span></label>
            {!! Form::text('address',old('address',isset($site)?$site->address:''),[
                'class' =>  'form-control',
                'data-parsley-required'=>'true',
            ]) !!}
        </div>
    </div>
</div>

<div class="row">

    <div class="col-lg-4">
        <div class="form-group">

            <label for="">Country<span class="required-symbol">*</span></label>
            <select name="country" id="country" class="form-control" data-parsley-required>
            </select>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">

            <label for="">State / Province<span class="required-symbol">*</span></label>

            <select name="state" id="state" class="form-control" data-parsley-required>
            </select>
        </div>
    </div>
    <div class="col-lg-4">

        <div class="form-group">

            <label for="">City<span class="required-symbol">*</span></label>
            <select name="city" id="city" class="form-control" data-parsley-required>
            </select>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            <label for="">Postal code</label>
            {!! Form::text('zip',old('zip',isset($site)?$site->zip:''),[
                'class' =>  'form-control',
                'data-parsley-maxlength'=>6,
                'data-parsley-minlength'=>5
            ]) !!}
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
            <label for="">Phone<span class="required-symbol">*</span></label>
            {!! Form::text('phone',old('phone',isset($site)?$site->phone:''),[
                'class' =>  'form-control phone-mask',
                'data-parsley-required'=>'true',
                'data-mask-clearifnotmatch'=>'true',
                'data-parsley-pattern'=>'\(\d{3}\) \d{3}-\d{4}'
            ]) !!}
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
            <label for="">Site contact name</label>
            {!! Form::text('contact',old('contact',isset($site)?$site->contact:''),[
                'class' =>  'form-control'
            ]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label for="">Special Instructions</label>
            {!! Form::textarea('special_instructions',old('special_instructions',isset($site->details['special_instructions'])?$site->details['special_instructions']:''),[
                'class' =>  'form-control',
                'rows'  =>  5,
                'id'    =>  'special_instructions'
            ]) !!}
        </div>
    </div>
</div>


@push('footer_scripts')
    <script type="text/javascript">

        var current_country={{ old('country',isset($site) && $site->country_id?$site->country_id:'null') }};
        var current_states={{ old('state',isset($site) && $site->state_id ?$site->state_id:'null') }};
        var current_metro={{ old('city',isset($site) && $site->metro_id?$site->metro_id:'null') }};
        var current_cities={{ old('city',isset($site) && $site->city_id?$site->city_id:'null') }};

        $('#country').select2({
            placeholder:'{{ __('Country') }}',
            allowClear:true,
            ajax:{
                url:'{{ route('api.countries.index') }}',
                dataType:'json',
                data:function (params) {
                    var query = {
                        name:params.term,
                        page: params.page || 1
                    }
                    return query;
                },
                processResults:function (data) {
                    if(data == undefined){
                        return [];
                    }
                    var formatedData =data.data.map(function(item){
                       return {id:item.id,text:item.name};
                    });

                    return {
                        results:formatedData,
                        pagination:{
                            more:data.links.next!=null,
                        }
                    }
                }
            }
        });

        $('#state').select2({
            placeholder:'{{ __('State') }}',
            allowClear:true,
            ajax:{
                url:'{{ route('api.states.index') }}',
                dataType:'json',
                data:function (params) {
                    var query = {
                        name:params.term,
                        country:$('#country').val(),
                        page: params.page || 1
                    }
                    return query;
                },
                processResults:function (data) {
                    var country_id = $('#country').val();
                    if(country_id==undefined || country_id==''){
                        return [];
                    }
                    if(data == undefined){
                        return [];
                    }
                    var formatedData =data.data.map(function(item){
                        return {id:item.id,text:item.name};
                    });

                    return {
                        results:formatedData,
                        pagination:{
                            more:data.links.next!=null,
                        }
                    }
                }
            }

        });

        $('#metro').select2({
            placeholder:'{{ __('Metro') }}',
            allowClear:true,
            ajax:{
                url:'{{ route('api.metros.index') }}',
                dataType:'json',
                data:function (params) {
                    var query = {
                        name:params.term,
                        country:$('#country').val(),
                        state:$('#state').val(),
                        page: params.page || 1
                    }
                    return query;
                },
                processResults:function (data) {

                    var state_id = $('#state').val();
                    if(state_id==undefined || state_id==''){
                        return [];
                    }

                    if(data == undefined){
                        return [];
                    }
                    var formatedData =data.data.map(function(item){
                        return {id:item.id,text:item.name};
                    });

                    return {
                        results:formatedData,
                        pagination:{
                            more:data.links.next!=null,
                        }
                    }
                }
            }

        });

        $('#city').select2({
            placeholder:'{{ __('City') }}',
            allowClear:true,
            ajax:{
                url:'{{ route('api.cities.index') }}',
                dataType:'json',
                data:function (params) {
                    var query = {
                        name:params.term,
                        country:$('#country').val(),
                        state:$('#state').val(),
                        page: params.page || 1
                    }
                    return query;
                },
                processResults:function (data) {

                    var country_id = $('#country').val();
                    if(country_id==undefined || country_id==''){
                        return [];
                    }

                    if(data == undefined){
                        return [];
                    }
                    var formatedData =data.data.map(function(item){
                        return {id:item.id,text:item.name};
                    });

                    return {
                        results:formatedData,
                        pagination:{
                            more:data.links.next!=null,
                        }
                    }
                }
            },
            sorter: function(data) {
                /* Sort data using lowercase comparison */
                return data.sort(function (a, b) {
                    a = a.text.toLowerCase();
                    b = b.text.toLowerCase();
                    if (a > b) {
                        return 1;
                    } else if (a < b) {
                        return -1;
                    }
                    return 0;
                });
            }
        });


        $('#country').on('change',onCountryChange);
        $('#state').on('change',onStateChange);
        $('#metro').on('change',onMetroChange);


        function onCountryChange() {

            $("#state option").remove();
            $('#metro option').remove();
            $('#city option').remove();
            $("#state").val("").trigger("change");
            $("#metro").val("").trigger("change");
            $('#city').val('').trigger('change');

        }

        function onStateChange() {
            $('#metro option').remove();
            $('#city option').remove();
            $('#metro').val('').trigger('change');
            $('#city').val('').trigger('change');
        }

        function onMetroChange() {
            $('#city option').remove();
            $('#city').val('').trigger('change');
        }

        onCountryChange();

        @if(isset($country))
            $('#country').append(new Option('{{ $country->name }}',{{$country->id}})).trigger('change');
        @endif

        @if(isset($state))
            $('#state').append(new Option('{{ $state->name }}',{{$state->id}})).trigger('change');
        @endif

        @if(isset($metro))
            $('#metro').append(new Option('{{ $metro->name }}',{{$metro->id}})).trigger('change');
        @endif

        @if(isset($city))
            $('#city').append(new Option('{{ $city->name }}',{{$city->id}})).trigger('change');
        @endif

    </script>
@endpush
