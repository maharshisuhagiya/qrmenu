@push('page_css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <style>
        .tblLocations .card {
            box-shadow: 0 0 3px rgb(0 0 0 / 15%);
        }

        body[data-layout-mode="dark"] #data-preview .card {
            background: #0000002e;
        }
    </style>
@endpush
<div class="row tblLocations mt-2 mx-1">
    @forelse ($mainMenu ?? [] as $mainMenuData)
        <div class="col-xxl-3 col-lg-4 col-md-6 table-data" data-id="{{ $mainMenuData->id }}" role="button">
            <i class="fas fa-grip-vertical grid-move-icon"></i>

            <div class="card overflow-hidden h-160px">
                {{ Form::open(['route' => ['restaurant.main_menu.destroy', ['main_menu' => $mainMenuData->id]], 'autocomplete' => 'off', 'class' => 'data-confirm d-grid', 'data-confirm-message' => __('system.main_menu.are_you_sure', ['name' => $mainMenuData->main_menu_name]), 'data-confirm-title' => __('system.crud.delete'), 'id' => 'delete-form_' . $mainMenuData->id, 'method' => 'delete']) }}

                <div class="card-body">

                    <div class="d-flex align-items-top">
                        <div>
                            @if ($mainMenuData->main_menu_image_url != null)
                                <img data-src="{{ $mainMenuData->main_menu_image_url }}" alt="" class="avatar-lg rounded-circle me-2 image-object-cover lazyload">
                            @else
                                <div class="avatar-lg d-inline-block align-middle me-2">
                                    <div class="avatar-title bg-soft-primary text-primary font-size-24 m-0 rounded-circle font-weight-bold">
                                        {{ $mainMenuData->main_menu_image_name }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 ms-3">
                            <h5 class="font-size-15 mb-1"><a href="{{ route('restaurant.foods.index', ['main_menu_id' => $mainMenuData->id]) }}" class="text-dark text-break">{{ $mainMenuData->local_lang_name }}</a></h5>
                            <p class="text-muted mb-0">{{ $mainMenuData->created_at }}</p>
                        </div>
                    </div>

                </div>
                <div class="col-md-12 text-end mb-2 position-absolute w-100 bottom-1">
                    <a role="button" href="{{ route('restaurant.main_menu.edit', ['main_menu' => $mainMenuData->id]) }}" class="btn btn-success btn-sm">
                        <i class="fa fa-pen"></i></a>
                    <button type="submit" class="btn btn-danger btn-sm  me-2"><i class="fa fa-trash"></i></button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    @empty
        <div class="col-md-12 text-center">
            {{ __('system.crud.data_not_found', ['table' => __('system.main_menu.title')]) }}

        </div>
    @endforelse

</div>
@push('page_scripts')
    <script src="{{ asset('assets/cdns/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/cdns/jquery.ui.touch-punch.min.js') }}" integrity="sha512-0bEtK0USNd96MnO4XhH8jhv3nyRF0eK87pJke6pkYf3cM0uDIhNJy9ltuzqgypoIFXw3JSuiy04tVk4AjpZdZw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    </script>
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

                        let id = $(this).data('id');
                        $.ajax({
                            url: "{{ route('restaurant.main_menu.change.position') }}",
                            type: 'post',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'id': id,
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
@endpush
