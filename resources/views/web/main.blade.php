<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 4/5/18
 * Time: 11:07 AM
 */
?>
<!DOCTYPE html>
<!--[if IE 7]><html lang="en" class="no-js oldie ie7"><![endif]-->
<!--[if IE 8]><html lang="en" class="no-js oldie ie8"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="es" class=" js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths"> <!--<![endif]-->
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EQBids - Rentals and Purchases Made Easy</title>
    <meta name="description" content="eqbids homepage">
    <meta name="keywords" content="homepage eqbids">
    <!-- Add to homescreen for Chrome ón Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="shortcut icon" href="/images/eqbids---canada-s-construction-marketplace-favicon.ico?3799077425"/>

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="gray">
    <meta name="apple-mobile-web-app-title" content="EQBids">

    <!-- iOS icons -->
    <!--<link rel="apple-touch-icon" href="{{ asset('main/images/icons/apple-touch-icon-180x180.png') }}">-->

    <!-- Tile icon for Win8 -->
    <!--<meta name="msapplication-TileImage" content="{{ asset('main/images/icons/ms-touch-icon-144x144-precomposed.png') }}">-->
    <meta name="msapplication-TileColor" content="#f36800">

    <!-- Generic Icon -->
    <link rel="shortcut icon" href="{{ asset('main/images/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- revoluation -->
    <link rel="stylesheet" type="text/css" href="{{ asset("css/revolution/settings.css")}}" media="screen" />
    <!-- Typography -->
    <link rel="stylesheet" type="text/css" href="{{ asset("css/typography.css")}}" />
    <!-- Shortcodes -->
    <link rel="stylesheet" type="text/css" href="{{ asset("css/shortcodes/shortcodes.css")}}" />

    <!-- plugins -->
    <link rel="stylesheet" type="text/css" href="{{ asset("css/plugins/mega_menu.css")}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset("css/plugins/font-awesome.min.css")}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset("css/plugins/bootstrap-datetimepicker.min.css")}}" />



    <!-- Style -->
    <link rel="stylesheet" type="text/css" href="{{ asset("css/style.css")}}" />

    <!-- Responsive -->
    <link rel="stylesheet" type="text/css" href="{{ asset("css/responsive.css")}}" />

    <link rel="stylesheet" type="text/css" href="{{ asset("css/sidebar.css")}}" />

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="https://fonts.googleapis.com/css?family=Bitter:400,700|Lato:400,900" rel="stylesheet">

</head>

<body role="document" class="st-container st-effect-3">

