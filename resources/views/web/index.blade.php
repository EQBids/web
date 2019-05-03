@extends('web.public')

@section('banner')
    <header><h1>EQBIDS</h1></header>
    <img src="images/Image.jpg" alt="EQBids">
@endsection
@section('top_menu')
    <div class="collapse navbar-collapse">
        @if(Auth::check())
            @if(Auth::user()->is_contractor)
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a href="{{ route('contractors_dashboard') }}" class="nav-icon" ><i class="fa fa-dashboard"></i>{{__('Contractor Dashboard')}}</a></li>
                </ul>
            @elseif(Auth::user()->is_admin)
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-icon" ><i class="fa fa-dashboard"></i>{{__('Admins Dashboard')}}</a></li>
                </ul>
            @elseif(Auth::user()->is_supplier)
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a href="{{ route('suppliers_dashboard') }}" class="nav-icon" ><i class="fa fa-dashboard"></i>{{__('Supplier Dashboard')}}</a></li>
                </ul>
            @endif
        @else
            <ul class="navbar-nav mr-0">
                <li class="nav-item"><a href="{{ route('show_login') }}" class="nav-icon" id="icon-account">{{ __('Login/Signup') }}</a> </li>
            </ul>
        @endif

    </div>
@endsection


@section('slider')
    <div id="eqbids_slider_wrapper" class="rev_slider_wrapper fullwidthbanner-container" data-alias="webster-construction" data-source="gallery" style="margin:0px auto;background:transparent;padding:0px;margin-top:0px;margin-bottom:0px;">
        <!-- START REVOLUTION SLIDER 5.4.6.3 fullwidth mode -->
        <div id="eqbids_slider" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.6.3">
            <ul>  <!-- SLIDE  -->
                @foreach($slides as $slide)
                <li data-index="rs-747" data-transition="fade" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="default" data-easeout="default" data-masterspeed="300"  data-thumb="revolution/assets/slider-construction/100x50_ae8a9-1.jpg"  data-rotate="0"  data-saveperformance="off"  data-title="Slide" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                    <!-- MAIN IMAGE -->
                    <img src="{{ asset($slide) }}"  alt=""  data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina>

                </li>
                @endforeach
            </ul>
            <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div> </div>
    </div>
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-6 co-sm-12">
                <img class="img-fluid" src="{{ asset('images/RUS.jpg') }}" alt="">
            </div>
            <div class="col-lg-6 sm-mt-30 col-sm-12">
                <div class="section-title line lef mb-20">
                    <h6 class="subtitle">About Us</h6>
                    <h2 class="title">Get to know us better.</h2>
                    <p class="mt-30">Simply dummy text of the printing and typesetting industry.  when an unknown printer took  scrambled it to make a type specimen book.</p>
                </div>
                <p>laboris nisi ut aliquip ex ea commodo consequat, It has survived not only five centuries, but also the leap into electronic typesetting.</p>
                <div class="row mt-30">
                    <div class="col-sm-6 col-xs-6 col-xx-12">
                        <ul class="list list-hand">
                            <li> Project Buildings</li>
                            <li> Home Maintenance </li>
                        </ul>
                    </div>
                    <div class="col-sm-6 col-xs-6 col-xx-12">
                        <ul class="list list-hand">
                            <li> Value Engineering</li>
                            <li> Project Management  </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('more-sections')

    <section class="page-section-ptb text-white bg-overlay-black-70 parallax" data-jarallax='{"speed": 0.6}'>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="section-title line lef">
                        <h6 class="text-white subtitle">We're Good At</h6>
                        <h2 class="text-white title">Our Services</h2>
                        <p class="text-white">{{ __('Conecting suppliers and contractors') }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-sm-12 col-xs-12 sm-mb-30">
                    <div class="counter left-icon text-white">
                        <span class="icon ti-cup theme-color" aria-hidden="true"></span>
                        <span class="timer" data-to="4905" data-speed="10000">4905</span>
                        <label>suppliers </label>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12 col-xs-12 xs-mb-30">
                    <div class="counter left-icon text-white">
                        <span class="icon ti-check-box theme-color" aria-hidden="true"></span>
                        <span class="timer" data-to="4782" data-speed="10000">4782</span>
                        <label>COMPLETED PROJECTS</label>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12 col-xs-12">
                    <div class="counter left-icon text-white">
                        <span class="icon ti-face-smile theme-color" aria-hidden="true"></span>
                        <span class="timer" data-to="3237" data-speed="10000">3237</span>
                        <label>Satisfied Contractors </label>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('footer_scripts')

    <script src="{{ asset("js/revolution/jquery.themepunch.tools.min.js") }}"></script>
    <script src="{{ asset("js/revolution/jquery.themepunch.revolution.min.js") }}"></script>

    <!-- SLIDER REVOLUTION 5.0 EXTENSIONS  (Load Extensions only on Local File Systems !  The following part can be removed on Server for On Demand Loading) -->
    <script src="{{ asset("js/revolution/extensions/revolution.extension.actions.min.js") }}"></script>
    <script src="{{ asset("js/revolution/extensions/revolution.extension.carousel.min.js") }}"></script>
    <script src="{{ asset("js/revolution/extensions/revolution.extension.kenburn.min.js") }}"></script>
    <script src="{{ asset("js/revolution/extensions/revolution.extension.layeranimation.min.js") }}"></script>
    <script src="{{ asset("js/revolution/extensions/revolution.extension.migration.min.js") }}"></script>
    <script src="{{ asset("js/revolution/extensions/revolution.extension.navigation.min.js") }}"></script>
    <script src="{{ asset("js/revolution/extensions/revolution.extension.parallax.min.js") }}"></script>
    <script src="{{ asset("js/revolution/extensions/revolution.extension.slideanims.min.js") }}"></script>
    <script src="{{ asset("js/revolution/extensions/revolution.extension.video.min.js") }}"></script>

    <script type="text/javascript">
        var revapi263,
            tpj=jQuery;
        tpj(document).ready(function() {
            if(tpj("#eqbids_slider").revolution == undefined){
                revslider_showDoubleJqueryError("#eqbids_slider");
            }else{
                revapi263 = tpj("#eqbids_slider").show().revolution({
                    sliderType:"standard",
                    sliderLayout:"fullwidth",
                    dottedOverlay:"none",
                    delay:9000,
                    navigation: {
                        keyboardNavigation:"off",
                        keyboard_direction: "horizontal",
                        mouseScrollNavigation:"off",
                        mouseScrollReverse:"default",
                        onHoverStop:"off",
                        touch:{
                            touchenabled:"on",
                            touchOnDesktop:"off",
                            swipe_threshold: 75,
                            swipe_min_touches: 1,
                            swipe_direction: "horizontal",
                            drag_block_vertical: false
                        }
                        ,
                        arrows: {
                            style:"gyges",
                            enable:true,
                            hide_onmobile:true,
                            hide_under:767,
                            hide_onleave:false,
                            tmp:'',
                            left: {
                                h_align:"left",
                                v_align:"center",
                                h_offset:20,
                                v_offset:0
                            },
                            right: {
                                h_align:"right",
                                v_align:"center",
                                h_offset:20,
                                v_offset:0
                            }
                        }
                    },
                    visibilityLevels:[1240,1024,778,480],
                    gridwidth:1270,
                    gridheight:400,
                    lazyType:"none",
                    shadow:0,
                    spinner:"spinner2",
                    stopLoop:"off",
                    stopAfterLoops:-1,
                    stopAtSlide:-1,
                    shuffle:"off",
                    autoHeight:"off",
                    disableProgressBar:"on",
                    hideThumbsOnMobile:"off",
                    hideSliderAtLimit:0,
                    hideCaptionAtLimit:0,
                    hideAllCaptionAtLilmit:0,
                    debugMode:false,
                    fallbacks: {
                        simplifyAll:"off",
                        nextSlideOnWindowFocus:"off",
                        disableFocusListener:false,
                    }
                });
            }
        });
    </script>
@endpush

