@php($append = request()->query->has('restaurant_view') ? ['restaurant_view' => request()->query->get('restaurant_view')] : [])
@foreach ($food_categories ?? [] as $category)
<div class="bg-white dark:bg-secondary/50 rounded-xl shadow-shadowitem hover:shadow-shadowdark transition">
    <a href="{{ route('restaurant.menu.item', ['restaurant' => $restaurant->id, 'food_category' => $category->id] + $append) }}"><img src="{{ $category->category_image_url }}" alt=""
            class="w-full rounded-t-xl h-56 object-cover" onerror="this.src='{{ asset('assets/images/defult.jpg') }}'" /></a>
    <div class="p-4">
        <a href="{{ route('restaurant.menu.item', ['restaurant' => $restaurant->id, 'food_category' => $category->id] + $append) }}" class="font-bold dark:text-white">{{ $category->local_lang_name }}</a>
        <p class="category-desc">{{ $category->category_description }}</p>
    </div>
</div>
@endforeach