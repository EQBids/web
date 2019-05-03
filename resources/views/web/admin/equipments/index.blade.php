@extends('web.admin.layout')

@section('content')


    <div class="row">
        <div class="col-lg-12">
            <h1>{{(__("Equipment list"))}}.</h1>
        </div>
    </div>

    <div class="row" style="margin-bottom:30px;">
        <div class="col-lg-12">
            <a href="{{route('admin.equipment.create')}}" class=" pull-right btn btn-primary">{{__("Create")}}</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <table class="table table-striped table-bordered" id="equipment-table">
                <thead>
                    <tr>
                        <th>{{__("Name")}}</th>
                        <th>{{__("Category")}}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{__("Actions")}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipments as $equipment)
                        <tr>
                            <td>{{$equipment->name}}</td>
                            <td>{{!$equipment->categories->isEmpty() ? $equipment->categories->first()->name : '-'}}</td>
                            <td>{{ $equipment->getStatusName() }}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('admin.equipment.edit',$equipment->id) }}">{{__('Edit')}}</a>
                                <a class="btn btn-danger" href="{{ route('admin.equipment.delete',$equipment->id) }}">{{__('Delete')}}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('footer_scripts')

    <script>
        $(function () {
            $('#equipment-table').dataTable({});
        });
    </script>
@endpush