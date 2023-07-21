@extends('frontend.master')
@section('content')
    <section class="container">
        <div class="flex md:justify-end justify-center pt-14 pb-8">
        </div>
        <div class="food_categories_data_append">
            @include('frontend.theme1.food_categories')
        </div>
        <p class="font-bold dark:text-white name truncate not_found" style="display:none;"> {{ __('system.messages.food_not_found') }}</p>
    </section>
@endsection
@push('page_js')
    <script>
        $(document).ready(function() {
            function serch(search) {
                search = search.toLowerCase();
                $(".category,.food-item").show();
                if (search == '') {

                    $(".category,.food-item").show();
                    $(document).find(".not_found").hide();
                } else {
                    $(document).find(".not_found").hide();
                    search = search.replace('\\', '\\\\');
                    console.log(search);
                    var x = 0;
                    $(document).find('.category').each(function() {
                        var title = $(this).find('.title').html()
                        title = title.toLowerCase();
                        if (title.search(search) == -1) {
                            var hide = 0;

                            $(this).find('.food-item').each(function() {
                                var val1 = $(this).find('.name').html(),
                                    val2 = $(this).find('.amount').html();
                                val3 = $(this).find('.description').html();
                                if (val1)
                                    val1 = val1.toLowerCase();
                                else
                                    val1 = "";
                                if (val2)
                                    val2 = val2.toLowerCase();
                                else
                                    val2 = "";
                                if (val3)
                                    val3 = val3.toLowerCase();
                                else
                                    val3 = "";

                                if (val1.search(search) == -1 && val2.search(search) == -1 && val3
                                    .search(search) == -1) {
                                    $(this).hide();
                                    hide++;
                                }
                            });
                            if (hide == $(this).find('.food-item').length) {
                                $(this).hide();
                                x++;
                            }
                        }
                    })

                    if (x == $(document).find('.category').length) {
                        $(document).find(".not_found").show();
                    } else {
                        $(document).find(".not_found").hide();
                    }
                }
            }
            $(document).on('keyup', '.search-text', function() {
                var search = $(this).val();
                serch(search)
            })

            $(document).on('click', '.get-active', function() {
                $('.get-active').removeClass('a-active');
                $(this).addClass('a-active');
                var url = '{{ route('restaurant.main_menu_search') }}';
                $.ajax({
                    url: url,
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: $(this).attr('data-id'),
                        restaurant_id: $(this).attr('data-restaurant_id'),
                        restaurant_view: "{{ app('request')->input('restaurant_view') }}",
                    },
                    // dataType: "json",
                    success: function (data) {
                        $('.food_categories_data_append').html(data);
                    },
                    error: function () {
                        alert('{{ __('system.messages.food_not_found') }}')
                        window.location.reload();
                    }
                })
            });
        })
    </script>
@endpush
