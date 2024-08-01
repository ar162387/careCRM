@extends('layouts.app') <!-- Adjust as per your layout structure -->

@section('title', 'Menu List')

@section('content')
    <div class="container">
        <h1>Menu List</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('menus.create') }}" class="btn btn-primary mb-3">Add Menu</a>

        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Icon</th>
                <th>Route</th>
                <th>Parent Menu</th>
                <th>Display Order</th>
                <th>Level</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($menus as $menu)
                <tr>
                    <td>{{ $menu->id }}</td>
                    <td>{{ $menu->title }}</td>
                    <td>{{ $menu->icon }}</td>
                    <td>{{ $menu->route }}</td>
                    <td>{{ optional($menu->parent)->title }}</td>
                    <td>{{ $menu->display_order }}</td>
                    <td>{{ $menu->level }}</td>
                    <td>{{ $menu->status ? 'Active' : 'Blocked' }}</td>
                    <td>
                        <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
