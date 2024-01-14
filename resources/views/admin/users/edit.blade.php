<!-- admin/users/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Name Field -->
        <input type="text" name="name" value="{{ $user->name }}" placeholder="Name">

        <!-- Email Field -->
        <input type="email" name="email" value="{{ $user->email }}" placeholder="Email">

        <!-- Role Field -->
        <select name="roles[]" multiple>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>

        <!-- Save Button -->
        <button type="submit">Save</button>
    </form>
@endsection
