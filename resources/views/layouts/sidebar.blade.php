<ul class="metismenu list-unstyled" id="side-menu">
    <li class="mb-3">
        <a href="javascript: void(0);" class="has-arrow">
            <span>{{auth()->user()->restaurant->name}}</span>
        </a>

        <ul class="sub-menu" aria-expanded="false">
            @foreach ($restaurants as $restaurant)
                @if (auth()->user()->restaurant_id != $restaurant->id)
                    <li><a onclick="event.preventDefault(); document.getElementById('restaurant_default_restaurant{{ $restaurant->id }}').submit();" href="javascript:void(0)" data-key="t-g-maps"> {{ $restaurant->name }}</a></li>
                @endif
            @endforeach
        </ul>

    </li>
    @include('layouts.menu')
</ul>
