<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset(config('app.favicon_icon')) }}">

    <!-- Font Family -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
    <link href="{{ asset('assets/cdns/css2.css') }}" rel="stylesheet"/>
    <!-- Style CSS -->
	<link href="https://qrmenu.graphicdesigns.club/resources/views/frontend/custom.css" rel="stylesheet"/>
    @vite('resources/css/app.css')
    <style>
        body .dark-bg {
            background: url('{{ asset('assets/theme/images/dark-bg.png') }}') fixed;
        }

        #myModal img {
            background-image: url('{{  asset('assets/images/placeholder.png') }}');
            width: 100% !important;
            background-position: center;
            background-size: 100% 100%;
        }
    </style>
    @stack('page_css')
</head>
@php($theme = Cookie::get('front_theme', ''))
@php($dir = Cookie::get('front_dir', $language->direction ?? 'ltr'))

<body dir="{{ $dir }}" class="{{ $dir }} overflow-x-hidden">

<div
    class="antialiased font-montserrat text-secondary text-base  bg-cover bg-no-repeat bg-center rounded-t-xl {{ $theme }}"
    style="background:url('{{ asset('assets/theme/images/gradient-bg.jpg') }}') fixed">
    <div class="dark:bg-secondary  bg-cover bg-no-repeat bg-center dark-bg" style="">

        @php($append = request()->query->has('restaurant_view') ? ['restaurant_view' => request()->query->get('restaurant_view')] : [])
        <header id="header"
            class="border-b-2 border-neutral/10 dark:border-white/10 py-3.5 fixed inset-x-0 top-0 transition duration-300 z-10">
            <div class="container">
                <div class="flex items-center justify-between">
                    <?php
                    $currentRoute = request()
                        ->route()
                        ->uri();
                    $url = $currentRoute == '/' ? route($currentRoute) : route('restaurant.menu', ['restaurant' => $restaurant->id] + $append);
                    ?>
                    <a href="{{ $url }}">
                        <img src="{{ asset($restaurant->logo_url ?? config('app.dark_sm_logo')) }}" alt="logo"
                             class="w-[150px] max-h-12  object-contain dark:hidden"/>
                        <img src="{{ asset($restaurant->dark_logo_url ?? config('app.ligth_sm_logo')) }}" alt="logo"
                             class="w-[150px] max-h-12 object-contain hidden dark:block"/>
                    </a>
                    <ul class="flex items-center gap-4 sm:gap-6">
                        <li>
                            <button class="mobile_search block md:hidden">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg" class="text-secondary dark:text-white">
                                    <g clip-path="url(#clip0_75_257)">
                                        <path
                                            d="M15.8667 15.8668C16.2127 15.5208 16.7737 15.5207 17.1197 15.8667L18.3729 17.1194C18.7192 17.4655 18.7192 18.0269 18.373 18.3731C18.0268 18.7193 17.4654 18.7193 17.1193 18.373L15.8666 17.1198C15.5206 16.7738 15.5207 16.2128 15.8667 15.8668Z"
                                            fill="currentColor"></path>
                                        <path
                                            d="M8.9748 1C13.3769 1 16.9496 4.57271 16.9496 8.9748C16.9496 13.3769 13.3769 16.9496 8.9748 16.9496C4.57271 16.9496 1 13.3769 1 8.9748C1 4.57271 4.57271 1 8.9748 1ZM8.9748 15.1774C12.4013 15.1774 15.1774 12.4013 15.1774 8.9748C15.1774 5.54741 12.4013 2.77218 8.9748 2.77218C5.54741 2.77218 2.77218 5.54741 2.77218 8.9748C2.77218 12.4013 5.54741 15.1774 8.9748 15.1774Z"
                                            fill="currentColor"></path>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_75_257">
                                            <rect width="20" height="20" fill="white"></rect>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </button>
                            <div
                                class="mobile_input hidden md:block absolute inset-x-4 top-full md:relative md:inset-x-0 md:w-[350px]">
                                <button class="absolute left-3 top-1/2 -translate-y-1/2">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg" class="text-secondary dark:text-white">
                                        <g clip-path="url(#clip0_75_257)">
                                            <path
                                                d="M15.8667 15.8668C16.2127 15.5208 16.7737 15.5207 17.1197 15.8667L18.3729 17.1194C18.7192 17.4655 18.7192 18.0269 18.373 18.3731C18.0268 18.7193 17.4654 18.7193 17.1193 18.373L15.8666 17.1198C15.5206 16.7738 15.5207 16.2128 15.8667 15.8668Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M8.9748 1C13.3769 1 16.9496 4.57271 16.9496 8.9748C16.9496 13.3769 13.3769 16.9496 8.9748 16.9496C4.57271 16.9496 1 13.3769 1 8.9748C1 4.57271 4.57271 1 8.9748 1ZM8.9748 15.1774C12.4013 15.1774 15.1774 12.4013 15.1774 8.9748C15.1774 5.54741 12.4013 2.77218 8.9748 2.77218C5.54741 2.77218 2.77218 5.54741 2.77218 8.9748C2.77218 12.4013 5.54741 15.1774 8.9748 15.1774Z"
                                                fill="currentColor"></path>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_75_257">
                                                <rect width="20" height="20" fill="white"></rect>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </button>
                                <input type="text" autocomplete="off" placeholder="{{__('auth.search_item') }}" id="search_text"
                                       class="w-full outline-none border-2 border-neutral/30 dark:border-secondary/50 rounded-lg py-3 pl-10 pr-4 placeholder:text-sm placeholder:text-secondary dark:placeholder:text-white dark:text-white font-semibold dark:bg-white/10 search-text">
                            </div>
                        </li>
                        @if(config('app.dark_light_change')==true)
                            <li>
                                <button type="button" class="flex">
                                    <label for="day-night" class="inline-flex relative items-center cursor-pointer">
                                        <input type="checkbox" value="" id="day-night"
                                               class="sr-only peer" {{ $theme != 'dark' ? 'checked' : '' }}>
                                        <div
                                            class="relative border border-primary w-12 md:w-14 h-6 bg-secondary rounded-full peer peer-checked:after:translate-x-[115%] md:peer-checked:after:translate-x-[161%] after:border-white after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:left-[2px] after:bg-white after:rounded-full after:h-[19px] after:w-[19px] after:transition-all peer-checked:bg-primary">
                                            <img src="{{ asset('assets/theme/images/sun-svg.svg') }}" alt=""
                                                 class="absolute left-0.5 top-1/2 -translate-y-1/2 w-4">
                                            <img src="{{ asset('assets/theme/images/moon.svg') }}" alt=""
                                                 class="absolute right-0.5 top-[60%] -translate-y-1/2 w-5">
                                        </div>
                                    </label>
                                </button>
                            </li>
                        @endif

                        @if(config('app.direction_change')==true)
                            <li class="w-5 h-5 md:w-auto">
                                <button type="button" id="direction">
                                    <svg width="26" height="26" viewBox="0 0 26 26" fill="none"
                                         xmlns="http://www.w3.org/2000/svg" style="width: 26px;height: 26px"
                                         class="text-primary dark:text-white w-full h-full">
                                        <g clip-path="url(#clip0_83_167)">
                                            <path d="M21.2709 22.5327H11.7382" stroke="currentColor" stroke-width="2"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M18.8036 25L21.2709 22.5327L18.8036 20.0654" stroke="currentColor"
                                                  stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M3.99988 19.5047H13.5326" stroke="currentColor" stroke-width="2"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M6.46717 21.972L3.99988 19.5047L6.46717 17.0374"
                                                  stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            <path
                                                d="M17.5697 1H9.55103C8.40589 1 7.30766 1.45491 6.49792 2.26464C5.68818 3.07438 5.23328 4.17262 5.23328 5.31776C5.23328 6.4629 5.68818 7.56114 6.49792 8.37087C7.30766 9.18061 8.40589 9.63552 9.55103 9.63552H10.1679"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            <path d="M15.1025 14.5701V1" stroke="currentColor" stroke-width="2"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M10.1682 14.5701V1" stroke="currentColor" stroke-width="2"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_83_167">
                                                <rect width="26" height="26" fill="currentColor"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </button>
                            </li>
                        @endif


                        @if(config('app.display_language')==true)
                            <li class="w-5 h-5 md:w-auto relative" id="language">
                                <button type="button">
                                    <svg width="26" height="26" viewBox="0 0 26 26" fill="none"
                                         xmlns="http://www.w3.org/2000/svg" style="width: 26px;height: 26px"
                                         class="text-primary dark:text-white w-full h-full">
                                        <g clip-path="url(#clip0_83_161)">
                                            <path
                                                d="M5.83001 9.5169C5.6914 9.96834 6.02901 10.4252 6.50125 10.4252C6.97376 10.4252 7.31139 9.96785 7.17229 9.51628C6.96869 8.85536 6.03299 8.85579 5.83001 9.5169Z"
                                                fill="#2B3F6C"/>
                                            <path
                                                d="M9.29921 1.74015C9.18166 1.30354 8.78576 1.00012 8.3336 1.00012H2.60003C1.9104 1.00012 1.24915 1.2742 0.761479 1.76183C0.27385 2.24924 0 2.91052 0 3.60016V23.2857C0 24.1767 1.07714 24.6228 1.70711 23.9929L7.50704 18.1929C7.69457 18.0054 7.94893 17.9 8.21414 17.9H12.345C13.0031 17.9 13.4817 17.2755 13.3107 16.6401L9.29921 1.74015ZM8.06876 12.4318C7.93994 12.0118 7.55205 11.725 7.11272 11.725H5.88939C5.45063 11.725 5.06311 12.011 4.9338 12.4303L4.68505 13.2369C4.60471 13.4974 4.36393 13.6751 4.09131 13.6751C3.67326 13.6751 3.3745 13.2706 3.49744 12.871L5.65892 5.8464C5.77254 5.47716 6.11368 5.2252 6.5 5.2252C6.88632 5.2252 7.22746 5.47716 7.34108 5.84639L9.5027 12.8715C9.62557 13.2708 9.32698 13.6751 8.90917 13.6751C8.63635 13.6751 8.39548 13.4971 8.31549 13.2362L8.06876 12.4318Z"
                                                fill="currentColor"/>
                                            <path
                                                d="M23.4 1.00006C23.3999 1.0001 23.3999 1.00012 23.3998 1.00012H11.7049C11.0468 1.00012 10.5682 1.6247 10.7393 2.2601L14.7509 17.1599C14.8684 17.5966 15.2643 17.9 15.7165 17.9H23.4C24.0897 17.9 24.7509 17.6259 25.2386 17.1383C25.7262 16.6509 26.0001 15.9896 26.0001 15.2999V3.60003C26.0001 2.9104 25.7262 2.24915 25.2386 1.76171C24.751 1.2741 24.0897 1.00002 23.4001 1C23.4001 1 23.4 1.00002 23.4 1.00006ZM18.8501 5.87513C18.8501 5.51618 19.1411 5.22514 19.5 5.22514C19.859 5.22514 20.1499 5.51618 20.1499 5.87513C20.1499 6.23404 19.859 6.52506 19.5001 6.52512C19.1411 6.52518 18.8501 6.23412 18.8501 5.87513ZM22.7499 13.0287C22.75 13.3866 22.4611 13.6802 22.104 13.6568C21.2862 13.6032 20.4797 13.4344 19.7084 13.1554C19.4408 13.0585 19.1463 13.0657 18.8819 13.1712C18.2467 13.4246 17.5784 13.5845 16.898 13.6462C16.541 13.6785 16.25 13.3847 16.25 13.0262V12.9362C16.25 12.6265 16.5023 12.3784 16.8107 12.3493C17.2635 12.3065 17.3889 11.742 17.1017 11.3893C16.7047 10.9019 16.4309 10.3565 16.3139 9.77098C16.2435 9.41899 16.541 9.12522 16.9 9.12522C17.2589 9.12522 17.5417 9.42326 17.656 9.76353C17.8362 10.2999 18.2372 10.792 18.7933 11.2022C19.0816 11.4148 19.4752 11.4123 19.7576 11.192C20.5216 10.5961 21.0646 9.76633 21.308 8.83657C21.4501 8.29344 20.9878 7.82511 20.4264 7.82511H16.8999C16.541 7.82511 16.25 7.53413 16.25 7.17518C16.25 6.81623 16.541 6.52524 16.8999 6.52524H21.7499C22.3022 6.52524 22.7499 6.97296 22.7499 7.52524V7.82511C22.7354 9.01921 22.3384 10.164 21.636 11.1008C21.2886 11.5642 21.4591 12.29 22.0358 12.3431C22.0562 12.345 22.0766 12.3467 22.0969 12.3485C22.4564 12.3786 22.7499 12.6679 22.7499 13.0287Z"
                                                fill="currentColor"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_83_161">
                                                <rect width="26" height="26" fill="white"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </button>
                                <ul class="absolute ltr:right-0 rtl:left-0 top-[150%] bg-primary dark:bg-secondary/70 text-white py-1 z-20 language_list"
                                    style="display:none">

                                    @php($languages_array = getAllLanguages(true))
                                    @foreach ($languages_array as $key => $language)
                                        @if (App::currentLocale() != $key)
                                            <li class="py-2 px-6  transition cursor-pointer  hover:bg-secondary dark:hover:bg-primary text-sm"
                                                onclick="event.preventDefault(); document.getElementById('user_set_default_language{{ $key }}').submit();">
                                                <a href="">{{ $language }}</a>
                                            </li>

                                            {{ Form::open(['route' => ['restaurant.default.language', ['language' => $key]], 'method' => 'put', 'autocomplete' => 'off', 'style' => 'display:none', 'id' => 'user_set_default_language' . $key]) }}
                                            <input type="hidden" name='back' value="{{ request()->fullurl() }}">
                                            {{ Form::close() }}
                                        @else
                                            <li class="py-2 px-6  transition cursor-pointer  bg-secondary dark:bg-primary text-sm"
                                                onclick="event.preventDefault(); document.getElementById('user_set_default_language{{ $key }}').submit();">
                                                <a href="">{{ $language }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                </ul>
                            </li>
                        @endif


                    </ul>
                </div>
            </div>
        </header>


        @if(config('app.show_banner')==1 || config('app.show_restaurant_name')==1)

            @if(config('app.show_banner'))
                <section class="relative h-[250px] md:h-[400px]">
                    <!-- Circles background -->
                    <img class="w-full h-full object-cover" loading="lazy" src="{{ $restaurant->cover_image_url }}"
                         onerror="this.src='{{ asset('assets/images/cover.png') }}'"
                         style="width: 100%">
                    <!-- SVG separator -->
                </section>
            @endif

            @if(config('app.show_restaurant_name'))
                @if(config('app.show_banner')==0)
                   <section class="relative h-[100px] md:h-[100px]"></section>
                @endif
                <section class="pt-5 dark:text-white">
                    <div class="container">
                        <div class="pb-5 border-b border-neutral/30 dark:border-white/30">
                            <p class="text-lg md:text-2xl font-semibold capitalize mb-4">
                                {{ $restaurant->name." - ".$restaurant->type }}
                            </p>
                            <ul class="flex flex-col md:flex-row gap-3">
                                @if($restaurant->full_address)
                                    <li class="inline-flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-primary flex-shrink-0" width="24" height="24"
                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="12" cy="11" r="3"></circle>
                                            <path
                                                d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                            </path>
                                        </svg>
                                        <a href="#">{{ $restaurant->full_address }}</a>
                                    </li>
                                @endif
                                @if($restaurant->phone_number)
                                    <li class="inline-flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-primary flex-shrink-0" width="24" height="24"
                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <rect x="6" y="3" width="12" height="18" rx="2"></rect>
                                            <line x1="11" y1="4" x2="13" y2="4"></line>
                                            <line x1="12" y1="17" x2="12" y2="17.01"></line>
                                        </svg>
                                        <a href="tel:{{ $restaurant->phone_number }}">{{ $restaurant->phone_number }}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </section>
            @endif

        @else
            <section class="relative h-[120px] md:h-[120px]"></section>
        @endif


        <div style="min-height:calc(100vh - 144px)">
            @yield('content')
            @once
                <div class="modal-details"></div>
            @endonce
        </div>
		
		
		
		<!--Chef’s Request Content-->
		<div class="container">
			<div class="row">
			  <div class="col-sm-12">
				  <div class="white-container shadow-shadowitem mb-10">
					  <h2 class="font-title">Chef’s request</h2>
				<p>“We believe every Patron deserves to enjoy fresh, made - from -scratch meals served in abundance. If you or any of your guests have an allergy or dietary restrictions, please inform your server & our chef’s will be delighted to accommodate your needs. The Secret in our kitchen is passion and respect for you! <br>
All government taxes and service charge as applicable </p>
				</div>
				</div>
			</div>
		</div>
		
		<!--Chef’s Request Content-->
		<div class="container">
			<div class="row">
			  <div class="col-sm-12">
				  <div class="white-container shadow-shadowitem mb-10">
					  <h2 class="font-title">Disclaimer</h2>
				<p> The food images shown are for illustration purposes only and may not be an exact representation of the food served.  </p>
				</div>
				</div>
			</div>
		</div>
		
		<!--Social Media icons-->
		<div class="container">
			<div class="row">
			  <div class="col-sm-12">
				  <div class="white-container shadow-shadowitem mb-10">
				  <ul class="social-menu">
					<h2>Connect with us on</h2>
					<li><a href="https://www.facebook.com/leonardo.surat" target="_blank" class="facebook"> <img src="https://qrmenu.graphicdesigns.club/resources/views/frontend/img/fb.svg" alt="" height="40"> </a></li>

					<li><a href="https://www.instagram.com/leonardo_surat/" target="_blank" class="instagram"><img src="https://qrmenu.graphicdesigns.club/resources/views/frontend/img/insta.svg" alt="" height="40"></a></li>

					<li><a href="https://www.zomato.com/surat/leonardo-italian-mediterranean-dining-piplod/" target="_blank" class="zomato"> <img src="https://qrmenu.graphicdesigns.club/resources/views/frontend/img/zomato.png" alt="" height="40"> </a></li>

					<li><a href="https://www.tripadvisor.in/Restaurant_Review-g297612-d5989667-Reviews-Leonardo_Italian_Mediterranean_Dining-Surat_Surat_District_Gujarat.html" target="_blank" class="twitter"><img src="https://qrmenu.graphicdesigns.club/resources/views/frontend/img/trip.svg" alt="" height="40"></a></li>

					<li><a href="https://www.leonardorestaurant.in/" target="_blank" class="twitter"><img src="https://qrmenu.graphicdesigns.club/resources/views/frontend/img/web.svg" alt="" height="40"></a></li>

					</ul>
				  </div>  
				</div>
			</div>
		</div>
		
		
        <footer>
            <div class="container">
                <div
                    class="flex flex-col md:flex-row items-center justify-center md:justify-between text-neutral py-5 border-t-2 border-dashed border-secondary/10 dark:border-white/10 font-semibold text-sm lg:text-base dark:text-white">
                    <div class="footer-copyright">©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> {{ __('auth.copyright') }}
                        <a href="{{ route('/') }}">{{ config('app.name') }}</a> | {{ __('auth.all_rights_reserved') }}

                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
<script src="{{ asset('assets/cdns/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script>
    var themeRoute = "{{ route('theme.mode') }}";
    $(".mobile_search").click(function () {
        $(".mobile_input").toggleClass("open_search");
    });
    $(document).on("change", '#day-night', function () {
        var is_ligth_mode = $(this).prop('checked');
        var theme = "-";
        if (is_ligth_mode) {
            $(document).find('.dark').removeClass('dark');
        } else {
            $(document).find('body>div').addClass('dark');
            theme = 'dark'
        }
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", themeRoute);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("f_theme=" + theme);
    })
    $(document).on("click", '#direction', function () {
        var body = $(document).find('body')
        if (body.attr('dir') == 'rtl') {
            body.attr('dir', 'ltr')
        } else {
            body.attr('dir', 'rtl')
        }
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", themeRoute);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("f_dir=" + body.attr('dir'));
    })
    $(document).on("click", '#language>button', function (e) {

        var droDown = $(document).find('#language .language_list')
        if (droDown.css('display') == 'none') {
            droDown.css('display', 'block');
        } else {
            droDown.css('display', 'none');
        }

        return false;
    })
    $(document).on("click", '#language', function (e) {

        return false;
    })
    $(document).on("click", function (e) {
        var droDown = $(document).find('#language .language_list')

        if (droDown.css('display') != 'none') {
            droDown.css('display', 'none');
        }
    })
    $(document).on('click', '.view_more', function () {
        id = $(this).data('id');
        modal_popup(id)
    })

    $(document).on('click', '.dismiss_modal', function () {
        document.getElementById('staticModal').classList.toggle('hidden')
    })
    $(window).scroll(function () {
        if ($(window).scrollTop() >= 1) {
            $("header").addClass("header-scroll");
        } else {
            $("header").removeClass("header-scroll");
        }
    });
    @if(config('app.is_show_display_full_details_model'))
    function modal_popup(id) {
        var url = '{{ route('restaurant.food',"#0#") }}';
        url = url.replace('#0#', id);
        // alert(url);
        $.ajax({
            url: url,
            type: 'post',
            data: {_token: '{{ csrf_token() }}'},
            // dataType: "json",
            success: function (data) {
                $(document).find('.modal-details').html(data);
                var swiper = new Swiper(".mySwiper", {
                    loop: false,
                    spaceBetween: 10,
                    slidesPerView: 4,
                    direction: "vertical",
                    freeMode: true,
                    watchSlidesProgress: true,
                });
                var swiper2 = new Swiper(".mySwiper2", {
                    loop: false,
                    spaceBetween: 0,
                    thumbs: {
                        swiper: swiper,
                    },
                });
                $("#myModal").toggleClass("show");
                if ($('body').attr('dir') == 'rtl') {
                    $(document).find('.modal-details .close-model').removeClass('right-2')
                    $(document).find('.modal-details .close-model').addClass('left-2')
                }
                $('body').css('overflow', 'hidden')
            },
            error: function () {
                alert('{{ __('system.messages.food_not_found') }}')
                window.location.reload();
            }
        })

    }

    //

    $(document).on('click', '.popup-slider', function () {
        var id = ($(this).data('id'))
        modal_popup(id)
    });

    $(document).on('click', '.close-model', function () {
        $("#myModal").toggleClass("show");
        $('body').removeAttr('style')
    })
    @endif
</script>
@stack('page_js')

</body>

</html>
