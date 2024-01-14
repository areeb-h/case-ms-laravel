<!-- admin/cases/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container" x-data="{ tab: 'summary' }" x-init="tab = window.location.hash ? window.location.hash.substring(1) : 'summary'">
        <ul class="tabs w-fit mx-auto">
            <li class="nav-item tab" :class="{'active': tab === 'summary'}">
                <button @click="tab = 'summary'; window.location.hash = 'summary'">Summary</button>
            </li>
            <li class="nav-item tab" :class="{'active': tab === 'documents'}">
                <button @click="tab = 'documents'; window.location.hash = 'documents'">Documents</button>
            </li>
            <li class="nav-item tab" :class="{'active': tab === 'hearings'}">
                <button @click="tab = 'hearings'; window.location.hash = 'hearings'">Hearings</button>
            </li>
            <li class="nav-item tab" :class="{'active': tab === 'actions'}">
                <button @click="tab = 'actions'; window.location.hash = 'actions'">Actions</button>
            </li>
        </ul>

        <div x-show="tab === 'summary'" class="p-4">
            @include('admin.cases.case.summary')
        </div>

        <div x-show="tab === 'documents'" class="p-4">
            @include('admin.cases.case.documents')
        </div>

        <div x-show="tab === 'hearings'" class="p-4">
            @include('admin.cases.case.hearings')
        </div>

        <div x-show="tab === 'actions'" class="p-4">
            @include('admin.cases.case.actions')
        </div>
    </div>
@endsection
