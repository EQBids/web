@extends('web.contractor.layout')

@section('content')
    <div class="col-lg-12">
        <a class="btn btn-danger" href="{{ route('contractor.offices.index') }}">{{__('Back')}}</a>
    </div>
    <div class="col-lg-12">

        <h1>{{__("Edit an office")}}</h1>

        {!! Form::open(
            [
                'method'=>'PUT','route'=>['contractor.offices.update',$office->id],
                'data-parsley-validate'=>true,
                'files'=>true,
            ])
        !!}

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="location">{{__("Location name")}}</label>
                    {!! Form::text('location',$office->company_name,[
                        'class' =>  'form-control',
                        'data-parsley-required'=>true
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="company_name">{{__("Address")}}</label>
                    {!! Form::text('address',$office->address,[
                        'class' =>  'form-control',
                        'data-parsley-required'=>true
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="country">{{__("Country")}}</label>
                    <select name="country" id="country" class="form-control" data-parsley-required>
                        <option value="1">Canada</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="state">{{__("Province/State")}}</label>
                    <select name="state" id="state" class="form-control" data-parsley-required>
                        <option value="">{{__("Choose")}}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="company_name">{{__("City")}}</label>
                    <select name="city" id="city" class="form-control" data-parsley-required>
                        <option value="">{{__("Choose")}}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="zip">{{__("Postal Code")}}</label>
                    {!! Form::text('zip',$office->postal_code,[
                         'class' =>  'form-control zip',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="company_name">{{__("Notes")}}</label>
                    {!! Form::textarea('details',null,[
                         'class' =>  'form-control',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="">{{__("Image")}}</label>
                    {!! Form::file('image',null,[
                         'class' =>  'form-control',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary">{{__("Save")}}</button>
                <a class="btn btn-danger" href="{{ route('contractor.offices.index') }}">{{__('Back')}}</a>
            </div>
        </div>


        {!! Form::close() !!}
    </div>
@endsection


@push('footer_scripts')

    <script type="text/javascript">


        var translation = {
            A:{pattern: /[A-Za-z]/},
            0:{pattern: /[0-9]/}
        };

        var options =  {
            onInvalid: function(val, e, f, invalid, options){
                var error = invalid[0];

                //With this we handle the case in which the first character is a number, because for some reason, the plugin,
                //does not fires the method onKeyPress..

                if (/[0-9]/.test(error.v)){

                    $('.zip').mask('00000',options);
                }
                else if(/^[a-z]+$/i.test(error.v)){
                    $('.zip').mask('A0A A0A',options);
                }
                console.log ("Digit: ", error.v, " is invalid for the position: ", error.p, ". We expect something like: ", error.e);
            },
            translation:translation,

        };

        var zipVal = $('.zip').val();
        if (zipVal){

            //If the first character of the zip value of the editing offices is a letter, it means the zip code is from Canada,
            if( /^[a-z]+$/i.test(zipVal.substring(0,1)) ){
                $('.zip').mask('A0A A0A',options);
            }
            //If it's a number, it means is from the US
            else if(/[0-9]/.test(zipVal.substring(0,1))){
                $('.zip').mask('00000',options);
            }
        }



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


        function onCountryChange() {

            $("#state option").remove();
            $('#city option').remove();
            $("#state").val("").trigger("change");
            $('#city').val('').trigger('change');

        }

        function onStateChange() {
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

        @if(isset($city))
            $('#city').append(new Option('{{ $city->name }}',{{$city->id}})).trigger('change');
        @endif
    </script>
@endpush
