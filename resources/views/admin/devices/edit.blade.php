@extends('layouts.care')

@section('title', 'Edit Device')

@section('content')
    <div class="mb-5">
        <h2 class="mb-2">Edit Device</h2>
        <h5 class="text-body-tertiary fw-semibold">
            Edit device details.
        </h5>
    </div>

    <form class="mb-9" action="{{ route('devices.update', $device->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3 mb-7">
            <div class="col-6">
                <label class="form-label" for="serial_number">Serial Number</label>
                <input class="form-control" type="text" id="serial_number" name="serial_number" value="{{ $device->serial_number }}" required>
            </div>

            <div class="col-6">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ $device->description }}</textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-primary" type="submit">Update Device</button>
        </div>
    </form>
@endsection
