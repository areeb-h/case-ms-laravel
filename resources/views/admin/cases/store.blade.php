<!-- resources/views/admin/users/store.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <p>User created successfully.</p>
        <a href="{{ route('admin.dashboard') }}">Go back to admin dashboard</a>
    </div>
@endsection
