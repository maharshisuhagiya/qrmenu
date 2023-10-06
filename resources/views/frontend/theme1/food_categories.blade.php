@foreach ($food_categories as $category)
    {{-- @if(count($category->foods)) --}}
    <div class="category">
        <div class="lg:flex items-center justify-between pt-0 pb-4 text-center lg:text-left">
            <h3 class="text-2xl font-bold mb-5 lg:mb-0 dark:text-white title font-title">{{ $category->local_lang_name }}
            </h3>
            <p class="category-desc">{{ $category->category_description }}</p>
        </div>
        <div class="mb-10">
            <div class="grid md:grid-cols-2 gap-5">
                @foreach ($category->foods as $key => $food)
                    <div class="bg-white dark:bg-secondary/50 rounded-xl p-4 food-item popup-slider cursor-pointer"  data-id='{!! $food->id !!}'>
                        <a href="javascript:" class="font-bold text-secondary dark:text-white name">{{ $food->local_lang_name }}</a>
                        <p class="text-neutral text-sm pt-3 mb-4 font-semibold line-clamp-3 description">
                            {{ $food->local_lang_description }}</p>
                        @if($food->price == 0)
                            @foreach ($food->local_custom_field as $item)
                                <button type="button" class="text-primary font-bold text-sm dark:text-white bg-primary/10 dark:bg-primary rounded-lg py-1.5 px-3 inline-block amount">
                                    <span>
                                        {{ displayCurrency($item) }}
                                    </span>
                                </button>
                            @endforeach
                        @else
                            <button type="button" class="text-primary font-bold text-sm dark:text-white bg-primary/10 dark:bg-primary rounded-lg py-1.5 px-3 inline-block amount">
                                <span>
                                    {{ displayCurrency($food->price) }}
                                </span>
                            </button>
                        @endif
                        <span class="food-type">
                            @if(count($food->food_types))
                                @foreach ($food->food_types as $item)
                                    <img  src="{{ $item->food_types_image_url }}" title="{{$item['food_types_name']}}" alt="">
                                @endforeach
                            @endif
                        </span>
                    </div>
                @endforeach
                <p class="font-bold dark:text-white name truncate not_found" style="{{ count($category->foods) > 0 ? 'display:none;' : '' }}"> {{ __('system.messages.food_not_found') }}</p>
            </div>
        </div>
    </div>
    {{-- @endif --}}
@endforeach