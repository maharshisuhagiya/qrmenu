<div class="gallery_image_hiddens d-none">
    @foreach ($food->gallery_images_with_details ?? [] as $img)
        <input type="hidden" name="gallery_image[]" value="{{ $img['img'] }}" id="img_{{ $img['id'] }}">
    @endforeach
</div>

<div class="row">

    <div class="col-md-4  form-group">
        @php($lbl_food_image = __('system.fields.food_image'))
        <div class="d-flex align-items-center">
            <input type="file" name="food_image" id="food_image" class="d-none my-preview" accept="image/*"
                   data-pristine-accept-message="{{ __('validation.enum', ['attribute' => strtolower($lbl_food_image)]) }}"
                   data-pristine-required-message="{{ __('validation.custom.select_required', ['attribute' => strtolower($lbl_food_image)]) }}"
                   data-preview='.preview-image' @if (!isset($food)) required @endif>
            <label for="food_image" class="mb-0">
                <div for="profile-image" class="btn btn-outline-primary waves-effect waves-light my-2 mdi mdi-upload ">
                    <span class="d-none d-lg-inline"> {{ $lbl_food_image }} <span class="text-danger">*</span> </span>
                </div>
            </label>
            <div class='mx-3 '>
                @if (isset($food) && $food->food_image_url != null)
                    <img src="{{ $food->food_image_url }}" alt=""
                         class="avatar-xl rounded-circle img-thumbnail preview-image">
                @else
                    <div class="preview-image-default">
                        <h1 class="rounded-circle font-size text-white d-inline-block text-bold bg-primary px-4 py-3 ">{{ $food->food_image_name ?? 'F' }}</h1>
                    </div>
                    <img class="avatar-xl rounded-circle img-thumbnail preview-image" style="display: none;"/>
                @endif
            </div>
        </div>

        @error('food_image')
        <p>
        <div class="pristine-error text-help">{{ $message }}</div>
        </p>
        @enderror
        <input type="hidden" name="restaurant_id" value="{{ auth()->user()->restaurant_id }}">

    </div>

</div>

<div class="row mt-3">
    <div class="col-md-4">
        <div class="mb-3 form-group  @error('categories') has-danger @enderror   ">
            @php($lbl_food_category = __('system.fields.food_category'))
            <label class="form-label" for="input-language">{{ $lbl_food_category }} <span
                    class="text-danger">*</span></label>
            @php($categories = App\Http\Controllers\Restaurant\FoodCategoryController::getCurrentRestaurantAllFoodCategories())
            <input type="text" name="categories_select" pristine-value-in="{{ implode(',', array_keys($categories)) }}"
                   value="{{ old('categories_select', implode(',', $food->categories_ids ?? [])) }}"
                   id="choices-multiple-remove-button-ref" required="true" class="pristine-in-validators d-none"
                   data-pristine-required-message="{{ __('validation.custom.select_required', ['attribute' => strtolower($lbl_food_category)]) }}">
            {!! Form::select('categories[]', $categories, old('categories', $food->categories_ids ?? []), [
                'class' => 'form-control choice-picker-multiple w-100 ',
                'id' => 'choices-multiple-remove-button',
                'data-id' => 'choices-multiple-remove-button-ref',
                'multiple' => true,
                'placeholder' => $lbl_food_category,
                'data-remove' => 'false',
            ]) !!}
            @error('categories')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror

        </div>

    </div>

</div>

<div class="row">
    <div class="col-md-4">
        <div class="mb-3 form-group @error('name') has-danger @enderror  @error('restaurant_id') has-danger @enderror">
            @php($lbl_food_name = __('system.fields.food_name'))

            <label class="form-label" for="name">{{ $lbl_food_name }} <span class="text-danger">*</span></label>
            {!! Form::text('name', null, [
                'autocomplete' => 'off',
                'class' => 'form-control',
                'id' => 'name',
                'placeholder' => $lbl_food_name,
                'required' => 'true',
                'maxlength' => 150,
                'minlength' => 2,
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_food_name)]),
                'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_food_name)]),
            ]) !!}
            @error('name')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
            @error('restaurant_id')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        @php($lbl_food_description = __('system.fields.food_description'))

        <div class="mb-3 form-group @error('description') has-danger @enderror">
            <label class="form-label" for="input-address">{{ $lbl_food_description }}</label>
            {!! Form::textarea('description', null, [
                'class' => 'form-control',
                'id' => 'input-address',
                'placeholder' => $lbl_food_description,
                //'minlength' => '5',
                'rows' => 2,
                //'required' => true,
                'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_food_description)]),
                'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_food_description)]),
            ]) !!}
        </div>
        @error('description')
        <div class="pristine-error text-help">{{ $message }}</div>
        @enderror
    </div>
