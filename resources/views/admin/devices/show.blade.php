@extends('layouts.care')

@section('title', 'Device Details')

@section('content')
    <div class="mb-5">
        <h2 class="mb-2">Device Details</h2>
        <h5 class="text-body-tertiary fw-semibold">
            View device details.
        </h5>
    </div>

    <div class="card mb-9">
        <div class="card-body">
            <h5 class="card-title">Serial Number: {{ $device->serial_number }}</h5>
            <p class="card-text">Description: {{ $device->description }}</p>
            <div class="d-flex justify-content-end">
                <a class="btn btn-primary me-2" href="{{ route('devices.edit', $device->id) }}">Edit</a>
                <form action="{{ route('devices.destroy', $device->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection
