@php($append = request()->query->has('restaurant_view') ? ['restaurant_view' => request()->query->get('restaurant_view')] : [])
@foreach ($food_categories ?? [] as $category)
<div class="text-center">
    <a href="{{ route('restaurant.menu.item', ['restaurant' => $restaurant->id, 'food_category' => $category->id] + $append) }}">
        <img src="{{ $category->category_image_url }}" alt="" class="w-full rounded-xl h-36 object-cover" />
        {{-- onerror="this.src='{{ asset('assets/images/defult.jpg') }}'" --}}
    </a>
    <a href="{{ route('restaurant.menu.item', ['restaurant' => $restaurant->id, 'food_category' => $category->id] + $append) }}"
        class="mt-3 inline-block font-title line-clamp-1 dark:text-white">{{ $category->local_lang_name }}</a>
    <p class="category-desc">{{ $category->category_description }}</p>
</div>
@endforeach