</div>
@foreach (getAllCurrentRestaruentLanguages() as $key => $lang)
    <div class="row">
        <input type="hidden" name="restaurant_ids[{{ $key }}]" value="{{ auth()->user()->restaurant_id }}">
        <div class="col-md-4">
            <div
                class="mb-3 form-group @error('name.' . $key) has-danger @enderror  @error('restaurant_ids.' . $key) has-danger @enderror">
                @php($lbl_food_name = __('system.fields.food_name') . ' ' . $lang)

                <label class="form-label" for="name.{{ $key }}">{{ $lbl_food_name }} <span class="text-danger">*</span></label>
                {!! Form::text("lang_name[$key]", null, [
                    'class' => 'form-control',
                    'id' => 'name.' . $key,
                    'placeholder' => $lbl_food_name,
                    'required' => 'true',
                    'maxlength' => 150,
                    'minlength' => 2,
                    'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_food_name)]),
                    'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_food_name)]),
                ]) !!}
                @error('name.' . $key)
                <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
                @error('restaurant_ids.' . $key)
                <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            @php($lbl_food_description = __('system.fields.food_description') . ' ' . $lang)

            <div class="mb-3 form-group @error('description.' . $key) has-danger @enderror">
                <label class="form-label" for="input-address">{{ $lbl_food_description }}</label>
                {!! Form::textarea("lang_description[$key]", null, [
                    'class' => 'form-control',
                    'id' => 'input-address',
                    'placeholder' => $lbl_food_description,
                    //'minlength' => '5',
                    'rows' => 2,
                    //'required' => true,
                    'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_food_description)]),
                    'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_food_description)]),
                ]) !!}
            </div>
            @error('description.' . $key)
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>

    </div>
