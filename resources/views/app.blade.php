<!DOCTYPE html>
<html lang="en" class=" js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('head_title','EQBids-Rentals and Purchases Made Easy')</title>

    <!-- Generic Icon -->
    <link rel="shortcut icon" href="{{ asset('images/eqbids---canada-s-construction-marketplace-favicon.ico') }}">

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

    @stack('styles')
</head>
<body>

<div class="wrapper">
    <!-- preloader -->
    <div id="pre-loader">
        <img src="{{ asset('images/pre-loader/loader-06.svg') }}" alt="">
    </div>

    <!--preloader -->
    <!--header -->
    <header id="header" class="header fancy">
        <div class="topbar">
            @yield('topbar')
        </div>
        <div class="menu">
            @yield('menu')
        </div>
    </header>
    <!--header -->
    <!-- rev slider -->
    <section class="rev-slider">
        @yield('slider')
    </section>

    @yield('sections')

    <!--footer -->
    <footer class="footer page-section-pt black-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-sm-6 sm-mb-30">
                    <div class="footer-useful-link footer-hedding">
                        <h6 class="text-white mb-30 mt-10 text-uppercase">Navigation</h6>
                        <ul>
                            <li><a href="/new_website/public">Home</a></li>
                            <li><a href="about">About Us</a></li>
                            <li><a href="contact">Contact Us</a></li>
                            <li><a href="terms">Terms and Conditions</a></li>
                            <li><a href="privacy">Privacy Policy</a></li>


                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 sm-mb-30">
                    <div class="footer-useful-link footer-hedding">
                        <h6 class="text-white mb-30 mt-10 text-uppercase">Useful Link</h6>
                        <ul>
                            <li><a href="login">Login</a></li>
                            <li><a href="signup-contractor">Contractor Signup</a></li>
                            <li><a href="signup-supplier">Supplier Signup</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 xs-mb-30">
                    <h6 class="text-white mb-30 mt-10 text-uppercase">Contact Us</h6>
                    <ul class="addresss-info">
                        <li><i class="fa fa-map-marker"></i> <p>Address: London, ON </p> </li>
                        <li><i class="fa fa-phone"></i> <a href="tel:5196572671 "> <span>519-657-2671</span> </a> </li>
                        <li><i class="fa fa-envelope-o"></i> <a href="mailto:info@eqbids.com"> <span>Email: info@eqbids.com</span> </a> </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <h6 class="text-white mb-30 mt-10 text-uppercase">Subscribe to Our Newsletter</h6>
                    <p>Sign Up to our Newsletter to get the latest news, offers and hear when new markets are opening.</p>
                    <div class="footer-Newsletter">
                        <div id="mc_embed_signup_scroll">
                            <form action="php/mailchimp-action.php" method="POST" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate">
                                <div id="msg"> </div>
                                <div id="mc_embed_signup_scroll_2">
                                    <input id="mce-EMAIL" class="form-control" type="text" placeholder="Email address" name="email1" value="">
                                </div>
                                <div id="mce-responses" class="clear">
                                    <div class="response" id="mce-error-response" style="display:none"></div>
                                    <div class="response" id="mce-success-response" style="display:none"></div>
                                </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                <div style="position: absolute; left: -5000px;">
                                    <input type="text" name="b_b7ef45306f8b17781aa5ae58a_6b09f39a55" tabindex="-1" value="">
                                </div>
                                <div class="clear">
                                    <button type="submit" name="submitbtn" id="mc-embedded-subscribe" class="button button-border mt-20 form-button">  Get notified </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-widget mt-20">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <p class="mt-15"> &copy;Copyright <span id="copyright"> <script>document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))</script></span> <a href="#"> EQBids Inc. </a> All Rights Reserved </p>
                    </div>
                    <div class="col-lg-6 col-md-6 text-left text-md-right">
                        <div class="footer-widget-social">
                            <ul>
                                <li><a href="https://www.facebook.com/EQBids/"target="_blank""><i class="fa fa-facebook"></i></a></li>
                                <li><a href="https://www.instagram.com/eqbids/"target="_blank"><i class="fa fa-instagram"></i></a></li>
                                <li><a href="https://www.twitter.com/eqbids"target="_blank"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="https://www.linkedin.com/company/eqbids/"target="_blank"><i class="fa fa-linkedin"></i> </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div>
<!--footer -->
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
<script src="{{ asset('js/eqbids.js') }}" type="application/javascript"></script>

@stack('footer_scripts')

</body>
</html>