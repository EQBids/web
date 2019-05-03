<script>
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
                    name:params.term!=undefined?params.term:'',
                    country:$('#country').val()!=undefined?$('#country').val():'',
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


   // $('#country').on('change',onCountryChange);
   // $('#state').on('change',onStateChange);


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