@endforeach
<div class="row">
    <div class="col-md-4">
        <div class="mb-3 form-group @error('price') has-danger @enderror">
            @php($lbl_food_price = __('system.fields.food_price'))
            <label class="form-label" for="price-mask">{{ $lbl_food_price }} <span class="text-danger">*</span></label>
            <div class="input-group">
                {!! Form::text('price', null, [
                    'class' => 'form-control price-mask',
                    'id' => 'price-mask',
                    'placeholder' => $lbl_food_price,
                    'required' => true,
                    'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_food_price)]),
                    'data-pristine-email-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_food_price)]),
                ]) !!}
                <div class="input-group-text">{{ config('app.currency_symbol') }}</div>
            </div>


            @error('price')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    </div>

    @if(config('app.is_allergies_field_visible'))
        <div class="col-md-4">
            @php($lbl_allergies = __('system.fields.allergies'))

            <div class="mb-3 form-group @error('allergies') has-danger @enderror">
                <label class="form-label" for="input-address">{{ $lbl_allergies }}</label>
                {!! Form::textarea('allergy', null, [
                    'class' => 'form-control',
                    'id' => 'allergy',
                    'placeholder' => $lbl_allergies,
                    'rows' => 2
                ]) !!}
            </div>
            @error('allergies')
            <div class="pristine-error text-help">{{ $message }}</div>
            @enderror
        </div>
    @endif

    @if(config('app.is_calories_field_visible'))
        <div class="col-md-4">
            @php($lbl_calories = __('system.fields.calories'))

            <div class="mb-3 form-group @error('calories') has-danger @enderror">
                <label class="form-label" for="calories">{{ $lbl_calories }}</label>
                {!! Form::text('calories',null,[
                    'class' => 'form-control',
                    'id' => 'calories',
                    'placeholder' => $lbl_calories,
                ]) !!}
                @error('calories')
                <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>
        </div>
    @endif



    @if(config('app.is_preparation_time_field_visible'))
        <div class="col-md-4">
            @php($lbl_preparation_time = __('system.fields.preparation_time'))

            <div class="mb-3 form-group @error('preparation_time') has-danger @enderror">
                <label class="form-label" for="preparation_time">{{ $lbl_preparation_time }}</label>
                {!! Form::text('preparation_time', isset($food) && $food->preparation_time ? str_replace([' hours ', __('system.fields.minute') ], ['.', ''], $food->preparation_time) : null, [
                    'class' => 'form-control',
                    'id' => 'preparation_time',
                    'placeholder' => $lbl_preparation_time,
                    'data-time_formate' => __('system.fields.minute'),
                    'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_preparation_time)]),
                ]) !!}
                @error('preparation_time')
                <div class="pristine-error text-help">{{ $message }}</div>
                @enderror
            </div>
        </div>

    @endif


    <div class="col-md-4">
        <div class="row">

            <div class="col-mb-6">
                @php($lbl_is_available = __('system.fields.is_available'))
                <div class="mt-4 mt-md-0">
                    <label class="form-label" for="is_available">{{ $lbl_is_available }}</label>
                    <div class="form-check form-switch form-switch-md mb-3">
                        <input type="hidden" name="is_available" value="0">
                        {!! Form::checkbox('is_available', 1, true, [
                            'class' => 'form-check-input',
                            'id' => 'is_available',
                            'placeholder' => $lbl_is_available,
                        ]) !!}
                    </div>

                </div>
            </div>
        </div>

    </div>
    <div class="col-md-12">
        <hr>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Custom fields') }}</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-tabs-custom " role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#eng" role="tab">
                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                            <span class="d-none d-sm-block">{{ __('English') }}</span>
                        </a>
                    </li>
                    @foreach(getAllCurrentRestaruentLanguages() as $key => $lang)
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#lang{{$key}}" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                <span class="d-none d-sm-block">{{ $lang }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                @php($lbl_name = __('system.fields.name'))
                @php($lbl_value = __('system.fields.value'))
                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="eng" role="tabpanel">

                        @if (isset($food) && isset($food->custom_field['en']) && count($food->custom_field['en'] ?? []) > 0 )
                            @foreach($food->custom_field['en'] ?? []  as $key => $first)
                                @if($loop->index ==0 )
                                    <div class="row clone_row mt-2">
                                        <div class="col-4 form-group">
                                            {!! Form::text('key[en][]',  $key, [
                                                'class' => 'form-control',
                                                'placeholder' => $lbl_name,
                                            ]) !!}
                                        </div>
                                        <div class="col-4 form-group">
                                            {!! Form::text('val[en][]',  $first, [
                                                  'class' => 'form-control',
                                                  'placeholder' => $lbl_value,
                                              ]) !!}
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-primary add-row"><span
                                                    class="fa fa-plus"></span></button>
                                            <button type="button" class="btn btn-danger remove-row d-none"><span
                                                    class="fa fa-minus"></span></button>
                                        </div>
                                    </div>
                                @else
                                    <div class="row clone_row mt-2">
                                        <div class="col-4 form-group">
                                            {!! Form::text('key[en][]',  $key, [
                                                'class' => 'form-control',
                                                'placeholder' => $lbl_name,
                                                'required' => true
                                            ]) !!}
                                        </div>
                                        <div class="col-4 form-group">
                                            {!! Form::text('val[en][]',  $first, [
                                                  'class' => 'form-control',
                                                  'placeholder' => $lbl_value,
                                                  'required' => true
                                              ]) !!}
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-primary add-row d-none"><span
                                                    class="fa fa-plus"></span></button>
                                            <button type="button" class="btn btn-danger remove-row "><span
                                                    class="fa fa-minus"></span></button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="row clone_row mt-2">
                                <div class="col-4 form-group">
                                    {!! Form::text('key[en][]',  null, [
                                        'class' => 'form-control',
                                        'placeholder' => $lbl_name,
                                    ]) !!}
                                </div>
                                <div class="col-4 form-group">
                                    {!! Form::text('val[en][]',  null, [
                                          'class' => 'form-control',
                                          'placeholder' => $lbl_value,
                                      ]) !!}
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-primary add-row"><span
                                            class="fa fa-plus"></span></button>
                                    <button type="button" class="btn btn-danger remove-row d-none"><span
                                            class="fa fa-minus"></span></button>
                                </div>
                            </div>
                        @endif
                    </div>
                    @foreach(getAllCurrentRestaruentLanguages() as $key => $lang)
                        <div class="tab-pane" id="lang{{$key}}" role="tabpanel">

                            @if (isset($food) && isset($food->custom_field[$key]) && count($food->custom_field[$key] ?? []) > 0 )
                                @foreach($food->custom_field[$key] ?? []  as $k => $first)
                                    @if($loop->index ==0 )
                                        <div class="row clone_row mt-2">
                                            <div class="col-4 form-group">
                                                {!! Form::text('key['.$key.'][]',  $k, [
                                                    'class' => 'form-control',
                                                    'placeholder' => $lbl_name,
                                                ]) !!}
                                            </div>
                                            <div class="col-4 form-group">
                                                {!! Form::text('val['.$key.'][]',  $first, [
                                                      'class' => 'form-control',
                                                      'placeholder' => $lbl_value,
                                                  ]) !!}
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-primary add-row"><span
                                                        class="fa fa-plus"></span></button>
                                                <button type="button" class="btn btn-danger remove-row d-none"><span
                                                        class="fa fa-minus"></span></button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row clone_row mt-2">
                                            <div class="col-4 form-group">
                                                {!! Form::text('key['.$key.'][]',  $k, [
                                                    'class' => 'form-control',
                                                    'placeholder' => $lbl_name,
                                                    'required' => true
                                                ]) !!}
                                            </div>
                                            <div class="col-4 form-group">
                                                {!! Form::text('val['.$key.'][]',  $first, [
                                                      'class' => 'form-control',
                                                      'placeholder' => $lbl_value,
                                                      'required' => true
                                                  ]) !!}
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-primary add-row d-none"><span
                                                        class="fa fa-plus"></span></button>
                                                <button type="button" class="btn btn-danger remove-row "><span
                                                        class="fa fa-minus"></span></button>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="row clone_row mt-2">
                                    <div class="col-4 form-group">
                                        {!! Form::text('key['.$key.'][]',  null, [
                                            'class' => 'form-control',
                                            'placeholder' => $lbl_name,
                                        ]) !!}
                                    </div>
                                    <div class="col-4 form-group">
                                        {!! Form::text('val['.$key.'][]',  null, [
                                              'class' => 'form-control',
                                              'placeholder' => $lbl_value,
                                          ]) !!}
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-primary add-row"><span
                                                class="fa fa-plus"></span></button>
                                        <button type="button" class="btn btn-danger remove-row d-none"><span
                                                class="fa fa-minus"></span></button>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @endforeach

                </div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div><!-- end col -->


