{{--@isset($create)--}}
{{--    @php($deactive = 'style="pointer-events: none;" ')--}}
{{--        @php($disable = 'disable');--}}
{{--@endisset--}}
<style>
    [type=radio]:checked + .select-theme {
        border: 2px solid green !important;
    }
</style>
@if(session()->has('errors'))
    <ul>
        @foreach(  session()->get('errors')->toarray() as $key=> $one)
            <li class="text-danger"><b>{{ __('system.fields.'.$key) }} : </b> {{ current($one) }}</li>
        @endforeach

    </ul>

@endif
<div id="basic-pills-wizard" class="twitter-bs-wizard">
    <ul class="twitter-bs-wizard-nav nav nav-pills nav-justified">
        <li class="nav-item">
            <a href="#seller-details" class="nav-link active" data-toggle="tab">
                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                     data-bs-original-title="{{ __('system.fields.restaurant_details') }}">
                    <i class="bx bx-list-ul"></i>
                </div>
            </a>
        </li>
        <li class="nav-item">
            <a href="#company-document1" class="nav-link  " data-toggle="tab" style="pointer-events: none;">
                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                     data-bs-original-title="{{ __('system.fields.restaurant_image') }}">
                    <i class='bx bx-images'></i>
                </div>
            </a>
        </li>

        <li class="nav-item">
            <a href="#bank-detail" class="nav-link  " data-toggle="tab" style="pointer-events: none;">
                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                     data-bs-original-title="{{ __('system.fields.restaurant_default_theme') }}">
                    <i class='bx bxs-layout'></i>
                </div>
            </a>
        </li>
    </ul>
    <!-- wizard-nav -->

    <div class="tab-content twitter-bs-wizard-tab-content">
        <div class="tab-pane active" id="seller-details" data-validate="someFunction">

            <div class="card">
                <div class="card-header">
                    <div class="text-left">
                        <h5 class="mb-0">{{ __('system.fields.restaurant_details') }}</h5>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-4">
                            @php($lbl_restaurant_name = __('system.fields.restaurant_name'))

                            <div class="mt-3 form-group @error('name') has-danger @enderror">
                                <label class="form-label" for="name">{{ $lbl_restaurant_name }} <span
                                        class="text-danger">*</span></label>
                                {!! Form::text('name', null, [
                                    'class' => 'form-control',
                                    'autocomplete' => 'off',
                                    'id' => 'name',
                                    'placeholder' => $lbl_restaurant_name,
                                    'required' => 'true',
                                    'maxlength' => 255,
                                    'minlength' => 2,
                                    'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_restaurant_name)]),
                                    'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_restaurant_name)]),
                                ]) !!}
                                @error('name')
                                <div class="pristine-error text-help">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            @php($lbl_restaurant_type = __('system.fields.restaurant_type'))
                            <div class="mt-3 form-group @error('type') has-danger @enderror">
                                <label class="form-label" for="restaurant">{{ $lbl_restaurant_type }} <span
                                        class="text-danger">*</span></label>
                                {{ Form::select('type', App\Models\Restaurant::restaurant_type_dropdown(), null, [
                                    'class' => 'form-control form-select',
                                    'id' => 'restaurant_type',
                                    'required' => true,
                                    'data-pristine-required-message' => __('validation.custom.select_required', ['attribute' => strtolower($lbl_restaurant_type)]),
                                ]) }}
                                @error('type')
                                <div class="pristine-error text-help">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            @php($lbl_contact_email = __('system.fields.contact_email'))


                            <div class="mt-3 form-group @error('contact_email') has-danger @enderror">
                                <label class="form-label" for="contact_email">{{ $lbl_contact_email }}</label>

                                {!! Form::email('contact_email', null, [
                                    'class' => 'form-control',
                                    'autocomplete' => 'off',
                                    'id' => 'contact_email',
                                    'placeholder' => $lbl_contact_email,
                                    'data-pristine-email-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_contact_email)]),
                                ]) !!}

                                @error('contact_email')
                                <div class="pristine-error text-help">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            @php($lbl_phone_number = __('system.fields.phone_number'))

                            <div class="mt-3 form-group @error('phone_number') has-danger @enderror">
                                <label class="form-label" for="pristine-phone-valid">{{ $lbl_phone_number }} <span
                                        class="text-danger">*</span></label>

                                {!! Form::text('phone_number', null, [
                                    'class' => 'form-control',
                                    'autocomplete' => 'off',
                                    'id' => 'pristine-phone-valid',
                                    'placeholder' => $lbl_phone_number,
                                    'maxlength' => 15,
                                    'required' => true,
                                    'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_phone_number)]),
                                ]) !!}

                                @error('phone_number')
                                <div class="pristine-error text-help">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @if (isset($create))
                            <div class="col-md-4">
                                @php($lbl_clone_data_into = __('system.fields.clone_data_into'))

                                <div class="mt-3 form-group @error('clone_data_into') has-danger @enderror">
                                    <label class="form-label"
                                           for="pristine-phone-valid">{{ $lbl_clone_data_into }}</label>

                                    {{ Form::select('clone_data_into', ['' => __('system.users.select_restaurant')] + App\Http\Controllers\Restaurant\RestaurantController::getRestaurantsDropdown(), null, [
                                        'class' => 'form-control form-select',
                                        'id' => 'clone_data_into',
                                    ]) }}
                                    @error('clone_data_into')
                                    <div class="pristine-error text-help">{{ $message }}</div>
                                    @enderror

                                </div>
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-md-4">
                                @php($lbl_city = __('system.fields.city'))
                                <div class="mt-3 form-group @error('city') has-danger @enderror">
                                    <label class="form-label" for="input-city">{{ $lbl_city }} <span
                                            class="text-danger">*</span></label>
                                    {!! Form::text('city', null, [
                                        'class' => 'form-control',
                                        'id' => 'input-city',
                                        'autocomplete' => 'off',
                                        'placeholder' => $lbl_city,
                                        'required' => true,
                                        'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_city)]),
                                    ]) !!}

                                </div>
                                @error('city')
                                <div class="pristine-error text-help">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                @php($lbl_state = __('system.fields.state'))
                                <div class="mt-3 form-group @error('state') has-danger @enderror">
                                    <label class="form-label" for="input-state">{{ $lbl_state }} <span
                                            class="text-danger">*</span></label>
                                    {!! Form::text('state', null, [
                                        'class' => 'form-control',
                                        'id' => 'input-state',
                                        'placeholder' => $lbl_state,
                                        'autocomplete' => 'off',
                                        'required' => true,
                                        'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_state)]),
                                    ]) !!}
                                </div>
                                @error('state')
                                <div class="pristine-error text-help">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                @php($lbl_country = __('system.fields.country'))

                                <div class="mt-3 form-group @error('country') has-danger @enderror">
                                    <label class="form-label" for="input-country">{{ $lbl_country }} <span
                                            class="text-danger">*</span></label>
                                    {!! Form::text('country', null, [
                                        'class' => 'form-control',
                                        'id' => 'input-country',
                                        'placeholder' => $lbl_country,
                                        'autocomplete' => 'off',
                                        'required' => true,
                                        'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_country)]),
                                    ]) !!}

                                </div>
                                @error('country')
                                <div class="pristine-error text-help">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                @php($lbl_zip = __('system.fields.zip'))

                                <div class="mt-3 form-group @error('zip') has-danger @enderror">
                                    <label class="form-label" for="input-zip">{{ $lbl_zip }} <span
                                            class="text-danger">*</span></label>
                                    {!! Form::text('zip', null, [
                                        'class' => 'form-control pristine-custom-pattern',
                                        'id' => 'input-zip',
                                        'placeholder' => $lbl_zip,
                                        'custom-pattern' => "^[0-9a-zA-z]{4,8}$",
                                        'required' => true,
                                        'maxlength' => 8,
                                        'autocomplete' => 'off',
                                        'data-pristine-pattern-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_zip)]),
                                        'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_zip)]),
                                    ]) !!}
                                </div>
                                @error('zip')
                                <div class="pristine-error text-help">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                @php($lbl_address = __('system.fields.address'))

                                <div class="mt-3 form-group @error('address') has-danger @enderror">
                                    <label class="form-label" for="input-address">{{ $lbl_address }} <span
                                            class="text-danger">*</span></label>
                                    {!! Form::textarea('address', null, [
                                        'class' => 'form-control',
                                        'id' => 'input-address',
                                        'placeholder' => $lbl_address,
                                        'minlength' => '5',
                                        'required' => true,
                                        'rows' => 2,
                                        'autocomplete' => 'off',
                                        'data-pristine-required-message' => __('validation.required', ['attribute' => strtolower($lbl_address)]),
                                        'data-pristine-minlength-message' => __('validation.custom.invalid', ['attribute' => strtolower($lbl_address)]),
                                    ]) !!}
                                </div>
                                @error('address')
                                <div class="pristine-error text-help">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="pager wizard twitter-bs-wizard-pager-link">
                <li class="previous "><a
                        href="{{ request()->query->get('back', null) ?? route('restaurant.restaurants.index') }}"
                        class="btn btn-secondary">{{ __('system.crud.cancel') }}</a></li>

                <li class="next">
                    <button class="btn btn-primary" type="button">{{ __('system.crud.next') }} <i
                            class="bx bx-chevron-right ms-1"></i></button>
                </li>
            </ul>
        </div>
        <!-- tab pane -->
        {{--        <div class="tab-pane" id="company-document">--}}
        {{--        </div>--}}
        <div class="tab-pane" id="company-document1">
            <div>
                <div class="card">
                    <div class="card-header">
                        <div class="text-left">
                            <h5 class="mb-0">{{ __('system.fields.restaurant_image') }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-stretch">
                            @php($lbl_logo = __('system.fields.app_light_logo'))
                            <div class="col-md-4 form-group ">
                                <label>{{ $lbl_logo }}</label>
                                <div class="d-flex  align-items-center ">
                                    <input type="file" name="logo" id="logo" class="d-none my-preview" accept="image/*"
                                           data-pristine-accept-message="{{ __('validation.enum', ['attribute' => strtolower($lbl_logo)]) }}"
                                           data-preview='.preview-image'>
                                    <label for="logo" class="mb-0">
                                        <div for="profile-image"
                                             class="btn btn-outline-primary waves-effect waves-light my-2 mdi mdi-upload ">
                                            {{ $lbl_logo }}
                                        </div>
                                    </label>
                                    <div class='mx-3 '>
                                        @if (isset($restaurant) && $restaurant->logo_url != null)
                                            <img src="{{ $restaurant->logo_url }}" alt=""
                                                 class="avatar-xl rounded-circle img-thumbnail preview-image">
                                        @else
                                            <div class="preview-image-default">
                                                <h1 class="rounded-circle font-size text-white d-inline-block text-bold bg-primary px-4 py-3 ">{{ $restaurant->logo_name ?? 'R' }}</h1>
                                            </div>
                                            <img class="avatar-xl rounded-circle img-thumbnail preview-image"
                                                 style="display: none;"/>
                                        @endif
                                    </div>
                                </div>
                                @error('logo')
                                <div class="pristine-error text-help">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group ">
                                @php($lbl_logo_ligth = __('system.fields.app_dark_logo'))
                                <label>{{ $lbl_logo_ligth }}</label>
                                <div class="d-flex  align-items-center ">

                                    <input type="file" name="dark_logo" id="dark_logo" class="d-none my-preview"
                                           accept="image/*"
                                           data-pristine-accept-message="{{ __('validation.enum', ['attribute' => strtolower($lbl_logo_ligth)]) }}"
                                           data-preview='.preview-image1'>
                                    <label for="dark_logo" class="mb-0">
                                        <div for="profile-image"
                                             class="btn btn-outline-primary waves-effect waves-light my-2 mdi mdi-upload ">
                                            {{ $lbl_logo_ligth }}
                                        </div>
                                    </label>
                                    <div class='mx-3 '>
                                        @if (isset($restaurant) && $restaurant->dark_logo_url != null)
                                            <img src="{{ $restaurant->dark_logo_url }}" alt=""
                                                 class="avatar-xl rounded-circle img-thumbnail preview-image1 ">
                                        @else
                                            <img class="avatar-xl rounded-circle img-thumbnail preview-image1"
                                                 style="display: none;"/>
                                        @endif
                                    </div>
                                </div>
                                @error('dark_logo')
                                <div class="pristine-error text-help">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group ">

                                @php($lbl_cover_image = __('system.fields.cover_image'))
                                <label>{{ $lbl_cover_image }}</label>
                                <div class="d-flex  align-items-center  ">

                                    <input type="file" name="cover_image" id="cover_image" class="d-none my-preview"
                                           accept="image/*"
                                           data-pristine-accept-message="{{ __('validation.enum', ['attribute' => strtolower($lbl_cover_image)]) }}"
                                           data-preview='.preview-cover_image-image'>
                                    <label for="cover_image" class="mb-0">
                                        <div for="profile-image"
                                             class="btn btn-outline-primary waves-effect waves-light my-2 mdi mdi-upload ">
                                            {{ $lbl_cover_image }}
                                        </div>
                                    </label>
                                    <div class='mx-3 '>
                                        <img class="avatar-xxl  preview-cover_image-image w-100"
                                             @if (isset($restaurant) && $restaurant->cover_image_url != null) src="{{ $restaurant->cover_image_url }}"
                                             @else style="display: none;" @endif />
                                    </div>
                                </div>
                                @error('cover_image')
                                <div class="pristine-error text-help">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="pager wizard twitter-bs-wizard-pager-link">
                    <li class="previous "><a href="javascript: void(0);" class="btn btn-primary" onclick=""><i
                                class="bx bx-chevron-left me-1"></i> {{ __('system.crud.previous') }}</a> <a
                            href="{{ request()->query->get('back', null) ?? route('restaurant.restaurants.index') }}"
                            class="btn btn-secondary">{{ __('system.crud.cancel') }}</a></li>
                    <li class="next  " {!! $deactive ?? ''  !!}>
                        <button class="btn btn-primary" type="button">{{ __('system.crud.next') }} <i
                                class="bx bx-chevron-right ms-1"></i></button>
                    </li>
                </ul>
            </div>
        </div>
        <!-- tab pane -->
        <div class="tab-pane" id="bank-detail">
            <div>
                <div class="card">
                    <div class="card-header">
                        <div class="text-left">
                            <h5 class="mb-0">{{ __('system.fields.restaurant_default_theme') }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            @foreach (getAllThemes() as $theme)

                                <div class="col-xl-3 col-sm-6">
                                    <div class="card  ">
                                        <input type="radio" name="theme" value="{{ strtolower($theme['name']) }}"
                                               id="t{{ $theme['id'] }}"
                                               class="d-none" @checked(strtolower($theme['name']) == strtolower( ($restaurant->theme ?? 'theme1')))>
                                        <div
                                            class="border border-secondary  rounded-3 select-theme">
                                            <div class="row g-0 align-items-center">
                                                <div class="col-md-12">
                                                    <div class="card-body">
                                                        <div class="row ">
                                                            <div class="col-md-6">
                                                                <h5 class="card-title">{{ $theme['name'] }}</h5>
                                                            </div>

                                                            <div class="col-md-6 text-end ">
                                                                <a type="button" target="_blank"
                                                                   class="btn btn-sm btn-secondary mb-md-2"
                                                                   href="{{ route('restaurant.menu', ['restaurant' => $restaurant->id ?? 1, 'restaurant_view' => strtolower($theme['name'])]) }}">{{ __('system.crud.preview') }}</a>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-12">
                                                                <label type="button"
                                                                       class="btn btn-sm btn-primary mb-md-2 w-100 select_lable"
                                                                       for="t{{ $theme['id'] }}">{{ __('system.crud.select') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <img class="card-img img-fluid lazyload"
                                                         data-src="{{ asset($theme['image']) }}" alt="Card image">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card -->
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <ul class="pager wizard twitter-bs-wizard-pager-link">
                    <li class="previous "><a href="javascript: void(0);" class="btn btn-primary" onclick=""><i
                                class="bx bx-chevron-left me-1"></i> {{ __('system.crud.previous') }}</a> <a
                            href="{{ request()->query->get('back', null) ?? route('restaurant.restaurants.index') }}"
                            class="btn btn-secondary">{{ __('system.crud.cancel') }}</a></li>
                    <li class="float-end">
                        <button class="btn btn-primary" type="submit">{{ __('system.crud.save') }}</button>
                    </li>

                </ul>
            </div>
        </div>
        <!-- tab pane -->
    </div>
    <!-- end tab content -->
</div>

@push('page_scripts')
    <script src="{{ asset('assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
    <script>
        var validEle = document.getElementById("pristine-valid");
        var validEle = new Pristine(validEle);
        var page_lode = 1;
        $(document).ready(function () {
            var triggerTabList = [].slice.call(document.querySelectorAll(".twitter-bs-wizard-nav .nav-link"));
            var wizard = $("#basic-pills-wizard").bootstrapWizard({
                tabClass: "nav nav-pills nav-justified",
                onNext: function (tab, navigation, index) {
                    if (!validEle.validate()) {
                        if ($(document).find('.tab-pane.active .has-danger').length != 0) {
                            $(document).find('.tab-pane.active .has-danger:first').focus();
                            tab.next().find('.nav-link').css('pointer-events', ' none')
                            return false;
                        } else {
                            tab.next().find('.nav-link').removeAttr('style')
                        }
                    } else {
                        $(document).find('.nav-link').removeAttr('style')
                    }


                },

                onTabShow: function (tab, navigation, index) {

                    setTimeout(function () {
                        if (!page_lode) {
                            validEle.reset();
                            page_lode = 0;
                        }
                    }, 10)
                }

            });
            triggerTabList.forEach(function (a) {
                var r = new bootstrap.Tab(a);
                a.addEventListener("click", function (a) {
                    a.preventDefault(), r.show()
                });
            });
        });
        var select = '{{ __('system.crud.select') }}';
        var selected = '{{ __('system.crud.selected') }}';
        $(document).on('change', '[type=radio]', function () {
            $('[type=radio] + .select-theme  label').html(select).addClass('btn-primary').removeClass('btn-success');
            $('[type=radio]:checked + .select-theme label').toggleClass('btn-primary btn-success').html(selected);
        })
        $(document).find('[type=radio]:checked').change();
        $(document).on('keypress', '.form-control', function () {
            $(document).find('.nav-item .nav-link.active').parents('.nav-item').nextAll().find('.nav-link').css('pointer-events', 'none')
        })
        $(document).on('change', '.form-select,.my-preview', function () {
            $(document).find('.nav-item .nav-link.active').parents('.nav-item').nextAll().find('.nav-link').css('pointer-events', 'none')
        })
        @if(!isset($create))
        $(document).find('.nav-item .nav-link.active').parents('.nav-item').nextAll().find('.nav-link').css('pointer-events', 'unset')
        @endif

    </script>

@endpush
