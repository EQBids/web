@extends('web.contractor.orders.process.layout')

@section('process_content')
    <h2>{{ __('Your Order so Far...') }}</h2>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="table-responsive">
                <table id="shopping-table" class="table shopping-cart-page">
                    <thead>
                        <tr>
                            <th>
                                {{ __('Product') }}
                            </th>
                            <th>
                                {{ __('Product name') }}
                            </th>
                            <th>
                                {{ __('Categories') }}
                            </th>
                            <th>
                                {{ __('Remove') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($cart != "")
                        @foreach($cart->items as $item)
                            <tr>
                                <td class="image">
                                    <a class="media-link" href="{{ route('contractor.equipment.show',[$item->id]) }}">
                                        <img class="img-fluid" src="{{ asset($item->image_path) }}" alt="">
                                    </a>
                                </td>
                                <td>
                                    <a  href="{{ route('contractor.equipment.show',[$item->id]) }}"> {{ $item->name }} </a>
                                </td>
                                <td>
                                    <ul>
                                        @foreach($item->categories as $category)
                                            <li>{{ $category->name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <button data-equipment-id="{{$item->id}}" class="btn btn-sm btn-danger remove-item-cart d-none" ><i class="fa fa-remove"></i></button>
                                </td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
        <a href="{{ route('contractor.equipment.index') }}" class="btn btn-primary"><i class="fa fa-icon-shopping-cart"></i>{{ __('Add more Equipment') }}</a>
            @if($cart != "")
                <button  class="flush-item-cart btn btn-warning"><i class="fa fa-cart-arrow-down"></i> {{ __('Empty the order') }}</button>
                <a href="{{ route('contractor.orders.process.location') }}" class="btn btn-success"><i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i> {{ __('Continue with Order') }}</a>
            @endif
        </div>
    </div>

@endsection



@push('before_footer_scripts')
    <script type="text/javascript">
        var highlight_url="{{  route('contractor.cart') }}";
    </script>

    <script type="text/javascript">
        var stepwizard_step = 1;
    </script>

@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $('#shopping-table').dataTable();
    </script>
@endpush