</div>
@push('page_scripts')

    <script>
        $(document).ready(function () {
            var validEle = document.getElementById("pristine-valid");
            var t = new Pristine(validEle);
            $(document).on('click', '.remove-row', function () {
                var currn_row = $(this).parents('.clone_row');
                if (confirm('{{ __('Are you sure to remove?') }}')) {
                    currn_row.remove();
                }
                t.validate();
                setTimeout(function () {
                    if ($('.tab-pane.active').find('.has-danger').length > 0) {
                        $('#pristine-valid .nav-link').addClass('disabled');
                    } else {
                        $('#pristine-valid .nav-link').removeClass('disabled');
                    }
                }, 100)
                return false;
            })
            $(document).on('click', '.add-row', function () {
                var currn_row = $(this).parents('.clone_row');
                var clons = currn_row.clone();
                clons.find('.add-row').addClass('d-none');
                clons.find('.remove-row').removeClass('d-none');
                clons.find('input').attr('data-pristine-required', 'data-pristine-required');

                clons.find('input').val('');
                var par = currn_row.parents('.tab-pane');
                $(par).append(clons);
                var t = new Pristine(validEle);
                t.reset();
                t.validate();

                if ($(par).find('.has-danger').length > 0) {
                    // console.log('addd')
                    $('#pristine-valid .nav-link').addClass('disabled');
                } else {
                    // console.log('remove')
                    $('#pristine-valid .nav-link').removeClass('disabled');
                }
            })
            $(document).on('blur', '.tab-pane.active input', function () {
                // $(this).keypress();
                t.validate();
                setTimeout(function () {
                    if ($('.tab-pane.active').find('.has-danger').length > 0) {
                        $('#pristine-valid .nav-link').addClass('disabled');
                    } else {
                        $('#pristine-valid .nav-link').removeClass('disabled');
                    }
                }, 100)


            })

        })

    </script>
@endpush