<div class="wrapper">

    <!--=================================
     preloader -->

    <div id="pre-loader">
        <img src="{{ asset('images/pre-loader/loader-06.svg') }}" alt="">
    </div>

    <!--=================================
     preloader -->

    <div class="st-pusher">

        <div class="st-menu st-effect-3 scrollbar" id="menu-1">
            <ul class="menu">

            </ul>
            <div class="newsletter newsletter-box newsletter-border">
                <div class="mb-30">
                    <h3>Newsletter</h3>
                    <p>Vel fugit quibusdam quidem animi deserunt aspernatur ab, minus placeat quaerat voluptatem!</p>
                </div>
                <div id="mc_embed_signup_scroll">
                    <form action="php/mailchimp-action.php" method="POST" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate">
                        <div id="msg"> </div>
                        <div id="mc_embed_signup_scroll_2">
                            <input id="mce-EMAIL" class="form-control placeholder" type="text" placeholder="Email address" name="email1" value="">
                        </div>
                        <div id="mce-responses" class="clear">
                            <div class="response" id="mce-error-response" style="display:none"></div>
                            <div class="response" id="mce-success-response" style="display:none"></div>
                        </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true">
                            <input type="text" name="b_b7ef45306f8b17781aa5ae58a_6b09f39a55" tabindex="-1" value="">
                        </div>
                        <div class="clear">
                            <button type="submit" name="submitbtn" id="mc-embedded-subscribe" class="button button-border mt-20 form-button">  Get notified </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="slide-footer">
                <div class="social-icons color-icon clearfix">
                    <ul>
                        <!-- <li class="social-google"><a href="#"> <i class="fa fa-google-plus"></i> </a></li> -->
                    </ul>
                </div>

            </div>
        </div>

        <!--=================================
         header -->

        <header id="header" class="header default fullWidth">
            <div class="topbar">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 xs-mb-10">
                            <div class="topbar-call text-center text-md-left">
                                <ul>
                                    <li><i class="fa fa-envelope-o theme-color"></i> <a href="mailto:team@eqbids.com"> <span>team@eqbids.com</span> </a> </li>
                                    <li><i class="fa fa-phone"></i> <a href="tel:+5196572671"> <span>+(519) 657-2671 </span> </a> </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="topbar-social text-center text-md-right">
                                <ul>
                                <li><a href="https://www.facebook.com/EQBids/"target="_blank""><span class="fa fa-facebook"></span></a></li>
                                <li><a href="https://www.instagram.com/eqbids/"target="_blank"><span class="fa fa-instagram"></span></a></li>
                        
                                <li><a href="https://www.twitter.com/eqbids"target="_blank"><span class="fa fa-twitter"></span></a></li>
                                <li><a href="https://www.linkedin.com/company/eqbids/"target="_blank"><span class="fa fa-linkedin"></span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--=================================
             mega menu -->

            <div class="menu">
                <!-- menu start -->
                <nav id="menu" class="mega-menu">
                    <!-- menu list items container -->
                    <section class="menu-list-items">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <!-- menu logo -->
                                    <ul class="menu-logo">
                                        <li>
                                            <a href="{{ url('') }}"><img id="logo_img" src="images/logo.png" alt="logo"> </a>
                                        </li>
                                    </ul>
                                    <!-- menu links -->
                                    <div class="menu-bar">
                                        <ul class="menu-links">
                                            @if(Auth::check())
                                                @if(Auth::user()->is_contractor)
                                                    <li class=""><a href="{{ route('contractors_dashboard') }}" id="account-nav" class="" ><i class="fa fa-dashboard"></i>{{__('Contractor Dashboard')}}</a></li>
                                                @elseif(Auth::user()->is_supplier)
                                                    <li class=""><a href="{{ route('suppliers_dashboard') }}" id="account-nav" class="" ><i class="fa fa-dashboard"></i>{{__('Suppliers Dashboard')}}</a></li>
                                                @elseif(Auth::user()->is_admin)
                                                    <li class=""><a href="{{ route('admin.dashboard') }}" id="account-nav" class="" ><i class="fa fa-dashboard"></i>{{__('Admins Dashboard')}}</a></li>
                                                @endif
                                                    <li><a href="{{ route('logout') }}" id="account-nav" class="">{{__('Logout')}}</a></li>
                                            @else
                                                <li class="active">
                                                    <a href="javascript:void(0)">
                                                        {{ __('Login/Signup') }} <i class="fa fa-angle-down fa-indicator"></i>
                                                        <div class="mobileTriggerButton"></div>
                                                    </a>
                                                    <ul class="drop-down-multilevel effect-expand-top" style="transition: all 400ms ease;">
                                                        <li>
                                                            <a href="{{ route('show_login') }}">{{ __('Login') }} </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('signup_contractor') }}">{{ __('Sign-up as contractor') }} </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('signup_supplier') }}">{{ __('Sign-up as supplier') }} </a>
                                                        </li>
                                                    </ul>

                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </nav>
                <!-- menu end -->
            </div>
        </header>

        <!--=================================
         header -->

        <!--=================================
         banner -->

        <section class="rev-slider">
            <div id="slider_wrapper" class="rev_slider_wrapper fullwidthbanner-container" data-alias="webster-slider-2" data-source="gallery" style="margin:0px auto;background:transparent;padding:0px;margin-top:0px;margin-bottom:0px;">
                <!-- START REVOLUTION SLIDER 5.4.6.3 fullwidth mode -->
                <div id="slider" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.6.3">
                    <ul>  <!-- SLIDE  -->
                        <li data-index="rs-757" data-transition="fade" data-slotamount="default"
                            data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="default"
                            data-easeout="default" data-masterspeed="300"
                            data-delay="8010"  data-rotate="0"  data-saveperformance="off"
                            data-title="Slide" data-description="">
                            <!-- MAIN IMAGE -->
                            <img src="{{ asset('images/slider/construction_background_slider212.jpg') }}"  alt=""
                                 data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                                 class="rev-slidebg" data-no-retina>
                            <!-- LAYERS -->
                        </li>
                        <li data-index="rs-757" data-transition="fade" data-slotamount="default"
                            data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="default"
                            data-easeout="default" data-masterspeed="300"
                            data-delay="8010"  data-rotate="0"  data-saveperformance="off"
                            data-title="Slide" data-description="">
                            <!-- MAIN IMAGE -->
                            <img src="{{ asset('images/slider/slider2.jpg') }}"  alt=""  data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina>
                            <!-- LAYERS -->
                        </li>
                    </ul>
                    <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div> </div>
            </div>
        </section>

        <!--=================================
         banner -->


        <!--=================================
        our-service -->

        <section class="our-service page-section-ptb">
            <div class="objects-left">

            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="section-title text-center">
                            <h6>What We Do</h6>
                            <h2 class="title-effect">We Make Rentals and Purchases Simpler, Smarter and More Streamlined</h2>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="feature-text left-icon mb-40">
                                    <div class="feature-icon">
                                        <span class="fa fa-desktop theme-color" aria-hidden="true"></span>
                                    </div>
                                    <div class="feature-info">
                                        <h5>User Friendly Design</h5>
                                        <p>The EQBids platform was designed with both Contractors and Suppliers needs in mind... </p>
                                        <a class="button icon-color" href="design">Read more <i class="fa fa-angle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="feature-text left-icon mb-40">
                                    <div class="feature-icon">
                                        <span class="fa fa-headphones theme-color" aria-hidden="true"></span>
                                    </div>
                                    <div class="feature-info">
                                        <h5>24/7 Customer support</h5>
                                        <p>Great support quibusdam reproduced enim <span class="theme-color" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tooltip on top">expedita</span> sed quia nesciunt incidunt..</p>
                                        <a class="button icon-color" href="support">Read more <i class="fa fa-angle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="feature-text left-icon mb-40">
                                    <div class="feature-icon">
                                        <span class="fa fa-sliders theme-color" aria-hidden="true"></span>
                                    </div>
                                    <div class="feature-info">
                                        <h5>Easy to Customize</h5>
                                        <p>Fully customizable template enim expedita sed quia nesciunt incidunt accusamus..</p>
                                        <a class="button icon-color" href="custom">Read more <i class="fa fa-angle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="feature-text left-icon mb-40">
                                    <div class="feature-icon">
                                        <span class="fa fa-shield theme-color" aria-hidden="true"></span>
                                    </div>
                                    <div class="feature-info">
                                        <h5>Powerful Performance</h5>
                                        <p>Fast as light Ipsum used since the 1500s is reproduced below for those Sections.. </p>
                                        <a class="button icon-color" href="perform">Read more <i class="fa fa-angle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>
            <div class="objects-right">
                <img class="objects-4 img-fluid wow fadeInRight" data-wow-delay="0.2s" data-wow-duration="2.0s" src="images/objects/04.png" alt="">
                <img class="objects-5 img-fluid wow fadeInRight" data-wow-delay="0.4s" data-wow-duration="2.0s" src="images/objects/05.png" alt="">
            </div>
        </section>

        <!--=================================
        our-service -->

        <!--=================================
        work-process -->

        <section class="split-section black-bg page-section-ptb">
            
            <div class="container">
                <div class="row justify-content-end">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2 class="text-white">We Do Things Different!</h2>
                            <h4 class="text-white title-effect">Our Unique Process </h4>
                            <p class="text-white">Know our process and Possimus delectus ex, harum, quis rerum maxime in magnam. lorem ipsum dolor sit amet, consectetur adipisicing elit. </p>
                        </div>
                        <div class="tab">
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a class="nav-link active"  href="#research-07" data-toggle="tab">Planning</a></li>
                                <li class="nav-item"><a class="nav-link" href="#design-07" data-toggle="tab">Design</a></li>
                                <li class="nav-item"><a class="nav-link" href="#develop-07" data-toggle="tab">Development</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="research-07">
                                    <p class="text-white">We'll go over your goals, styles you like, layouts & color you like and your projects need. Enim expedita sed quia nesciunt incidunt accusamus necessitatibus modi adipis official Dolor sit amet consectetur adipisicing elit. </p>
                                </div>
                                <div class="tab-pane fade" id="design-07">
                                    <p class="text-white">Here we will take everything we learned during planning and create the design that all you want. modi adipis official Dolor sit amet, consectetur adipisicing elit. Vero quod conseqt quibusdam Vero quod conseqt enim. </p>
                                </div>
                                <div class="tab-pane fade" id="develop-07">
                                    <p class="text-white">After we have the look for the projects, we will need to code this. all the functionality in place. voluptatem obcaecati impedit iste fugiat eius iusto harum quaerat quisquam ipsum, alias nihil qui error eaque explicabo.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--=================================
        work-process -->

        <!--=================================
        things differently -->

        <section class="page-section-ptb">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <div class="section-title mb-20">
                            <h6>What we do?</h6>
                            <h2>We do things differently</h2>
                            <p>We are dedicated to providing you with the best experience possible.</p>
                        </div>
                        <p>Creative Digital Agency For Smart Solutions adipisci laudantium, nam quo, delectus cum expossimus fuga magnam id, ipsam. lorem ipsum dolor sit amet, <mark> consectetur adipisicing </mark> elit. Quidem voluptatum ad, excepturi nobis quam quia Lorem ipsum dolor sit amet, consectetur adipisicing elit. </p>
                        <p>Consectetur lorem ipsum dolor sit amet adipisicing elit. Ex, sed! consectetur adipisicing elit.</p>
                    </div>
                    <div class="col-lg-6 col-md-6 sm-mt-40">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 mb-40">
                                <div class="feature-text left-icon mb-30">
                                    <div class="feature-icon">
                                        <span class="ti-menu theme-color" aria-hidden="true"></span>
                                    </div>
                                    <div class="feature-info">
                                        <h5 class="text-back pt-10">Responsive Menu</h5>
                                    </div>
                                </div>
                                <p> Horizontal, vertical left, vertical right, 6 drop down animations, multilevel drop down and much more... </p>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 mb-40">
                                <div class="feature-text left-icon mb-30">
                                    <div class="feature-icon">
                                        <span class="ti-settings theme-color" aria-hidden="true"></span>
                                    </div>
                                    <div class="feature-info">
                                        <h5 class="text-back pt-10">Customizability</h5>
                                    </div>
                                </div>
                                <p> Choose the right color, layout, background pattern and background image with our template...</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 xs-mb-40">
                                <div class="feature-text left-icon mb-30">
                                    <div class="feature-icon">
                                        <span class="ti-layout-column3 theme-color" aria-hidden="true"></span>
                                    </div>
                                    <div class="feature-info">
                                        <h5 class="text-back pt-10">Page layouts options</h5>
                                    </div>
                                </div>
                                <p>Webster has many page layouts options to choose from. Change as per your requirement...</p>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="feature-text left-icon mb-30">
                                    <div class="feature-icon">
                                        <span class="ti-eye theme-color" aria-hidden="true"></span>
                                    </div>
                                    <div class="feature-info">
                                        <h5 class="text-back pt-10">Retina ready</h5>
                                    </div>
                                </div>
                                <p>Webster automatically resize to fit the different screen size and make it look great on all device...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--=================================
        things differently -->

        <!--=================================
        frequently  -->

        <section class="theme-bg pos-r page-section-ptb">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="section-title">
                            <h6 class="text-white">Frequently asked questions</h6>
                            <h2 class="text-white title-effect dark">Have a Question?</h2>
                            <p class="text-white">First look at elit Lorem ipsum dolor consectetur adipisicing elit lorem ipsum dolor sit amet.</p>
                        </div>
                        <p class="text-white">Adipisicing elit lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor, consectetur adipisicing elit.</p>
                        <a class="button button-border white mt-20" href="#"> Ask us now </a>
                    </div>
                    <div class="col-md-6">
                        <div class="accordion animated dark-bg">
                            <div class="acd-group acd-active">
                                <a href="#" class="acd-heading acd-active">01. What is available through the web?</a>
                                <div class="acd-des text-white">Avilable through web is Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip</div>
                            </div>
                            <div class="acd-group">
                                <a href="#" class="acd-heading">02. How do I publish on the Web?</a>
                                <div class="acd-des text-white">You can similique quam corporis sequi adipisicing elit lorem ipsum dolor sit amet, consectetur. Tempora, ab officiis ducimus commodi, id, voluptates suscipit quasi nisi. Qui, explicabo quod laborum alias vero aliquid.</div>
                            </div>
                            <div class="acd-group">
                                <a href="#" class="acd-heading">03. How do I find out what's new on the Web?</a>
                                <div class="acd-des text-white">Web is vast ducimus commodi quibusdam similique quam corporis sequi adipisicing elit lorem ipsum dolor sit amet, consectetur. id, voluptates suscipit quasi nisi. Qui, explicabo quod laborum alias vero aliquid.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--=================================
        frequently  -->


        <!--=================================
        footer -->

        <footer class="footer footer-topbar black-bg">
            <div class="copyright">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <img class="img-fluid"  id="logo-footer"  src="images/logo.png" alt="">
                            <div class="footer-text">
                                <p> &copy;Copyright <span id="copyright"> <script>document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))</script></span> <a href="#"> EQBids Inc. </a> All Rights Reserved </p>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="footer-social">
                                <ul class="text-left text-md-right">
                                    <li class="list-inline-item"><a href=" {{ route('contact') }}">Contact us </a> &nbsp;&nbsp;&nbsp;|</li>
                                    <li class="list-inline-item"><a href=" {{ route('about') }}">About us </a> &nbsp;&nbsp;&nbsp;|</li>
                                    <li class="list-inline-item"><a href=" {{ route('terms') }}">Terms and Conditions </a> &nbsp;&nbsp;&nbsp;|</li>
                                    <li class="list-inline-item"><a href=" {{ route('privacy') }}">Privacy Policy </a> &nbsp;&nbsp;&nbsp;</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!--=================================
         footer -->

    </div>
