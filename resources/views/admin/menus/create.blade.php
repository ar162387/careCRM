@extends('layouts.app') <!-- Adjust as per your layout structure -->

@section('title', 'Create Menu')

@section('content')
    <div class="container">
        <h1>Create Menu</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('menus.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Menu Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
            </div>
            <div class="form-group">
                <label for="icon">Menu Icon</label>
                <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon') }}">
            </div>
            <div class="form-group">
                <label for="route">Menu Route</label>
                <input type="text" class="form-control" id="route" name="route" value="{{ old('route') }}">
            </div>
            <div class="form-group">
                <label for="parent_id">Parent Menu</label>
                <select class="form-control" id="parent_id" name="parent_id">
                    <option value="">Select Parent</option>
                    @foreach ($parentMenus as $parentMenu)
                        <option value="{{ $parentMenu->id }}" {{ old('parent_id') == $parentMenu->id ? 'selected' : '' }}>
                            {{ $parentMenu->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="display_order">Display Order</label>
                <input type="number" class="form-control" id="display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0" max="100">
            </div>
            <div class="form-group">
                <label for="level">Menu Level</label>
                <select class="form-control" id="level" name="level">
                    <option value="admin" {{ old('level') == 'admin' ? 'selected' : '' }}>Admin Level Menu</option>
                    <option value="client" {{ old('level') == 'client' ? 'selected' : '' }}>Client Level Menu</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Menu Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add New Menu</button>
        </form>
    </div>
@endsection
