@extends('web.admin.layout')

@section('content')


    <div class="row">
        <div class="col-lg-12">
            <h1>{{(__("Materials list"))}}.</h1>
        </div>
    </div>

    <div class="row" style="margin-bottom:30px;">
        <div class="col-lg-12">
            <a href="{{route('admin.materials.create')}}" class=" pull-right btn btn-primary">{{__("Create")}}</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <table class="table table-striped table-bordered" id="material-table">
                <thead>
                    <tr>
                        <th>{{__("Name")}}</th>
                        <th>{{__("Category")}}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{__("Actions")}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materials as $material)
                        <tr>
                            <td>{{$material->name}}</td>
                            <td>{{!$material->categories->isEmpty() ? $material->categories->first()->name : '-'}}</td>
                            <td>{{ $material->getStatusName() }}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('admin.materials.edit',$material->id) }}">{{__('Edit')}}</a>
                                <a class="btn btn-danger" href="{{ route('admin.materials.delete',$material->id) }}">{{__('Delete')}}</a>
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
            $('#material-table').dataTable({});
        });
    </script>
@endpush