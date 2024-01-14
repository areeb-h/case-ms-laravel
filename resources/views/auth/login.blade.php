<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite('resources/css/app.css')

    <!-- @vite(['resources/sass/app.scss', 'resources/js/app.js'])-->
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-2xl shadow-md w-full sm:w-96">
            <h1 class="bg-orange-200/30 font-bold text-lg text-slate-800 text-center px-3 py-2 rounded-xl /mx-auto /w-fit">
                Case Management System
                <div class="rounded-lg text-base w-full font-semibold text-center py-2 bg-white/50 mt-2">
                    Please sign in to continue
                </div>
            </h1>

            <div class="card-body bg-white">
                <form method="POST" class="space-y-5" action="{{ route('login') }}">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="block text-md-end">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="rounded-md py-2 px-3 w-full border border-cyan-400 form-input @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-md-end">{{ __('Password') }}</label>
                        <input id="password" type="password" class="rounded-md py-2 px-3 w-full border border-cyan-400 form-input @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input class="form-checkbox" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="ml-2" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col space-y-4 items-center">
                        <button type="submit" class="flex w-full text-white font-bold bg-teal-500 px-4 py-2 rounded-lg hover:bg-teal-500/90 justify-center items-center">
                            {{ __('Login') }}
                        </button>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link ml-4" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
