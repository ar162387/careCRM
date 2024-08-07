@extends('layouts.care')

@section('content')
    <div class="col-12 col-xxl-8">
        <div class="card shadow-none border" data-component-card="data-component-card">
            <div class="card-header p-4 border-bottom bg-body">
                <div class="row g-3 justify-content-between align-items-center">
                    <div class="col-12 col-md">
                        <h4 class="text-body mb-0" id="vertical-wizard">
                            Edit Service Representative
                        </h4>
                    </div>
                </div>
            </div>


            <div class="card-body pt-4 pb-0">
                <div class="row justify-content-between">
                    <div class="col-md-8">
                        <form id="wizardVerticalForm4" action="{{ route('service-reps.update', $serviceRepresentative->id) }}" method="POST" novalidate="novalidate">
                            @csrf
                            @method('PUT')
                            <div class="row g-3" id="service-rep-info">
                                <div class="col-md-12">
                                    <label for="service-rep-name" class="form-label">Service Rep Name</label>
                                    <input type="text" class="form-control" id="service-rep-name" name="service_rep_name" value="{{ $serviceRepresentative->service_rep_name }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="service-rep-address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="service-rep-address" name="address" value="{{ $serviceRepresentative->address }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="service-rep-city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="service-rep-city" name="city" value="{{ $serviceRepresentative->city }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="service-rep-state" class="form-label">State/Province</label>
                                    <input type="text" class="form-control" id="service-rep-state" name="state" value="{{ $serviceRepresentative->state }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="service-rep-zip" class="form-label">Zip/Post Code</label>
                                    <input type="text" class="form-control" id="service-rep-zip" name="zip" value="{{ $serviceRepresentative->zip }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="service-rep-country" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="service-rep-country" name="country" value="{{ $serviceRepresentative->country }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="service-rep-contact-name" class="form-label">Contact Name</label>
                                    <input type="text" class="form-control" id="service-rep-contact-name" name="contact_name" value="{{ $serviceRepresentative->contact_name }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="service-rep-contact-title" class="form-label">Contact Title</label>
                                    <input type="text" class="form-control" id="service-rep-contact-title" name="contact_title" value="{{ $serviceRepresentative->contact_title }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="service-rep-phone-number" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="service-rep-phone-number" name="phone_number" value="{{ $serviceRepresentative->phone_number }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="service-rep-alt-phone-number" class="form-label">Alt Phone Number</label>
                                    <input type="text" class="form-control" id="service-rep-alt-phone-number" name="alt_phone_number" value="{{ $serviceRepresentative->alt_phone_number }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="service-rep-fax-number" class="form-label">Fax Number</label>
                                    <input type="text" class="form-control" id="service-rep-fax-number" name="fax_number" value="{{ $serviceRepresentative->fax_number }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="service-rep-email-address" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="service-rep-email-address" name="email" value="{{ $serviceRepresentative->email }}">
                                </div>

                            </div>

                            <div class="card-footer border-top-0">
                                <div class="d-flex pager wizard list-inline mb-0 justify-content-end">
                                    <button class="btn btn-primary" type="submit">
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Add any additional JavaScript here for handling form submission or validation
    </script>
@endpush
