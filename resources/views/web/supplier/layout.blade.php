@extends('web.public')
@section('title','EQBIDS SUPPLIER')

@section('menu_entries')
    <li><a href="{{ route('suppliers_dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('Supplier\'s Dashboard') }} </a> </li>
@endsection


@section('sections')
    <section class="page-section-ptb">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-xs-1 p-l-0 p-r-0" id="sidebar">
                    <div class="list-group panel">
                        @if(Auth::user()->hasAnyRol(['supplier-superadmin','supplier-admin']))
                            <a href="#menu_suppliers" class="list-group-item collapsed" data-toggle="collapse" data-parent="#sidebar" aria-expanded="false"><i class="fa fa-check-square"></i> <span class="hidden-sm-down">Management</span> </a>
                            <div class="collapse" id="menu_suppliers">

                                <a href="{{ route('supplier.inventory.index') }}" class="list-group-item" ><i class="fa fa-building"></i>{{ __('Inventory') }}</a>
                                <a href="{{ route('supplier.offices.index') }}" class="list-group-item" ><i class="fa fa-building"></i>{{ __('Offices') }}</a>
                                <a href="{{ route('supplier.settings.index') }}" class="list-group-item" ><i class="fa fa-gears"></i>{{ __('Settings') }}</a>
                                <a href="{{ route('supplier.users.index') }}" class="list-group-item" ><i class="fa fa-users"></i>{{ __('Manage users') }}</a>
                            </div>
                        @endif
                        <a href="{{ route('supplier.orders.index') }}" class="list-group-item" ><i class="fa fa-shopping-cart"></i> {{ __('Orders') }}</a>
                        <a href="{{ route('supplier.bids.index') }}" class="list-group-item" ><i class="fa fa-gavel"></i> {{ __('Bids') }}</a>
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

@push('before_footer_scripts')
    <script type="text/javascript">
        var highlight_url="{{  route('suppliers_dashboard') }}";
    </script>
@endpush
