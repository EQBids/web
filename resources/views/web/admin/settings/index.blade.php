@extends('web.admin.layout')

@section('content')


    <div class="row">
        <div class="col-lg-12">
            <h1>{{(__("Settings list"))}}.</h1>
        </div>
    </div>

    <div class="row" style="margin-bottom:30px;">
        <div class="col-lg-12">
            <a href="{{route('admin.settings.create')}}" class=" pull-right btn btn-primary">{{__("Create")}}</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <table class="table table-striped table-bordered" id="equipment-table">
                <thead>
                    <tr>
                        <th>{{__("Name")}}</th>
                        <th>{{__("Value")}}</th>
                        <th>{{__("Description")}}</th>
                        <th>{{__("Actions")}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($settings as $setting)
                        <tr>
                            <td>{{$setting->name}}</td>
                            <td>{{$setting->value}}</td>
                            <td>{{$setting->description}}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('admin.settings.edit',$setting->id) }}">{{__('Edit')}}</a>
                                <a class="btn btn-danger" href="{{ route('admin.settings.delete',$setting->id) }}">{{__('Delete')}}</a>
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