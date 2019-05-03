{{ csrf_field() }}
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label for="name">{{__("Name")}}</label>
            <input name="name" type="text" id="name" class="form-control" value="{{ old('name',(isset($industry)?$industry->name:'')) }}" data-parsley-required="true" />
        </div>

        <div class="form-group">
            <label for="parent">{{__("Parent industry")}}</label>
            <?php $old_parent = old('parent',isset($industry)?$industry->parent_id:-1) ?>
            <select name="parent" id="parent" class="form-control">
                <option></option>
                @foreach($industries as $industry)
                    <option value="{{ $industry->id}}" {{ $industry->id==$old_parent?'selected':'' }}>{{$industry->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

@push('footer_scripts')
    <script type="application/javascript">
        $('#parent').select2({

        });
    </script>

@endpush

