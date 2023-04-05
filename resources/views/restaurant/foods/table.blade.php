@push('page_css')
    <link rel="stylesheet" href="{{ asset('assets/cdns/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/cdns/magnific-popup.css') }}">
    <style>
        .tblLocations .card {
            box-shadow: 0 0 3px rgb(0 0 0 / 15%);
        }

        body[data-layout-mode="dark"] #data-preview .card {
            background: #0000002e;
        }

        img.mfp-img {
            width: 100%;
        }

        #data-preview .card {
            height: 220px;
        }

        .choices__list.choices__list--dropdown {
            z-index: 15;
        }
    </style>
@endpush
<div class="row tblLocations px-1 pt-3">

    @forelse ($foods ?? [] as $food)

        <div class="col-xxl-3 col-lg-4 col-md-6 table-data" data-food_id="{{ $food->id }}" data-category="{{ request()->query('food_category_id') }}" @if (request()->query->has('food_category_id')) role="button" @endif>
            @if (request()->query->has('food_category_id'))
                <i class="fas fa-grip-vertical grid-move-icon"></i>
            @endif


            <div class="card overflow-hidden">

                {{ Form::open(['route' => ['restaurant.foods.destroy', ['food' => $food->id]], 'class' => 'data-confirm', 'data-confirm-message' => __('system.foods.are_you_sure', ['name' => $food->name]), 'data-confirm-title' => __('system.crud.delete'), 'autocomplete' => 'off', 'id' => 'delete-form_' . $food->id, 'method' => 'delete']) }}

                <div class="card-body">

                    <div class="d-flex align-items-top">
                        <div class="popup-slider " role="button" data-items='{!! json_encode($food->gallery_images_slider_data) !!}'>

                            @if ($food->food_image_url != null)
                                <img data-src="{{ $food->food_image_url }}" alt="" class="avatar-lg rounded-circle me-2 image-object-cover lazyload">
                            @else
                                <div class="avatar-lg d-inline-block align-middle me-2">
                                    <div class="avatar-title bg-soft-primary text-primary font-size-24 m-0 rounded-circle font-weight-bold">
                                        {{ $food->logo_name }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 ms-3 food-size-width">
                            <h5 class="font-size-15 mb-1 "><a class="text-dark text-break">{{ $food->local_lang_name }}</a></h5>
                            <p class="text-muted mb-0 text-truncate">
                                @foreach ($food->food_categories as $c)
                                    <span class="badge font-size-12 bg-soft-info text-info foods-desc">{!! $c->category_name !!}
                                    </span>
                                @endforeach

                            </p>
                        </div>
                    </div>
                    <div class="mt-3 pt-1 w-100 data-description">
                        {{ $food->local_lang_description }}
                    </div>
                </div>

                <div class="position-absolute w-100 bottom-1">
                    <div class="col-md-12 text-end mb-2 px-3 align-items-center justify-content-between gap-1 d-flex ">
                        <div>{{ $food->usd_price }}</div>
                        <div class="d-flex align-items-center gap-1">
                            <a role="button" href="{{ route('restaurant.foods.show', ['food' => $food->id, 'back' => url()->full()]) }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-info"></i></a>
                            <a role="button" href="{{ route('restaurant.foods.edit', ['food' => $food->id, 'back' => url()->full()]) }}" class="btn btn-success btn-sm"><i class="fa fa-pen"></i></a>
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </button>
                        </div>
                    </div>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    @empty
        <div class="col-md-12 text-center">
            {{ __('system.crud.data_not_found', ['table' => __('system.foods.title')]) }}
        </div>
    @endforelse

</div>

@push('page_scripts')
    <script type="text/javascript" src="{{ asset('assets/cdns/jquery.magnific-popup.min.js') }}"></script>

    @if (request()->query->has('food_category_id'))
        <script src="{{ asset('assets/cdns/jquery-ui.js') }}"></script>
        <script src="{{ asset('assets/cdns/jquery.ui.touch-punch.min.js') }}" integrity="sha512-0bEtK0USNd96MnO4XhH8jhv3nyRF0eK87pJke6pkYf3cM0uDIhNJy9ltuzqgypoIFXw3JSuiy04tVk4AjpZdZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



        <script>
            $(function() {
                $(".tblLocations").sortable({
                    cursor: 'pointer',
                    dropOnEmpty: false,
                    start: function(e, ui) {
                        ui.item.addClass("selected");
                    },
                    stop: function(e, ui) {
                        ui.item.removeClass("selected");

                        $(this).find(".table-data").each(function(index) {

                            let food_id = $(this).data('food_id');
                            let category = $(this).data('category');
                            $.ajax({
                                url: "{{ route('restaurant.foods.change.position') }}",
                                type: 'post',
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    'food_id': food_id,
                                    'category': category,
                                    'index': index + 1,

                                },
                                success: function(data) {

                                },
                            });

                        });

                    }
                });
            });
        </script>
    @endif
    <script>
        $(document).find('.popup-slider').each(function() {
            var that = $(this);
            var items = that.data('items');
            $(this).magnificPopup({
                items: items,
                closeBtnInside: false,

                gallery: {
                    enabled: true
                },
                type: 'image' // this is a default type
            });
        })
    </script>

@endpush
