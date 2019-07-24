@extends('web.admin.layout')

@section('content')
    <h2>{{ __('Top 5 - Most Requested Equipments') }}</h2>
    
    @if(isset($items)  )
    <div class="row mt-50 table-responsive" >
        <table class="table table-striped table-bordered" id="report_table">
            <thead>
            <tr>
                <th>{{ __('Equipment') }}</th>
                <th>{{ __('Total') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endsection

