<div class="lg:flex items-center justify-between pb-8 text-center lg:text-left pt-14">
    <h3 class="text-2xl font-bold mb-5 lg:mb-0 dark:text-white">{{ $food_category->local_lang_name }}</h3>
    <p class="category-desc mb-10">{{ $food_category->category_description }}</p>
    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-end gap-5">
        {!! Form::select('categories', $categoires, $food_category->id, [
            'class' => 'text-white bg-neutral dark:bg-[#2c333f] text-sm font-semibold py-3.5 px-4 rounded-lg border border-neutral dark:border-secondary outline-none',
            'id' => 'category',
        ]) !!}
    </div>
</div>
<div class="pb-12 md:pb-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 xl:gap-8">
    @include('frontend.food_list')
    <p class="font-bold dark:text-white name truncate not_found custome" style="display:none"> {{ __('system.messages.food_not_found') }}</p>
</div>