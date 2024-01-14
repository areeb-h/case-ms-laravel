<!-- Main Layout -->
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CMS') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite('resources/css/app.css')

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="{{ mix('js/app.js') }}" defer></script>
    <!-- @vite(['resources/sass/app.scss', 'resources/js/app.js'])-->
</head>
<body class="bg-gray-100">
    <div id="app">
        <nav class="bg-white shadow-sm">
            <div class="container mx-auto">
                <div class="flex justify-between items-center py-4">
                    <a class="text-2xl font-semibold text-gray-800" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <div class="hidden md:flex justify-center items-center">
                        <ul class="flex space-x-4">
                            <!-- Authentication Links -->
                            @guest
                            @if (Route::has('login'))
                                    <li class="">
                                        <a class="text-gray-800 hover:text-blue-600" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li>
                                        <a class="text-gray-800 hover:text-blue-600" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                @if (Auth::check() && Auth::user()->hasRole('Administrator'))
                                    <ul class="links">
                                        <li>
                                            <a class="{{ Str::startsWith(Route::currentRouteName(), 'admin.users') ? 'link active' : 'link' }}" href="{{ route('admin.users') }}">{{ __('Users') }}</a>
                                        </li>
                                        <li>
                                            <a class="{{ Str::startsWith(Route::currentRouteName(), 'admin.cases') ? 'link active' : 'link' }}" href="{{ route('admin.cases') }}">{{ __('Cases') }}</a>
                                        </li>
                                    </ul>
                                @endif

                                <li class="relative">
                                    @include('components.dropdown')
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            FB.init();
        });
    </script>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>
