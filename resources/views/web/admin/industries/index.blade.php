@extends('web.admin.layout')

@section('content')
    <h2>{{ __('Industries') }}</h2>

    <div class="row mb-30" >
        <div class="col-lg-12">
            <a href="{{route('admin.industries.create')}}" class=" pull-right btn btn-primary">{{__("Create")}}</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <table class="table table-bordered table-striped" id="table">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Parent industry') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($industries as $industry)
                        <tr>
                            <td>{{ $industry->name }}</td>
                            <td>{{ $industry->parent?$industry->parent->name:'' }}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('admin.industries.edit',$industry->id) }}">{{__('Edit')}}</a>
                                <a class="btn btn-danger" href="{{ route('admin.industries.delete',$industry->id) }}">{{__('Delete')}}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection


@push('footer_scripts')

    <script type="text/javascript">
        $('#table').dataTable({});
    </script>
@endpush