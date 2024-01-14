
<!-- resources/views/admin/users/create.blade.php -->

@extends('layouts.app')

@section('content')
    <main>
        <div class="container">
            <div class="bg-white mx-auto p-5 rounded-xl space-y-5 w-fit">
                <h1 class="bg-green-200/30 font-bold text-slate-800 text-center px-3 py-2 rounded-xl /mx-auto /w-fit">Create New User</h1>
                <form class="min-w-[400px]" action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="flex flex-col space-y-4">

                        <div class="w-full space-y-2">
                            <label for="name">Name</label>
                            <input class="text-input" type="text" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="w-full space-y-2">
                            <label for="email">Email</label>
                            <input class="text-input" type="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="w-full space-y-2">
                            <label for="roles">Roles</label>
                            <select class="text-input" name="roles[]" required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="flex justify-between mt-5 pt-5 border-t space-x-4">
                        <div class="flex rounded-lg bg-red-300/50 hover:bg-red-300 items-center">
                            <a class="px-4 py-1.5 rounded-lg hover:bg-red-300 w-full" href="{{ url()->previous() }}">Cancel</a>
                        </div>
                        <div class="w-full rounded-lg flex bg-teal-300/50 hover:bg-teal-300 items-center">
                            <button class="w-full" type="submit">Create User</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
