<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/18/18
 * Time: 11:40 AM
 */
?>

@extends('app')
@section('title','EQBIDS ADMIN')

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
                                <li><a href="{{ route('admin.dashboard') }}">Dashboard </a> </li>
                                <li><a href="{{ route('admin.equipment.listing.index') }}">{{ __('Equipments') }} </a> </li>
                                @if(Auth::check())
                                    <li><a href="{{ route('admin.logout') }}" ><i class="fa fa-user"></i> {{__('Logout')}}</a></li>
                                @else
                                    <li><a href="{{ route('admin.login') }}" ><i class="fa fa-user"></i> {{ __('Login / Signup') }}</a> </li>
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
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-xs-1 p-l-0 p-r-0" id="sidebar">
                    <div class="list-group panel">
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item" ><i class="fa fa-dashboard"></i>{{__('Admin Dashboard')}}</a>
                        <a href="#menu_contractors" class="list-group-item collapsed" data-toggle="collapse" data-parent="#sidebar" aria-expanded="false"><i class="fa fa-check-square"></i> <span class="hidden-sm-down">Contractors</span> </a>
                        <div class="collapse" id="menu_contractors">
                            <a class="list-group-item" href="{{ route('admin.sites.index') }}" data-parent="#menu_contractors"><i class="fa fa-building"></i>{{ __('Sites') }}</a>
                        </div>

                        <a href="#menu_equipment" class="list-group-item collapsed" data-toggle="collapse" data-parent="#sidebar" aria-expanded="false"><i class="fa fa-circle-o"></i> <span class="hidden-sm-down">Equipment</span> </a>
                        <div class="collapse" id="menu_equipment">
                            <a class="list-group-item" href="{{ route('admin.categories.index') }}" data-parent="#menu_equipment"><i class="fa fa-map-pin"></i> {{ __('Categories') }}</a>
                            <a class="list-group-item" href="{{ route('admin.equipment.index') }}" data-parent="#menu_equipment"><i class="fa fa-building-o" aria-hidden="true"></i> {{ __('Manage Equipment') }}</a>
                            <a class="list-group-item" href="{{ route('admin.materials.index') }}" data-parent="#menu_equipment"><i class="fa fa-boxes" aria-hidden="true"></i> {{ __('Manage Materials') }}</a>
                        </div>

                        <a href="#menu_reports" class="list-group-item collapsed" data-toggle="collapse" data-parent="#sidebar" aria-expanded="false"><i class="fa fa-file"></i> <span class="hidden-sm-down">Reports</span> </a>
                        <div class="collapse" id="menu_reports">
                            <a class="list-group-item" href="{{ route('admin.reports.contractors') }}" data-parent="#menu_reports"><i class="fa fa-circle"></i>{{ __('Contractors') }}</a>
                            <a class="list-group-item" href="{{ route('admin.reports.equipments') }}" data-parent="#menu_reports"><i class="fa fa-circle"></i>{{ __('Equipments') }}</a>
                            <a class="list-group-item" href="{{ route('admin.reports.suppliers') }}" data-parent="#menu_reports"><i class="fa fa-circle"></i>{{ __('Suppliers') }}</a>
                            <a class="list-group-item" href="{{ route('admin.reports.quotes') }}" data-parent="#menu_reports"><i class="fa fa-circle"></i>{{ __('Quotes') }}</a>
                            <a class="list-group-item" href="{{ route('admin.reports.quotesStatus') }}" data-parent="#menu_reports"><i class="fa fa-circle"></i>{{ __('Quotes Status') }}</a>
                            <a class="list-group-item" href="{{ route('admin.reports.whoQuoted') }}" data-parent="#menu_reports"><i class="fa fa-circle"></i>{{ __('Who Quoted') }}</a>
                            <a class="list-group-item" href="{{ route('admin.reports.topEquipmentRequests') }}" data-parent="#menu_reports"><i class="fa fa-circle"></i>{{ __('Top Equipment Requests') }}</a>
                            <a class="list-group-item" href="{{ route('admin.reports.equipmentHistory') }}" data-parent="#menu_reports"><i class="fa fa-circle"></i>{{ __('Equipment History') }}</a>
                            <a class="list-group-item" href="{{ route('admin.reports.marketPlaceFee') }}" data-parent="#menu_reports"><i class="fa fa-circle"></i>{{ __('Market Place Fee') }}</a>
                        </div>

                        <a href="{{ route('admin.orders.index') }}" class="list-group-item" ><i class="fa fa-list-ul"></i> {{__('Orders')}}</a>
                        <a href="{{ route('admin.users.index') }}" class="list-group-item" ><i class="fa fa-users"></i>{{__('Users')}}</a>

                        <a href="{{ route('admin.applicants.index') }}" class="list-group-item" ><i class="fa fa-users"></i>{{__('Applicants')}}</a>
                        <a href="{{ route('admin.industries.index') }}" class="list-group-item" ><i class="fa fa-building-o"></i>{{__('Industries')}}</a>
                        <a href="{{ route('admin.settings.index') }}" class="list-group-item" ><i class="fa fa-gears"></i>{{__('Settings')}}</a>
                    </div>
                </div>
                <div class="col-md-9">
                    @yield('content')
                </div>
            </div>
        </div>
    </section>

    @yield('more-sections')

@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset("css/eqbids.css")}}" />
@endpush


