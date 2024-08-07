@extends('layouts.care')

@section('content')
    <div class="col-12 col-xxl-8">
        <div class="card shadow-none border" data-component-card="data-component-card">
            <div class="card-header p-4 border-bottom bg-body">
                <div class="row g-3 justify-content-between align-items-center">
                    <div class="col-12 col-md">
                        <h4 class="text-body mb-0" id="vertical-wizard">
                            View Service Representative
                        </h4>
                    </div>
                </div>
            </div>

            <div class="card-body pt-4 pb-0">
                <div class="row justify-content-between">
                    <div class="col-md-8 mb-5">
                        <div id="wizardVerticalForm4">
                            <div class="row g-3" id="service-rep-info">
                                <div class="col-12">
                                    <label class="form-label">Service Rep Name</label>
                                    <input type="text" class="form-control" value="{{ $serviceRepresentative->service_rep_name }}" readonly>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Address</label>
                                            <input type="text" class="form-control" value="{{ $serviceRepresentative->address }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">City</label>
                                            <input type="text" class="form-control" value="{{ $serviceRepresentative->city }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">State/Province</label>
                                            <input type="text" class="form-control" value="{{ $serviceRepresentative->state }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Zip/Post Code</label>
                                            <input type="text" class="form-control" value="{{ $serviceRepresentative->zip }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Country</label>
                                    <input type="text" class="form-control" value="{{ $serviceRepresentative->country }}" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Contact Name</label>
                                    <input type="text" class="form-control" value="{{ $serviceRepresentative->contact_name }}" readonly>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Contact Title</label>
                                            <input type="text" class="form-control" value="{{ $serviceRepresentative->contact_title }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" value="{{ $serviceRepresentative->phone_number }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Alt Phone Number</label>
                                            <input type="text" class="form-control" value="{{ $serviceRepresentative->alt_phone_number }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Fax Number</label>
                                            <input type="text" class="form-control" value="{{ $serviceRepresentative->fax_number }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Email Address</label>
                                    <input type="text" class="form-control" value="{{ $serviceRepresentative->email }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Add any additional JavaScript here if needed
    </script>
@endpush
