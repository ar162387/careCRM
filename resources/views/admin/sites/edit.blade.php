@extends('layouts.care')

@section('content')
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/sites') }}">Sites</a></li>
            <li class="breadcrumb-item active">Edit Site</li>
        </ol>
    </nav>

    <form class="mb-9" method="POST" action="{{ route('sites.update', $site->id) }}">
        @csrf
        @method('PUT')
        <div class="row g-3 flex-between-end mb-5">
            <div class="col-auto">
                <h2 class="mb-2">Edit Site</h2>
                <h5 class="text-body-tertiary fw-semibold">
                    Update the site information for your factories.
                </h5>
            </div>
            <div class="col-auto">
                <button class="btn btn-phoenix-secondary me-2 mb-2 mb-sm-0" type="reset">Discard</button>
                <button class="btn btn-primary mb-2 mb-sm-0" type="submit">Update Site</button>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-12 col-xl-8">
                <div class="mb-5">
                    <h5>Site Title</h5>
                    <input class="form-control" type="text" id="title" name="title" placeholder="Site Title"
                           value="{{ old('title', $site->title) }}" required>
                </div>

                <div class="mb-5">
                    <h5>Factory</h5>
                    <select class="form-control" id="factory_id" name="factory_id" required>
                        @foreach($factories as $factory)
                            <option value="{{ $factory->id }}" {{ $site->factory_id == $factory->id ? 'selected' : '' }}>
                                {{ $factory->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </form>
@endsection
