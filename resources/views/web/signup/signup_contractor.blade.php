@extends('web.public')

@section('content')
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title text-center">
                        <h6></h6>
                        <h2 class="title-effect">Signup as a contractor</h2>
                    </div>
                </div>

                <div class="col-lg-12" style="margin-top: 30px">

                    {!! Form::open(['method'=>'POST','route'=>'signup','data-parsley-validate']) !!}

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
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="" class="label">@lang('signup.first_name_label') <span class="required-symbol">*</span></label>
                                    <div class="control">
                                        {!! Form::text('first_name',null,[
                                            'class'=>'form-control',
                                            'pattern' =>'[A-Za-z]+',
                                            'data-parsley-required'=>'true'
                                        ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="" class="label">@lang('signup.last_name_label') <span class="required-symbol">*</span></label>
                                    <div class="control">
                                        {!! Form::text('last_name',null,[
                                            'class'=>'form-control',
                                            'pattern' =>'[A-Za-z]+',
                                            'data-parsley-required'=>'true'
                                        ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="" class="label">@lang('signup.company_name_label') <span class="required-symbol">*</span></label>
                                    <div class="control">
                                        {!! Form::text('company_name',null,[
                                            'class'=>'form-control',
                                            'data-parsley-required'=>'true'
                                        ]) !!}
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="" class="label">@lang('signup.street_address_label')</label>
                                    <div class="control">
                                        {!! Form::text('address',null,[
                                            'class'=>'form-control',
                                            'data-parsley-required'=>true,
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="" class="label">@lang('signup.postal_code_label')</label>
                                    <div class="control">
                                        {!! Form::text('postal_code',null,[
                                            'class'=>'form-control zip',
                                            'id'=>'autocomplete',
                                            'onfocus'=>'geolocate();'
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="" class="label" style="display: block">@lang('signup.country_label') <span class="required-symbol">*</span></label>
                                <select name="country" id="country" class="form-control" data-parsley-required>
                                    <option value="">Choose</option>
                                </select>
                            </div></div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="" class="label" style="display: block">@lang('signup.state_label') <span class="required-symbol">*</span></label>
                                <select name="state" id="state" class="form-control" data-parsley-required>
                                    <option value="">Choose</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="" class="label" style="display: block">@lang('signup.city_label') <span class="required-symbol">*</span></label>
                                <select name="city" id="city" class="form-control" data-parsley-required>
                                    <option value="">Choose</option>
                                </select>
                            </div>
                        </div>
                    </div>
               
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="label" style="display: block">{{ _('Industry') }}</label>
                                <select name="industry" id="industry" class="form-control" onchange="industrySelected()">
                                    <option value="">Choose</option>
                                    @foreach($industries as $industry)
                                        <option value="{{$industry->id}}">{{$industry->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="label" style="display: block">{{ _('Sub category') }}</label>
                                <select name="sub_industry" id="sub_industry" class="form-control">
                                </select>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="label">@lang('signup.main_phone_label') <span class="required-symbol">*</span></label>
                                <div class="control">
                                    {!! Form::text('phone',null,[
                                        'class'=>'form-control phone-mask',
                                        'data-parsley-required',
                                        'data-mask'=>'(000) 0000-0000',
                                        'data-parsley-pattern'=>'\(\d{3}\) \d{3}-\d{4}'
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="label">@lang('signup.email_label') <span class="required-symbol">*</span></label>
                                <div class="control">
                                    {!! Form::email('email',null,[
                                        'class'=>'form-control',
                                        'data-parsley-required'
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-12">

                            <button class="btn btn-primary pull-right">@lang('signup.register_label')</button>
                            <a href="{{ route('show_login') }}" class="btn btn-warning pull-right">{{ __('Back') }}</a>

                        </div>
                    </div>

                    <input type="hidden" name="role" value="contractor">
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

@endsection

@push('footer_scripts')
    @include('web.partials.zip')
    @include('web.partials.geo')

    <script type="application/javascript">
        var sub_industries = {!! json_encode($sub_industries) !!};

        $('#industry').select2({});
        $('#sub_industry').select2({});

        function industrySelected(){
            var industry=$('#industry').val();
            $('#sub_industry').empty().select2();
            if(industry!='' && industry in sub_industries){
                var data = $.map(sub_industries[industry], function (obj) {
                    obj.text=obj.name;
                    return obj;
                });
                $('#sub_industry').empty().select2({
                    data:data,
                });
            }
        }
        

        
        


    </script>
<script>
      // This example retrieves autocomplete predictions programmatically from the
      // autocomplete service, and displays them as an HTML list.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      function initService() {
        var displaySuggestions = function(predictions, status) {
          if (status != google.maps.places.PlacesServiceStatus.OK) {
            alert("This postal code does not exist");
            document.getElementById('autocomplete').value = "";
            return;
          }

          predictions.forEach(function(prediction) {
            //var li = document.createElement('li');
            console.log(prediction.description);
            //document.getElementById('results').appendChild(li);
          });
        };
        var service = new google.maps.places.AutocompleteService();
        service.getQueryPredictions({ input: document.getElementById('autocomplete').value + " Canada"}, displaySuggestions);
      }

      $("#autocomplete").blur(function(){
        initService();

      })
    </script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCtd3X36RZ89MYzAhEJ_2LJX0pb_j1-iYc&libraries=places&callback=initService"
        async defer></script>
@endpush
