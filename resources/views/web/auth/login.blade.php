<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>EQBIDS</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="images/favicon.ico" />

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- font -->
    <link  rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,500,500i,600,700,800,900|Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900">

    <link rel="stylesheet" type="text/css" href="{{ asset("css/revolution/settings.css")}}" media="screen" />
    <!-- Typography -->
    <link rel="stylesheet" type="text/css" href="{{ asset("css/typography.css")}}" />
    <!-- Shortcodes -->
    <link rel="stylesheet" type="text/css" href="{{ asset("css/shortcodes/shortcodes.css")}}" />

    <!-- Style -->
    <link rel="stylesheet" type="text/css" href="{{ asset("css/style.css")}}" />

    <!-- Responsive -->
    <link rel="stylesheet" type="text/css" href="{{ asset("css/responsive.css")}}" />


</head>

<body>

<div class="wrapper">

    <!--=================================
     preloader -->

    <div id="pre-loader">
        <img src="{{ asset('images/pre-loader/loader-06.svg') }}" alt="">
    </div>

    <!--=================================
     preloader -->

    <!--=================================
    login -->

    <section class="login white-bg o-hidden scrollbar">
        <div class="container-fluid p-0">
            <div class="row row-eq-height no-gutter height-100vh">
                <div class="col-lg-6 parallax" style="background-image: url({{ asset('images/China.jpg') }});">
                </div>
                <div class="col-lg-5">
                    <div class="vertical-align full-width">
                        <form class="login-14" action="{{ route('loginRequestPin') }}" method="post" data-parsley-validate>
                            {{ csrf_field() }}
                            <h1>{{ __('EQBIDS ADMIN PANEL') }}</h1>
                            <p class="mb-30">Welcom back, please login to your account.</p>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(app('request')->input('signup'))
                                <div class="alert alert-success">
                                    <p>{{ __('Your account has been created, you can login now') }}</p>
                                </div>
                            @endif

                            <div class="pb-50 clearfix white-bg">
                                <div class="section-field mb-20">
                                    <label class="mb-10" for="name">email* </label>
                                    <input id="email" class="web form-control"
                                           type="text" placeholder="Email" name="email"
                                           data-parsley-required
                                           data-parsley-type="email"
                                    >
                                </div>
                                <button type="submit" class="button">
                                    <span>Request pin</span>
                                    <i class="fa fa-check"></i>
                                </button>
                                
                            </div>
                            <a href="{{ route('home') }}">
                                            <i class="far fa-id-badge"></i>
                                            {{ __('Back To Home') }}
                                        </a>
                            <p class="mt-20 mb-0">Don't have an account?
                                <ul>
                                    
                                    <li><a href="{{ route('signup_contractor') }}">
                                            <i class="far fa-id-badge"></i>
                                            {{ __('Signup as contractor') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('signup_supplier') }}">
                                            <i class="far fa-id-badge"></i>
                                            {{ __('Signup as supplier') }}
                                        </a>
                                    </li>
                                </ul>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--=================================
     login -->

</div>

<script type="text/javascript">
    var plugin_path = "{{ asset('').'js/plugins/' }}";
</script>

<script src="{{ asset('js/app.js') }}" type="application/javascript"></script>
<script src="{{ asset('js/plugins/mega-menu/mega_menu.js') }}" type="application/javascript"></script>
<script src="{{ asset('js/eqbids.js') }}" type="application/javascript"></script>

</body>
</html>

