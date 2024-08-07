@extends('layouts.care')

@section('content')
    <div class="col-12 col-xxl-8">
        <div class="card shadow-none border" data-component-card="data-component-card">
            <div class="card-header p-4 border-bottom bg-body">
                <div class="row g-3 justify-content-between align-items-center">
                    <div class="col-12 col-md">
                        <h4 class="text-body mb-0" id="vertical-wizard">
                            Create Service Representative
                        </h4>
                    </div>
                </div>
            </div>

            <div class="card-body pt-4 pb-0">
                <div class="row justify-content-between">
                    <div class="col-md-8">
                        <form id="wizardVerticalForm4" action="{{ route('service-reps.store') }}" method="POST" novalidate="novalidate">
                            @csrf
                            <div class="row g-3" id="service-rep-info">
                                <div class="col-md-12">
                                    <label for="service-rep-name" class="form-label">Service Rep Name</label>
                                    <input type="text" class="form-control" id="service-rep-name" name="service_rep_name" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="service-rep-address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="service-rep-address" name="address" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="service-rep-city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="service-rep-city" name="city" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="service-rep-state" class="form-label">State/Province</label>
                                    <input type="text" class="form-control" id="service-rep-state" name="state" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="service-rep-zip" class="form-label">Zip/Post Code</label>
                                    <input type="text" class="form-control" id="service-rep-zip" name="zip" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="service-rep-country" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="service-rep-country" name="country" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="service-rep-contact-name" class="form-label">Contact Name</label>
                                    <input type="text" class="form-control" id="service-rep-contact-name" name="contact_name" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="service-rep-contact-title" class="form-label">Contact Title</label>
                                    <input type="text" class="form-control" id="service-rep-contact-title" name="contact_title">
                                </div>
                                <div class="col-md-6">
                                    <label for="service-rep-phone-number" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="service-rep-phone-number" name="phone_number" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="service-rep-alt-phone-number" class="form-label">Alt Phone Number</label>
                                    <input type="text" class="form-control" id="service-rep-alt-phone-number" name="alt_phone_number">
                                </div>
                                <div class="col-md-6">
                                    <label for="service-rep-fax-number" class="form-label">Fax Number</label>
                                    <input type="text" class="form-control" id="service-rep-fax-number" name="fax_number">
                                </div>
                                <div class="col-md-6">
                                    <label for="service-rep-email-address" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="service-rep-email-address" name="email" autocomplete="off" onfocusout="CheckEmail(this)" required="" data-original-title="" title="" style="background-color:pink;">
                                </div>
                            </div>

                            <div class="card-footer border-top-0">
                                <div class="d-flex pager wizard list-inline mb-0 justify-content-end">
                                    <button class="btn btn-primary" type="submit">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection()

@push('scripts')
    <script>
        function CheckEmail(ctrl)
        {
            let email = $(ctrl).val();
            $.post("/api/check-email/" + email, function(response)
            {
                if(response.check === "N")
                {
                    $(ctrl).tooltip({title: 'This email don\'t match our records'}).tooltip('show');
                    $(ctrl).css("background-color", "pink");
                    console.log('This email don\'t match our records');
                }
                else
                {
                    $(ctrl).css("background-color", "#DAF7A6");
                    console.log('This email match our records');
                }
            });
        }
    </script>
@endpush
