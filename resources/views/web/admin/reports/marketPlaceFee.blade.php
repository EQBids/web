@extends('web.admin.layout')

@section('content')
    <h2>{{ __('Equipment History') }}</h2>
    <form method="get" action="{{ route('admin.reports.marketPlaceFee') }}">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

       

        

        <div class="row">
            <div class="offset-lg-8 col-lg-4">
                <button type="submit" class="btn btn-primary btn-lg mt-30">{{ __('Filter') }}</button>
               
            </div>
        </div>
    </form>
    @if(isset($items)  )
    <div class="row mt-50 table-responsive" >
        <table class="table table-striped table-bordered" id="report_table">
            <thead>
            <tr>
                <th>{{ __('#Quote') }}</th>
                <th>{{ __('Supplier') }}</th>
                <th>{{ __('Price') }}</th>
                <th>{{ __('Price With Fee') }}</th>
                <th>{{ __('Fee') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->supplier }}</td>
                    <td>{{ $item->amount }}</td>
                    <td>{{ $item->price_w_fee }}</td>
                    <td>{{ $item->fee }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endsection

