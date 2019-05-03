@extends('web.public')

@section('menu_entries')
    <li><a href="{{ route('contractor.equipment.index') }}">{{ __('Equipments') }} </a> </li>
    @if(Auth::check())
        <li class="shpping-cart">
            <a href="{{ route('contractor.cart') }}">{{ __('Shopping cart') }}
                <i class="fa fa-shopping-cart icon"></i>
                <strong id="shopping_cart_count" class="item"></strong>
            </a>
        </li>
    @endif
@endsection

@section('sections')
    <section class="page-section-ptb">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-xs-1 p-l-0 p-r-0" id="sidebar">
                    <div class="list-group panel">
                        <a href="#menu_contractors" class="list-group-item collapsed" data-toggle="collapse" data-parent="#sidebar" aria-expanded="false"><i class="fa fa-check-square"></i> <span class="hidden-sm-down">Management</span> </a>
                        <div class="collapse" id="menu_contractors">
                            <a href="{{ route('contractor.sites.index') }}" class="list-group-item" data-parent="#menu_contractors"><i class="fa fa-building"></i>{{ __('Manage sites') }}</a>
                            <a href="{{ route('contractor.offices.index') }}" class="list-group-item" data-parent="#menu_contractors"><i class="fa fa-map-marker"></i>{{ __('Manage offices') }}</a>
                            @if(Auth::user()->hasAnyRol(['contractor-superadmin','contractor-admin']))
                                <a href="{{ route('contractor.users.index') }}" class="list-group-item" data-parent="#menu_contractors"><i class="fa fa-users"></i>{{ __('Manage users') }}</a>
                            @endif
                        </div>
                        <a href="{{ route('contractor.orders.index') }}" class="list-group-item" data-parent="#sidebar"><i class="fa fa-shopping-cart"></i>{{ __('Orders') }}</a>

                        <a href="{{ route('contractor.suppliers.index') }}" class="list-group-item" data-parent="#menu_contractors"><i class="fa fa-map-marker"></i> {{ __('Suppliers') }}</a>
                    </div>
                </div>
                <div class="col-lg-9">
                    @yield('content')
                </div>
            </div>
        </div>
    </section>

    @yield('more-sections')

@endsection

@prepend('before_footer_scripts')
    @routes('contractor')
    <script type="text/javascript">
       var highlight_url="{{  route('contractors_dashboard') }}";
    </script>

    <script type="application/javascript" src="{{ asset('js/cart.js') }}"></script>
@endprepend
