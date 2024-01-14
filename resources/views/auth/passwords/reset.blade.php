@extends('layouts.app')

@section('content')
<main>
    <div class="container">
        <div class="bg-white mx-auto p-5 rounded-xl space-y-5 w-fit">
            <h1 class="bg-orange-200/30 font-bold text-slate-800 text-center px-3 py-2 rounded-xl /mx-auto /w-fit">
                Reset Password
            </h1>

            <div class="card-body">
                <form method="POST" class="space-y-5 min-w-[400px]" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="space-y-2">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class=" text-input form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('New Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="text-input form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="text-input form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <button type="submit" class="btn-teal h-[37px] w-full">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
