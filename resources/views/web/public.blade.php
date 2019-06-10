@extends('app')

@section('title','EQBIDS')

@section('topbar')
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 xs-mb-10">
                <div class="topbar-call text-center text-md-left">
                    <ul>
                        <li><i class="fa fa-envelope-o theme-color"></i> team@eqbids.com</li>
                        <li><i class="fa fa-phone"></i> <a href="tel:+7042791249"> <span>+(123) 456-7890 </span> </a> </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="topbar-social text-center text-md-right">
                    <ul>
                        <li><a href="#"><span class="fa fa-facebook"></span></a></li>
                        <li><a href="#"><span class="fa fa-instagram"></span></a></li>
                        <li><a href="#"><span class="fa fa-google"></span></a></li>
                        <li><a href="#"><span class="fa fa-twitter"></span></a></li>
                        <li><a href="#"><span class="fa fa-linkedin"></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('menu')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <!-- menu start -->
                <nav id="menu" class="mega-menu">
                    <!-- menu list items container -->
                    <section class="menu-list-items" style="height: 92px">
                        <!-- menu logo -->
                        <ul class="menu-logo">
                            <li>
                                <a href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}" alt="logo"> </a>
                            </li>
                        </ul>
                        <!-- menu links -->
                        <div class="menu-bar">
                            <ul class="menu-links">
                                <!--<li><a href="{{ url('/') }}">Home </a> </li>-->
                                @if(Auth::check())
                                    @if(Auth::user()->contractors->count()>0)
                                        <li><a href="{{ route('contractors_dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('Contractor\'s Dashboard') }} </a> </li>
                                    @endif
                                @endif
                                @yield('menu_entries')
                                <li><a href="{{ route('about') }}">About us  </a>
                                  
                                </li>
                                
                                @if(Auth::check())
                                        <li><a href="{{ route('logout') }}" ><i class="fa fa-user"></i> {{__('Logout')}}</a></li>
                                @else
                                    <li><a href="{{ route('show_login') }}" ><i class="fa fa-user"></i> {{ __('Login / Signup') }}</a> </li>
                                @endif
                            </ul>
                        </div>
                    </section>
                </nav>
            </div>
        </div>
    </div>

@endsection

@section('sections')
    <section class="page-section-ptb">
        @yield('content')
    </section>

    @yield('more-sections')

@endsection
