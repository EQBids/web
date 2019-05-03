@extends('web.admin.layout')

@section('content')

    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <h2>Categories list.</h2>
            </div>
            <div class="col-lg-12 mb-40">
                <a href="{{route('admin.categories.create')}}" class="btn btn-primary pull-right">CREATE</a>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped" id="categories-table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{__("Name")}}</th>
                        <th scope="col">{{__("Parent")}}</th>
                        <th scope="col">{{__("Status")}}</th>
                        <th scope="col">{{__("Actions")}}</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>

                                <td>{{++$loop->index}}</td>
                                <td>{{$category->name}}</td>
                                <td>{{$category->parent? $category->parent->name : '-'}}</td>
                                <td>{{$category->getStatusName()}}</td>
                                <td>
                                    <a href="{{route('admin.categories.edit',$category->id)}}" class="btn btn-primary btn-sm">{{__("Edit")}}</a>
                                    <a href="{{route('admin.categories.delete',$category->id)}}" class="btn btn-danger btn-sm">{{__("Delete")}}</a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script>
        $(function(){
            $('#categories-table').dataTable();
        })
    </script>
@endpush