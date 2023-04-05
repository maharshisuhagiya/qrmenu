@php($languages_array = getAllLanguages(true))
@extends('layouts.app', ['languages_array' => $languages_array])
@section('title', __('system.environment.menu'))
@section('content')
    <div class="row">

        <div class="col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title">{{ __('system.environment.display') }}</h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a
                                                href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                        <li class="breadcrumb-item active">{{ __('system.environment.display') }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form autocomplete="off" novalidate=""
                      action="{{ route('restaurant.environment.setting.display.update') }}" id="pristine-valid"
                      method="post" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{url('environment/setting')}}">
                                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                            <span
                                                class="d-none d-sm-block">{{ __('system.environment.application') }} {{ __('system.environment.title') }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{url('environment/setting/email')}}">
                                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                            <span class="d-none d-sm-block">{{ __('system.environment.email') }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="{{url('environment/setting/display')}}">
                                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                            <span
                                                class="d-none d-sm-block">{{ __('system.environment.display') }}</span>
                                        </a>
                                    </li>
                                </ul>
                                @method('put')
                                @csrf
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                @php($lbl_is_available = __('system.fields.display_language_text'))
                                                <div class="mt-4 mt-md-0">
                                                    <label class="form-label"
                                                           for="display_language">{{ $lbl_is_available }}</label>
                                                    <div class="form-check form-switch form-switch-md mb-3">
                                                        <input type="hidden" name="display_language" value="0">
                                                        {!! Form::checkbox('display_language', 1, config('app.display_language'), [
                                                            'class' => 'form-check-input',
                                                            'id' => 'display_language'
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                @php($dark_light_text = __('system.fields.dark_light_text'))
                                                <div class="mt-4 mt-md-0">
                                                    <label class="form-label"
                                                           for="dark_light_change">{{ $dark_light_text }}</label>
                                                    <div class="form-check form-switch form-switch-md mb-3">
                                                        <input type="hidden" name="dark_light_change" value="0">
                                                        {!! Form::checkbox('dark_light_change', 1, config('app.dark_light_change'), [
                                                            'class' => 'form-check-input',
                                                            'id' => 'dark_light_change'
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                @php($direction_text = __('system.fields.direction_text'))
                                                <div class="mt-4 mt-md-0">
                                                    <label class="form-label"
                                                           for="direction_change">{{ $direction_text }}</label>

                                                    <div class="form-check form-switch form-switch-md mb-3">
                                                        <input type="hidden" name="direction_change" value="0">
                                                        {!! Form::checkbox('direction_change', 1, config('app.direction_change'), [
                                                            'class' => 'form-check-input',
                                                            'id' => 'direction_change'
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                @php($is_allergies_field_visible = __('system.fields.is_allergies_field_visible'))
                                                <div class="mt-4 mt-md-0">
                                                    <label class="form-label"
                                                           for="is_allergies_field_visible">{{ $is_allergies_field_visible }}</label>
                                                    <div class="form-check form-switch form-switch-md mb-3">
                                                        <input type="hidden" name="is_allergies_field_visible" value="0">
                                                        {!! Form::checkbox('is_allergies_field_visible', 1, config('app.is_allergies_field_visible'), [
                                                            'class' => 'form-check-input',
                                                            'id' => 'is_allergies_field_visible'
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                @php($is_calories_field_visible = __('system.fields.is_calories_field_visible'))
                                                <div class="mt-4 mt-md-0">
                                                    <label class="form-label"
                                                           for="is_calories_field_visible">{{ $is_calories_field_visible }}</label>
                                                    <div class="form-check form-switch form-switch-md mb-3">
                                                        <input type="hidden" name="is_calories_field_visible" value="0">
                                                        {!! Form::checkbox('is_calories_field_visible', 1, config('app.is_calories_field_visible'), [
                                                            'class' => 'form-check-input',
                                                            'id' => 'is_calories_field_visible'
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                @php($is_preparation_time_field_visible = __('system.fields.is_preparation_time_field_visible'))
                                                <div class="mt-4 mt-md-0">
                                                    <label class="form-label"
                                                           for="is_preparation_time_field_visible">{{ $is_preparation_time_field_visible }}</label>
                                                    <div class="form-check form-switch form-switch-md mb-3">
                                                        <input type="hidden" name="is_preparation_time_field_visible" value="0">
                                                        {!! Form::checkbox('is_preparation_time_field_visible', 1, config('app.is_preparation_time_field_visible'), [
                                                            'class' => 'form-check-input',
                                                            'id' => 'is_preparation_time_field_visible'
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                @php($is_show_display_full_details_model = __('system.fields.is_show_display_full_details_model'))
                                                <div class="mt-4 mt-md-0">
                                                    <label class="form-label"
                                                           for="is_show_display_full_details_model">{{ $is_show_display_full_details_model }}</label>
                                                    <div class="form-check form-switch form-switch-md mb-3">
                                                        <input type="hidden" name="is_show_display_full_details_model" value="0">
                                                        {!! Form::checkbox('is_show_display_full_details_model', 1, config('app.is_show_display_full_details_model'), [
                                                            'class' => 'form-check-input',
                                                            'id' => 'is_show_display_full_details_model'
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                @php($display_banner_text = __('system.fields.display_banner_text'))
                                                <div class="mt-4 mt-md-0">
                                                    <label class="form-label"
                                                           for="is_show_display_full_details_model">{{ $display_banner_text }}</label>
                                                    <div class="form-check form-switch form-switch-md mb-3">
                                                        <input type="hidden" name="show_banner" value="0">
                                                        {!! Form::checkbox('show_banner', 1, config('app.show_banner'), [
                                                            'class' => 'form-check-input',
                                                            'id' => 'show_banner'
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                @php($display_restaurant_name = __('system.fields.display_restaurant_name'))
                                                <div class="mt-4 mt-md-0">
                                                    <label class="form-label"
                                                           for="is_show_display_full_details_model">{{ $display_restaurant_name }}</label>
                                                    <div class="form-check form-switch form-switch-md mb-3">
                                                        <input type="hidden" name="show_restaurant_name" value="0">
                                                        {!! Form::checkbox('show_restaurant_name', 1, config('app.show_restaurant_name'), [
                                                            'class' => 'form-check-input',
                                                            'id' => 'show_restaurant_name'
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top text-muted">
                        <div class="row">
                            <div class="col-12 mt-3">

                                <button class="btn btn-primary" type="submit">{{ __('system.crud.save') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- end card -->
            </div>
        </div>
    </div>
@endsection
