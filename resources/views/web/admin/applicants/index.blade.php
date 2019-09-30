@extends('web.admin.layout')

@section('content')

    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <h2>{{__("Applicants")}}</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped" id="applicants-table">
                    <thead>
                    <tr>
                        <th>{{ __('Date')}}</th>
                        <th>{{ __('Company name')}}</th>
                        <th>{{ __('Full name')}}</th>
                        <th>{{ __('City') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th scope="col">{{__("Actions")}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr class="{{ $user->status==\App\User::STATUS_REJECTED?'table-danger':'' }}">

                            <td>{{ $user->created_at?$user->created_at->format('Y-m-d H:i'):'' }}</td>
                            <td>{{ isset($user->settings['company_name']) ? $user->settings['company_name'] : '-' }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>
                                @if($user->city)
                                    {{ $user->city->name }}
                                @endif
                            </td>
                            <td>{{ $user->getStatusName() }}</td>
                            <td>
                                <a href="{{route('admin.applicants.view',$user->id)}}" class="btn btn-primary btn-sm">{{__("View")}}</a>
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
            $('#applicants-table').dataTable({
                order:[[0,'desc']]
            });
        })
    </script>
@endpush