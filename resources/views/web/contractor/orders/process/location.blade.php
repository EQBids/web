<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 4/4/18
 * Time: 9:09 PM
 */
?>

@extends('web.contractor.orders.process.layout')

@section('process_content')
    <h2 class="text-center mb-60">{{ __('Where should this equipment be delivered?') }}</h2>
       <form method="post" action="{{ route('contractor.orders.process.location.store') }}">
            @include('web.partials.show_errors')

            <div class="row">
                {{ method_field('post') }}
                {{ csrf_field() }}

                <div class="col-lg-4">
                    <div class="form-group">
                        <label>Select an existing job site</label>
                        <select id="site" name="site" class="fancyselect wide">
                            <option value="">-</option>
                            @if(isset($sites)) 
                            @foreach($sites as $_site)
                                
                                <option value="{{ $_site->id }}" {{ (old('site')==$_site->id) || (isset($site_id) && $site_id==$_site->id)?'selected':'' }}> {{ $_site->nickname }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="text-center mt-80">
                        <input type="submit" class="btn btn-success btn-lg" name="existing" value="Pick and continue" />
                    </div>
                </div>
                <div class="col-lg-8">
                    <h4>Or create a new one</h4>
                    <?php $show_errors=false; ?>
                    @include('web.contractor.job_sites.form')

                    <div class="text-center mt-20">
                        <input type="submit" class="btn btn-success btn-lg" name="new" value="Create and continue" />
                    </div>

                </div>

            </div>
        </form>
@endsection


@push('footer_scripts')
    @include('web.partials.geo')
    @if(isset($sites)) 
    <script type="text/javascript">
        var sites = {!! \App\Http\Resources\Buyer\siteResource::collection($sites)->keyBy('id') !!};

    </script>
    @endif
@endpush


@push('before_footer_scripts')
    <script type="text/javascript">
        var highlight_url="{{  route('contractor.cart') }}";
    </script>

    <script type="text/javascript">
        var stepwizard_step = 2;
    </script>

@endpush