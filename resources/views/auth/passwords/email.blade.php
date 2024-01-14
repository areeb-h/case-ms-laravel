@extends('layouts.app')

@section('content')
<main>
    <div class="container">
        <div class="bg-white mx-auto p-5 rounded-xl space-y-5 w-fit">
            <h1 class="bg-orange-200/30 font-bold text-slate-800 text-center px-3 py-2 rounded-xl /mx-auto /w-fit">Reset Password</h1>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" class="space-y-5 min-w-[400px]" action="{{ route('password.email') }}">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                        <div class="w-full">
                            <input id="email" type="email" class="text-input form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="flex justify-between mt-5 pt-5 border-t space-x-4">
                            <div class="flex w-[130px] rounded-lg bg-orange-300/50 hover:bg-orange-300 items-center text-center">
                                <a class="px-4 py-1.5 w-full" href="{{ url()->previous() }}">Go Back</a>
                            </div>
                            <div class="w-full rounded-lg flex bg-teal-300/50 hover:bg-teal-300 items-center">
                                <button class="w-full" type="submit">Send Password Reset Link</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
