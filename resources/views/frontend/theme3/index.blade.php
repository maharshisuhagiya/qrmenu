@extends('frontend.master')
@section('content')
    <div class="container food_categories_data_append">
        @include('frontend.theme3.food_categories')
    </div>
@endsection
@push('page_js')
    <script>
        $(document).ready(function() {
            $(document).on('click', '[role=tab]', function() {
                var _target = $(this).data('tabs-target')
                $(document).find("#myTabContent .active").addClass('hidden')
                $(document).find("#myTabContent .active").removeClass('active')
                $(document).find(_target).removeClass('hidden')
                $(document).find(_target).addClass('active')
                $(document).find("#myTab .actives").removeClass('actives')
                $(this).addClass('actives')
            })


            function serch(search) {
                search = search.toLowerCase();

                if (search == '') {

                    $(".food-item").show();
                    $(document).find(".not_found.custome").hide();
                } else {
                    $(".food-item").show();
                    search = search.replace('\\', '\\\\');
                    $(document).find('.tab-pane').each(function() {
                        var x = 0;
                        $(this).find('.food-item').each(function() {
                            if (!$(this).hasClass('not_found')) {
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
                                    x++;
                                }
                            }

                        });
                        if (x == $(this).find('.food-item').length) {
                            $(this).find(".not_found").show();
                        } else {
                            $(this).find(".not_found").hide();
                        }

                    });

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
