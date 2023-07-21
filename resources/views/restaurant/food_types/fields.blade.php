<div class="row">
    <div class="col-md-12  form-group">
        @php($lbl_food_types_image = __('system.fields.food_types_image'))
        <div class="d-flex align-items-center">
            <input type="file" name="food_types_image" id="food_types_image" class="d-none my-preview" accept="image/*" data-pristine-accept-message="{{ __('validation.enum', ['attribute' => strtolower($lbl_food_types_image)]) }}"
                data-preview='.preview-image'>
            <label for="food_types_image" class="mb-0">
                <div for="profile-image" class="btn btn-outline-primary waves-effect waves-light my-2 mdi mdi-upload ">
                    <span class="d-none d-lg-inline">{{ $lbl_food_types_image }}</span>
                </div>
            </label>
            <div class='mx-3 '>
                @if (isset($foodTypes) && $foodTypes->food_types_image_url != null)
                    <img src="{{ $foodTypes->food_types_image_url }}" alt="" class="avatar-xl rounded-circle img-thumbnail preview-image">
                @else
                    <div class="preview-image-default">
                        <h1 class="rounded-circle font-size text-white d-inline-block text-bold bg-primary px-4 py-3 ">{{ $restaurant->food_types_image_name ?? 'C' }}</h1>
                    </div>
                    <img class="avatar-xl rounded-circle img-thumbnail preview-image" style="display: none;" />
                @endif
            </div>
        </div>
        @error('food_types_image')
            <div class="pristine-error text-help">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        @php($lbl_food_types_name = __('system.fields.food_types_name'))

        <div class="mb-3 form-group @error('food_types_name') has-danger @enderror  @error('restaurant_id') has-danger @enderror">
            <label class="form-label" for="name">{{ $lbl_food_types_name }} <span class="text-danger">*</span></label>
            {!! Form::text('food_types_name', null, [
                'class' => 'form-control',
                'id' => 'name',
                'autocomplete' => 'off',
                'placeholder' => $lbl_food_types_name,
                'required' => 'true',
                'maxlength' => 150,
                'minlength' => 2,
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_food_types_name)]),
                'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_food_types_name)]),
            ]) !!}


            @error('food_types_name')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
            @error('restaurant_id')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror

        </div>
    </div>

    @foreach (getAllCurrentRestaruentLanguages() as $key => $lang)
        <input type="hidden" name="restaurant_ids[{{ $key }}]" value="{{ auth()->user()->restaurant_id }}">
        <div class=" col-md-6">
            @php($lbl_food_types_name = __('system.fields.food_types_name') . ' ' . $lang)

            <div class="mb-3 form-group @error('lang_food_types_name.' . $key) has-danger @enderror @error('restaurant_ids.' . $key) has-danger @enderror">
                <label class="form-label" for="name">{{ $lbl_food_types_name }} <span class="text-danger">*</span></label>
                {!! Form::text("lang_food_types_name[$key]", null, [
                    'class' => 'form-control',
                    'id' => 'name',
                    'autocomplete' => 'off',
                    'placeholder' => $lbl_food_types_name,
                    'required' => 'true',
                    'maxlength' => 150,
                    'minlength' => 2,
                    'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_food_types_name)]),
                    'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_food_types_name)]),
                ]) !!}


                @error('lang_food_types_name.' . $key)
                    <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
                @error('restaurant_ids.' . $key)
                    <div class="pristine-error text-help">{{ $message }}</div>
                @enderror

            </div>
        </div>
    @endforeach



    <input type="hidden" name="restaurant_id" value="{{ auth()->user()->restaurant_id }}">
    @if (isset($edit))
        <input type="hidden" name="action" value="edit">
    @endif
</div>
