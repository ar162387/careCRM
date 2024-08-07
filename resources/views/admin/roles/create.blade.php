@extends('layouts.care')
@section('title', 'Create Role')
@section('page-title', 'Create Role')
@section('page-message', "Create a new user role.")

@section('content')
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active">Create Menu</li>
        </ol>
    </nav>

    <div class="mb-5">
        <h2 class="text-bold text-body-emphasis">Create Role</h2>
        <p class="text-body-tertiary lead">
            Add a new user role.
        </p>
    </div>

    <form method="POST" action="{{ route('roles.store') }}" class="needs-validation" novalidate>
        @csrf
        <div class="mb-5">
            <div class="form-group">
                <label for="title" class="form-label">Role Title</label>
                <input type="text" class="form-control" name="title" required>
                <div class="invalid-feedback">Please provide a suitable role title.</div>
            </div>
        </div>

        <div class="mb-5">
            <div class="form-group">
                <label for="menus" class="form-label">Attach Menus</label>
                <select class="form-select" id="menus" name="menus[]" data-choices="data-choices" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}'>
                    <option value="">Select Menu...</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}" data-custom-properties="[object Object]">{{ $menu->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Create Role</button>
    </form>
@endsection