</div>

<div id="back-to-top"><a class="top arrow" href="#top"><i class="fa fa-angle-up"></i> <span>TOP</span></a></div>


<script type="text/javascript">
    var plugin_path = "{{ asset('').'js/plugins/' }}";
</script>

<script type="text/javascript">
            @if(Session::has('notifications'))
		        <?php $notifications = Session::get('notifications'); ?>
                    var alertify_notifications = [
                        @foreach($notifications as $notification)
                            {
                                text:'{{ isset($notification['text'])?$notification['text']:'' }}',
                                type:'{{ isset($notification['type'])?$notification['type']:'message' }}',
                                wait:{{ isset($notification['wait'])?$notification['wait']:600 }},
                            },
                        @endforeach
                        ];
            @endif
</script>

@stack('before_footer_scripts')

<script src="{{ asset('js/app.js') }}" type="application/javascript"></script>
<script src="{{ asset('js/plugins/mega-menu/mega_menu.js') }}" type="application/javascript"></script>
<!-- REVOLUTION JS FILES -->
<script src="{{ asset('js/revolution/jquery.themepunch.tools.min.js') }}"></script>
<script src="{{ asset('js/revolution/jquery.themepunch.revolution.min.js') }}"></script>

<!-- SLIDER REVOLUTION 5.0 EXTENSIONS  (Load Extensions only on Local File Systems !  The following part can be removed on Server for On Demand Loading) -->
<script src="{{ asset('js/revolution/extensions/revolution.extension.actions.min.js') }}"></script>
<script src="{{ asset('js/revolution/extensions/revolution.extension.carousel.min.js') }}"></script>
<script src="{{ asset('js/revolution/extensions/revolution.extension.kenburn.min.js') }}"></script>
<script src="{{ asset('js/revolution/extensions/revolution.extension.layeranimation.min.js') }}"></script>
<script src="{{ asset('js/revolution/extensions/revolution.extension.migration.min.js') }}"></script>
<script src="{{ asset('js/revolution/extensions/revolution.extension.navigation.min.js') }}"></script>
<script src="{{ asset('js/revolution/extensions/revolution.extension.parallax.min.js') }}"></script>
<script src="{{ asset('js/revolution/extensions/revolution.extension.slideanims.min.js') }}"></script>
<script src="{{ asset('js/revolution/extensions/revolution.extension.video.min.js') }}"></script>

