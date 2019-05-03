@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{ csrf_field() }}

    <div class="form-group">
        <label>Email:</label>
        <input name="email" class="form-control" value="{{ old('email',isset($user)?$user->email:'') }}" data-parsley-required data-parsley-type="email" />
    </div>

    <div class="form-group">
        <label>Role:</label>
        <select name="role" class="fancyselect wide">
            @foreach($roles as $role)
                <option {{ old('role',isset($user) && $user->rols()->first() ?$user->rols()->first()->id:'')==$role->id?'selected':'' }} value="{{ $role->id }}">{{__($role->name)}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>{{__('First name')}}:</label>
        <input type="text" name="first_name" class="form-control"
               data-parsley-required
               data-parsley-maxlength="100"
               value="{{ old('first_name',isset($user)?$user->first_name:'') }}"
        />
    </div>

    <div class="form-group">
        <label>{{__('Last name')}}:</label>
        <input type="text" name="last_name"
               class="form-control"
               data-parsley-required
               data-parsley-maxlength="100"
               value="{{ old('last_name',isset($user)?$user->last_name:'') }}"
        />
    </div>

    <div class="form-group">
        <label>{{ __('Office') }}</label>
        <select class="fancyselect wide" name="office_id">
            @foreach($offices as $office)
                <option value="{{ $office->id }}" {{ isset($user) && $user->contractor && $user->contractor->id==$office->id?'selected':'' }}>{{ $office->company_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>{{ __('Status') }}</label>
        <select class="fancyselect wide" name="status">
            <option value="1" {{ isset($user) && $user->status=='1'?'selected':'' }}>{{ __('Active') }}</option>
            <option value="2" {{ isset($user) && $user->status=='2'?'selected':'' }}>{{ __('Inactive') }}</option>
        </select>
    </div>

@push('footer_scripts')
    @include('web.partials.geo')
    @include('web.partials.zip')
@endpush