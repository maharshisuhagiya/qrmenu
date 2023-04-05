@extends('layouts.app')
@section('title', __('system.dashboard.menu'))
@section('content')
    <div class="row">
        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">{{ __('system.dashboard.menu') }}</h4>


            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <a href="{{ route('restaurant.restaurants.index') }}">
                        <div class="d-flex align-items-center">

                            <div class="flex-grow-1">
                                <span class="text-muted mb-3 lh-1 d-block text-truncate">{{ __('system.dashboard.total_restaurants') }}</span>
                                <h4 class="mb-3">
                                    <span class="counter-value" data-target="{{ $restaurants_count }}">0</span>
                                </h4>
                            </div>

                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <a href="{{ route('restaurant.food_categories.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted mb-3 lh-1 d-block text-truncate">{{ __('system.dashboard.total_categories') }}</span>
                                <h4 class="mb-3">
                                    <span class="counter-value" data-target="{{ $categories_count }}">0</span>
                                </h4>

                            </div>

                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">

            <div class="card card-h-100">

                <div class="card-body">
                    <a href="{{ route('restaurant.foods.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted mb-3 lh-1 d-block text-truncate">{{ __('system.dashboard.total_foods') }}</span>
                                <h4 class="mb-3">
                                    <span class="counter-value" data-target="{{ $foods_count }}">0</span>
                                </h4>

                            </div>

                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">

            <div class="card card-h-100">

                <div class="card-body">
                    <a href="{{ route('restaurant.users.index') }}">

                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted mb-3 lh-1 d-block text-truncate">{{ __('system.dashboard.total_users') }}</span>
                                <h4 class="mb-3">
                                    <span class="counter-value" data-target="{{ $users_count }}">0</span>
                                </h4>

                            </div>

                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @php($class = 'col-xl-6')
        @php($width = '250px')
        @if (auth()->user()->user_type == App\Models\User::USER_TYPE_ADMIN)
            @php($class = 'col-xl-6')
            @php($width = '300px')
        @endif

        <div class="{{ $class }}">
            <div class="card">

                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('system.dashboard.recent_foods') }}</h4>

                </div>

                <div class="card-body px-0 pb-0 pt-2">
                    <div class="table-responsive px-3 h-455" data-simplebar="init">

                        <table class="table align-middle table-nowrap">
                            <tbody>
                                @forelse ($foods as $food)
                                    <tr>
                                        <td class="w-50px">
                                            <div class="avatar-md me-4">

                                                @if ($food->food_image_url != null)
                                                    <img data-src="{{ $food->food_image_url }}" alt="" class="avatar-md rounded-circle me-2 image-object-cover lazyload">
                                                @else
                                                    <div class="avatar-md d-inline-block align-middle me-2">
                                                        <div class="avatar-title bg-soft-primary text-primary font-size-24 m-0 rounded-circle font-weight-bold">
                                                            {{ $food->logo_name }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        <td style="max-width:{{ $width }}">
                                            <div>
                                                <h5 class="font-size-15 text-truncate"><a class="text-dark">{{ $food->local_lang_name }}</a></h5>
                                            </div>
                                        </td>


                                        <td>
                                            <div class="text-end">

                                                <span class="text-muted">{{ $food->created_at }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">
                                            {{ __('system.messages.not_found', ['model' => __('system.foods.title')]) }}
                                            </tdc>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                </div>
                <!-- end card body -->


            </div>
            <!-- end card -->
        </div>
        <div class="{{ $class }}">
            <div class="card">

                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('system.dashboard.recent_categories') }}</h4>

                </div><!-- end card header -->

                <div class="card-body px-0 pb-0 pt-2">
                    <div class="table-responsive px-3 h-455" data-simplebar="init">

                        <table class="table align-middle table-nowrap">
                            <tbody>
                                @forelse ($categories as $foodCategory)
                                    <tr>
                                        <td class="w-50px">
                                            <div class="avatar-md me-4">

                                                @if ($foodCategory->category_image_url != null)
                                                    <img data-src="{{ $foodCategory->category_image_url }}" alt="" class="avatar-md rounded-circle me-2 image-object-cover lazyload">
                                                @else
                                                    <div class="avatar-md d-inline-block align-middle me-2">
                                                        <div class="avatar-title bg-soft-primary text-primary font-size-24 m-0 rounded-circle font-weight-bold">
                                                            {{ $foodCategory->category_image_name }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        <td style="max-width:{{ $width }}" class="">
                                            <div class="text-dark">
                                                <h5 class="font-size-15 text-truncate"><a>{{ $foodCategory->local_lang_name }}</a></h5>
                                            </div>
                                        </td>


                                        <td>
                                            <div class="text-end">

                                                <span class="text-muted">{{ $foodCategory->created_at }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">
                                            {{ __('system.messages.not_found', ['model' => __('system.food_categories.title')]) }}
                                            </tdc>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                </div>
                <!-- end card body -->


            </div>
            <!-- end card -->
        </div>
        @if (auth()->user()->user_type == App\Models\User::USER_TYPE_ADMIN)
            <div class="col-xl-6">
                <div class="card">

                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ __('system.dashboard.recent_restaurants') }}</h4>

                    </div><!-- end card header -->

                    <div class="card-body px-0 pb-0 pt-2">
                        <div class="card-body px-0 pt-2">
                            <div class="table-responsive px-3 h-425px" data-simplebar>
                                <table class="table align-middle table-nowrap">
                                    <tbody>
                                        @forelse ($restaurants  as $restaurant)
                                            <tr>
                                                <td class="w-50px">
                                                    <div class="avatar-md me-4">

                                                        @if ($restaurant->logo_url != null)
                                                            <img data-src="{{ $restaurant->logo_url }}" alt="" class="avatar-md rounded-circle me-2 lazyload">
                                                        @else
                                                            <div class="avatar-md d-inline-block align-middle me-2">
                                                                <div class="avatar-title bg-soft-primary text-primary font-size-18 m-0 rounded-circle font-weight-bold">
                                                                    {{ $restaurant->logo_name }}
                                                                </div>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </td>

                                                <td>
                                                    <div>
                                                        <h5 class="font-size-15"><a class="text-dark">{{ $restaurant->name }}</a></h5>
                                                        <span class="text-muted">{{ $restaurant->phone_number }}</span>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="text-end">

                                                        <span class="text-muted">{{ $restaurant->created_at }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">
                                                    {{ __('system.messages.not_found', ['model' => __('system.restaurants.title')]) }}
                                                    </tdc>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <!-- end card body -->


                </div>
                <!-- end card -->
            </div>

        @endif
        <div class="{{ $class }}">
            <div class="card">

                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ __('system.dashboard.recent_users') }}</h4>

                </div><!-- end card header -->

                <div class="card-body px-0 pb-0 pt-2">
                    <div class="table-responsive px-3 h-455" data-simplebar="init">

                        <table class="table align-middle table-nowrap">
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="w-50px">
                                            <div class="avatar-md me-4">

                                                @if ($user->profile_url != null)
                                                    <img data-src="{{ $user->profile_url }}" alt="" class="avatar-md rounded-circle me-2 image-object-cover lazyload">
                                                @else
                                                    <div class="avatar-md d-inline-block align-middle me-2">
                                                        <div class="avatar-title bg-soft-primary text-primary font-size-18 m-0 rounded-circle font-weight-bold">
                                                            {{ $user->logo_name }}
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                        </td>

                                        <td style="max-width:{{ $width }}">
                                            <div class="">
                                                <h5 class="font-size-15 text-truncate"><a class="text-dark">{{ $user->name }}</a></h5>
                                                <span class="text-muted d-block text-truncate">{{ $user->email }}</span>
                                            </div>
                                        </td>


                                        <td>
                                            <div class="text-end">

                                                <span class="text-muted">{{ $user->created_at }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">
                                            {{ __('system.messages.not_found', ['model' => __('system.users.title')]) }}
                                            </tdc>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                </div>
                <!-- end card body -->


            </div>
            <!-- end card -->
        </div>

    </div>
@endsection