<script src="{{ asset('js/eqbids.js') }}" type="application/javascript"></script>

<script type="application/javascript">
    $(document).ready(function() {
        $("#slider").show().revolution({
            sliderType: "standard",
            sliderLayout: "fullwidth",
            dottedOverlay: "none",
            delay: 9000,
            navigation: {
                keyboardNavigation: "off",
                keyboard_direction: "horizontal",
                mouseScrollNavigation: "off",
                mouseScrollReverse: "default",
                onHoverStop: "off",
                touch: {
                    touchenabled: "on",
                    touchOnDesktop: "off",
                    swipe_threshold: 75,
                    swipe_min_touches: 1,
                    swipe_direction: "horizontal",
                    drag_block_vertical: false
                }
                ,
                arrows: {
                    style: "dione",
                    enable: true,
                    hide_onmobile: true,
                    hide_under: 767,
                    hide_onleave: false,
                    tmp: '',
                    left: {
                        h_align: "left",
                        v_align: "center",
                        h_offset: 0,
                        v_offset: 0
                    },
                    right: {
                        h_align: "right",
                        v_align: "center",
                        h_offset: 0,
                        v_offset: 0
                    }
                }
            },
            visibilityLevels: [1240, 1024, 778, 480],
            gridwidth: 1920,
            gridheight: 900,
            lazyType: "none",
            shadow: 0,
            spinner: "spinner2",
            stopLoop: "off",
            stopAfterLoops: -1,
            stopAtSlide: -1,
            shuffle: "off",
            autoHeight: "off",
            disableProgressBar: "on",
            hideThumbsOnMobile: "off",
            hideSliderAtLimit: 0,
            hideCaptionAtLimit: 0,
            hideAllCaptionAtLilmit: 0,
            debugMode: false,
            fallbacks: {
                simplifyAll: "off",
                nextSlideOnWindowFocus: "off",
                disableFocusListener: false,
            }
        });
    });

</script>

@stack('footer_scripts')

</body>
</html>