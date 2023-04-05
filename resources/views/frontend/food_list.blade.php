@push('page_css')

    @if(config('app.is_show_display_full_details_model'))
        <style>
            img.mfp-img {
                width: 100%;
            }
        </style>
    @else
        <link rel="stylesheet" href="{{ asset('assets/cdns/magnific-popup.css') }}">
        <style>
            img.mfp-img {
                width: 100%;
            }
        </style>
    @endif

@endpush

@if (isset($foods) && count($foods) > 0)
    @foreach ($foods as $food)
        <div
            class="bg-white dark:bg-secondary/50 rounded-xl shadow-shadowitem hover:shadow-shadowdark transition food-item  "
            style="{{ $style ?? '' }}">
            <a href="javascript:"><img src="{{ $food->food_image_url }}" alt=""
                                       class="w-full rounded-t-xl h-56 object-cover popup-slider"
                                       @if(config('app.is_show_display_full_details_model'))
                                           data-id='{!! $food->id !!}'
                                       @else
                                           data-items='{!! json_encode($food->gallery_images_slider_data) !!}'
                                       @endif
                                       onerror="this.src='{{ asset('assets/images/defult.jpg') }}'"/></a>
            <div class="p-4  ">
                <p class="font-bold dark:text-white name truncate font-title">{{ $food->local_lang_name }}</p>
                <p class="text-neutral font-semibold my-3 text-sm line-clamp-3 dark:text-[#B4C1E0] description cursor-pointer md:line-clamp-5 ">
                    {!! Str::limit($food->local_lang_description, 200, ' <a data-description="'.$food->local_lang_description.'" data-title="'.$food->local_lang_name.'"  type="button"  data-modal-toggle="staticModal" class="view_more text-primary text-sm dark:text-white" data-id='.$food->id.'> '.__('system.fields.view_more').'</a>') !!}</p>
                <div class="flex  items-center  justify-between">
                    <div class="text-primary font-bold text-sm dark:text-white amount"> {{ displayCurrency($food->price) }}</div>
                </div>
            </div>
        </div>
    @endforeach

@else
    <p class="font-bold dark:text-white name truncate not_found"> {{ __('system.messages.food_not_found') }}</p>
@endif

@once
    @if(!config('app.is_show_display_full_details_model'))
        @push('page_js')
            <script type="text/javascript" src="{{ asset('assets/cdns/jquery.magnific-popup.min.js') }}"></script>

            <script>
                $(document).find('.popup-slider').each(function () {
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
    @endif
@endonce
