<div class="overflow-x-auto mb-4 scrollbar-thin scrollbar-thumb-primary scrollbar-track-[#d1d7e7]">
    <div class="flex w-max justify-between gap-4 py-8 lg:py-12 font-semibold dark:text-white" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
        @foreach ($food_categories ?? [] as $key => $category)
            <div class="text-center w-40 xl:w-auto {{ $key == 0 ? 'actives' : '' }} cursor-pointer " id="profile{{ $category->id }}-tab" data-tabs-target="#profile{{ $category->id }}" type="button" role="tab"
                aria-controls="profile{{ $category->id }}">
                <img src="{{ $category->category_image_url }}" alt="" class="mx-auto w-24 h-24 rounded-full border border-2  border-transparent " />
                {{-- onerror="this.src='{{ asset('assets/images/defult.jpg') }}'" --}}
                <a href="javascript:" class="mt-3 inline-block line-clamp-3">{{ $category->local_lang_name }}</a>
            </div>
        @endforeach

    </div>
</div>
<div id="myTabContent">
    @foreach ($food_categories ?? [] as $key => $category)
        <div class="pb-12 md:pb-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 xl:gap-8  {{ $key == 0 ? 'active' : 'hidden' }} tab-pane" id="profile{{ $category->id }}" role="tabpanel"
            aria-labelledby="profile{{ $category->id }}-tab">
            @include('frontend.food_list', ['foods' => $category->foods ?? []])

        </div>
    @endforeach

</div>
<p class="font-bold dark:text-white name truncate not_found" style="{{ count($food_categories ?? []) > 0 ? 'display:none;' : '' }}">
    {{ __('system.messages.food_not_found') }}
</p>