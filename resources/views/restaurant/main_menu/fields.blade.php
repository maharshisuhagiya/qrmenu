<div class="row">
    <div class="col-md-12  form-group">
        @php($lbl_main_menu_image = __('system.fields.main_menu_image'))
        <div class="d-flex align-items-center">
            <input type="file" name="main_menu_image" id="main_menu_image" class="d-none my-preview" accept="image/*" data-pristine-accept-message="{{ __('validation.enum', ['attribute' => strtolower($lbl_main_menu_image)]) }}"
                data-preview='.preview-image'>
            <label for="main_menu_image" class="mb-0">
                <div for="profile-image" class="btn btn-outline-primary waves-effect waves-light my-2 mdi mdi-upload ">
                    <span class="d-none d-lg-inline">{{ $lbl_main_menu_image }}</span>
                </div>
            </label>
            <div class='mx-3 '>
                @if (isset($mainMenu) && $mainMenu->main_menu_image_url != null)
                    <img src="{{ $mainMenu->main_menu_image_url }}" alt="" class="avatar-xl rounded-circle img-thumbnail preview-image">
                @else
                    <div class="preview-image-default">
                        <h1 class="rounded-circle font-size text-white d-inline-block text-bold bg-primary px-4 py-3 ">{{ $restaurant->main_menu_image_name ?? 'C' }}</h1>
                    </div>
                    <img class="avatar-xl rounded-circle img-thumbnail preview-image" style="display: none;" />
                @endif
            </div>
        </div>
        @error('main_menu_image')
            <div class="pristine-error text-help">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        @php($lbl_main_menu_name = __('system.fields.main_menu_name'))

        <div class="mb-3 form-group @error('main_menu_name') has-danger @enderror  @error('restaurant_id') has-danger @enderror">
            <label class="form-label" for="name">{{ $lbl_main_menu_name }} <span class="text-danger">*</span></label>
            {!! Form::text('main_menu_name', null, [
                'class' => 'form-control',
                'id' => 'name',
                'autocomplete' => 'off',
                'placeholder' => $lbl_main_menu_name,
                'required' => 'true',
                'maxlength' => 150,
                'minlength' => 2,
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_main_menu_name)]),
                'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_main_menu_name)]),
            ]) !!}


            @error('main_menu_name')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
            @error('restaurant_id')
                <div class="pristine-error text-help">{{ $message }}</div>
            @enderror

        </div>
    </div>

    <div class="col-md-12">
        @php($lbl_main_menu_description = __('system.fields.main_menu_description'))

        <div class="mb-3 form-group @error('main_menu_description') has-danger @enderror">
            <label class="form-label" for="input-address">{{ $lbl_main_menu_description }}</label>
            {!! Form::textarea('main_menu_description', null, [
                'class' => 'form-control',
                'id' => 'input-address',
                'placeholder' => $lbl_main_menu_description,
                //'minlength' => '5',
                'rows' => 5,
                //'required' => true,
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_main_menu_description)]),
                'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_main_menu_description)]),
            ]) !!}
        </div>
        @error('main_menu_description')
        <div class="pristine-error text-help">{{ $message }}</div>
        @enderror
    </div>

    @foreach (getAllCurrentRestaruentLanguages() as $key => $lang)
        <input type="hidden" name="restaurant_ids[{{ $key }}]" value="{{ auth()->user()->restaurant_id }}">
        <div class=" col-md-6">
            @php($lbl_main_menu_name = __('system.fields.main_menu_name') . ' ' . $lang)

            <div class="mb-3 form-group @error('lang_main_menu_name.' . $key) has-danger @enderror @error('restaurant_ids.' . $key) has-danger @enderror">
                <label class="form-label" for="name">{{ $lbl_main_menu_name }} <span class="text-danger">*</span></label>
                {!! Form::text("lang_main_menu_name[$key]", null, [
                    'class' => 'form-control',
                    'id' => 'name',
                    'autocomplete' => 'off',
                    'placeholder' => $lbl_main_menu_name,
                    'required' => 'true',
                    'maxlength' => 150,
                    'minlength' => 2,
                    'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_main_menu_name)]),
                    'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_main_menu_name)]),
                ]) !!}


                @error('lang_main_menu_name.' . $key)
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
