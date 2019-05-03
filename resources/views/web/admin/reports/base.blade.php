@extends('web.admin.layout')

@section('content')
    <h2>{{ $title }}</h2>

    <form method="get" action="{{ $form_action }}">
        @include('web.admin.reports.filter')
    </form>
    <div class="row mt-50">
        <table class="table table-striped table-bordered" id="report_table">
            <thead>
                <tr>
                    @foreach($columns as $column)
                        <th>{{ is_string($column)?$column:$column['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        @foreach($columns as $column)
                            <th>{{ is_string($column)?'':$item->{$column['field']} }}</th>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('footer_scripts')

    <script>
        $(function () {
            $('#report_table').dataTable({
                @if(isset($sort))
                "order": {!! json_encode($sort) !!}
                @endif
            });
        });
    </script>
@endpush