@extends('layouts.app')
@section('title', __('system.users.create.menu'))
@section('content')
    <div class="row">

        <div class="col-xl-12 col-sm-12">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title">{{ __('system.users.create.menu') }}</h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('system.dashboard.menu') }}</a></li>
                                        <li class="breadcrumb-item "><a href="{{ route('restaurant.users.index') }}">{{ __('system.users.title') }}</a></li>
                                        <li class="breadcrumb-item active">{{ __('system.users.create.menu') }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <form autocomplete="off" novalidate="" action="{{ route('restaurant.users.store') }}" id="pristine-valid" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="card-body">
                            @csrf
                            @include('restaurant.users.fields')
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 mt-3">
                                <button class="btn btn-primary" type="submit">{{ __('system.crud.save') }}</button>
                                <a href="{{ route('restaurant.users.index') }}"class="btn btn-secondary">{{ __('system.crud.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- end card -->
            </div>
        </div>
    </div>
@endsection
