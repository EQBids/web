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
            <label>{{ __('Company name') }}:</label>
            <input name="company_name" class="form-control"
                data-parsley-maxlength="150"
                   value="{{ old('company_name',isset($user) && $user->contractors->first()?$user->contractors->first()->company_name:'') }}"
            />
        </div>

        <div class="form-group">
            <label>{{ __('Address') }}:</label>
            <textarea name="address" class="form-control"
                      data-parsley-maxlength="200"
            >{{ old('address',isset($user) && $user->contractors->first()?$user->contractors->first()->address:'') }}</textarea>
        </div>

        <div class="form-group">
            <label>{{ __('Country') }}:</label>
            <select class="form-control" name="country_id" id="country" data-parsley-required>
            </select>
        </div>

        <div class="form-group">
            <label>{{ __('State') }}:</label>
            <select class="form-control" name="state_id" id="state">
            </select>
        </div>

        <div class="form-group">
            <label>{{ __('City') }}:</label>
            <select class="form-control" name="city_id" id="city">
            </select>
        </div>

        <div class="form-group">
            <label>{{ __('Zip/Postal Code') }}</label>
            <input type="text" class="form-control zip" name="postal_code"
                   value="{{ old('postal_code',isset($user)?$user->settings['postal_code']:'') }}"
            />
        </div>

        <div class="form-group">
            <label>{{ __('Main Phone') }}:</label>
            <input type="text" class="form-control phone-mask" name="phone"
            data-parlsey-required
                   value="{{ old('phone',isset($user)?$user->phone:'') }}"
            />
        </div>

        <div class="form-group">
            <label>{{ __('Secondary Phone') }}:</label>
            <input type="text" class="form-control phone-mask" name="phone_alt"
                   value="{{ old('phone_alt',isset($user->details->phone_alt)?$user->details->phone_alt:'') }}"
            />
        </div>

        <div class="form-group">
            <label>{{ __('Status') }}</label>
            <select class="fancyselect wide" name="status">
                <option value="0" {{ isset($user) && $user->status=='0'?'selected':'' }}>{{ __('Pending') }}</option>
                <option value="1" {{ isset($user) && $user->status=='1'?'selected':'' }}>{{ __('Active') }}</option>
                <option value="2" {{ isset($user) && $user->status=='2'?'selected':'' }}>{{ __('Inactive') }}</option>
                <option value="3" {{ isset($user) && $user->status=='3'?'selected':'' }}>{{ __('Banned') }}</option>
                <option value="4" {{ isset($user) && $user->status=='4'?'selected':'' }}>{{ __('On approval') }}</option>
                <option value="5" {{ isset($user) && $user->status=='5'?'selected':'' }}>{{ __('Blocked') }}</option>
                <option value="6" {{ isset($user) && $user->status=='6'?'selected':'' }}>{{ __('Away') }}</option>
            </select>
        </div>

@push('footer_scripts')
    @include('web.partials.geo')
    @include('web.partials.zip')
@endpush