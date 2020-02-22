@extends('web.contractor.layout')

@section('content')
    <div class="col-lg-12">
        <h1>{{__("Workers affiliated to: ".$office->company_name)}}</h1>

        <div class="row">
            <div class="col-lg-12">
                <table id="dttable" class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($workers))
                        @foreach($workers as $worker)
                            <tr>
                                <td>{{ $worker->first_name . ' ' . $worker->last_name }}</td>
                                <td>{{$worker->name}}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ route('contractor.users.edit',[$worker->user_id]) }}">{{__('Edit')}}</a>
                                    <a class="btn btn-danger btn-sm" href="{{ route('contractor.offices.deleteWorker',[$worker->user_id]) }}">{{__('Remove')}}</a>
                                </td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @if(isset($eligibleWorkers) && count($eligibleWorkers) > 0)
            <div class="row">
                <br>
                <div class="col-lg-12">
                    <h4>{{__("Eligible Workers")}}</h4>
                </div>

                <div class="col-lg-12">

                    <div class="row">
                        <div class="col-lg-12">
                            @include('web.partials.show_errors')
                        </div>
                    </div>
                    {!! Form::open(['method'=>'POST','route'=>['contractor.offices.workers.add',$office->id] ]) !!}
                        <div class="row">
                            <div class="col-lg-8">
                                {{--<h7>{{__("Choose a worker to affiliate to this office")}}</h7>--}}
                                <div class="form-group">
                                    <select name="eligible_worker" id="eligible-worker" class="form-control">
                                        @foreach($eligibleWorkers as $eligibleWorker)
                                            <option value="{{$eligibleWorker->id}}">{{$eligibleWorker->first_name. " ".$eligibleWorker->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-sm">{{__("Add")}}</button>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>

            </div>
        @endif

    </div>
@endsection

@push('footer_scripts')
    <script>
        $(document).ready(function () {
            $('#dttable').dataTable();
            $('#eligible-worker').select2({});
        });
    </script>
@endpush