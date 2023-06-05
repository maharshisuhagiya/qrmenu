@extends('layouts.app')
@section('title', __('system.main_menu.update.menu', ['food_category' => $mainMenu->category_name]))
@section('content')
    <div class="row">

        <div class="col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-mb-8 col-xl-8">
                            <h4 class="card-title">{{ __('system.main_menu.update.menu', ['food_category' => $mainMenu->category_name]) }}</h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                        <li class="breadcrumb-item "><a href="{{ route('restaurant.main_menu.index') }}">{{ __('system.main_menu.menu') }}</a></li>
                                        <li class="breadcrumb-item active">{{ __('system.main_menu.update.menu', ['food_category' => $mainMenu->category_name]) }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 text-end">

                        </div>
                    </div>
                </div>
                {{ Form::model($mainMenu, ['route' => ['restaurant.main_menu.update', $mainMenu->id], 'method' => 'put', 'files' => true, 'id' => 'pristine-valid']) }}
                @if (request()->query->has('back'))
                    <input type="hidden" name="back" value="{{ request()->query->get('back') }}">
                @endif
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                @include('restaurant.main_menu.fields', ['edit' => true])
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 mt-3">
                                <button class="btn btn-primary" type="submit">{{ __('system.crud.save') }}</button>
                                <a href="{{ route('restaurant.main_menu.index') }}"class="btn btn-secondary">{{ __('system.crud.back') }}</a>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}

            </div>
        </div>
    </div>
@endsection
