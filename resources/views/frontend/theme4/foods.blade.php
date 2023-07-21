@extends('frontend.master')
@section('content')
    <section class="container food_categories_data_append">
        @include('frontend.theme2.foods_view')
    </section>
@endsection
@push('page_js')
    <script>
        @php($append = request()->query->has('restaurant_view') ? ['restaurant_view' => request()->query->get('restaurant_view')] : [])

        var categoriesRoute = "{{ route('restaurant.menu.item', ['restaurant' => $restaurant->id, 'food_category' => '#ID#']) . '?' . http_build_query($append) }}"

        $(document).on("change", "#category", function() {
            categoriesRoute = categoriesRoute.replace("#ID#", $(this).val())
            document.location.href = categoriesRoute

        })
        $(document).ready(function() {
            function serch(search) {
                search = search.toLowerCase();
                $(".food-item").show();
                $(document).find(".not_found").hide();
                if (search == '') {
                    $(".food-item").show();
                    $('.not_found').hide();
                } else {
                    search = search.replace('\\', '\\\\');
                    var x = 0;
                    $(document).find('.food-item').each(function() {
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

                        if (val1.search(search) == -1 && val2.search(search) == -1 && val3.search(search) == -1) {
                            $(this).hide();
                            x++;
                        }



                    });
                    if (x == $(document).find('.food-item').length) {
                        $(document).find(".not_found").show();
                    } else {
                        $(document).find(".not_found").hide();
                    }

                }
            }

            var activeMenu = ".active-"+"{{ $food_category->main_menu }}";
            $('.get-active').removeClass('a-active');
            $(activeMenu).addClass('a-active');

            $(document).on('keyup', '#search_text', function() {
                var search = $(this).val();
                serch(search)
            })

            $(document).on('click', '.get-active', function() {
                $('.get-active').removeClass('a-active');
                $(this).addClass('a-active');
                var url = '{{ route('restaurant.menu.item.search', [":restaurant_id", ":food_category_id", ":main_menu"]) }}';
                url = url.replace(':restaurant_id', "{{ $restaurant->id }}");
                url = url.replace(':food_category_id', "{{ $food_category->id }}");
                url = url.replace(':main_menu', $(this).attr('data-id'));
                $.ajax({
                    url: url,
                    type: 'get',
                    // dataType: "json",
                    success: function (data) {
                        console.log(data);
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
