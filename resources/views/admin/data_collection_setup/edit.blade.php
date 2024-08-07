@extends('layouts.care')

@section('content')
    <div class="col-12 col-xxl-8">
        <div class="card shadow-none border" data-component-card="data-component-card">
            <div class="card-header p-4 border-bottom bg-body">
                <div class="row g-3 justify-content-between align-items-center">
                    <div class="col-12 col-md">
                        <h4 class="text-body mb-0" id="vertical-wizard">
                            Data Collection Setup
                        </h4>
                    </div>
                </div>
            </div>

            <div class="card-body theme-wizard mb-0" data-theme-wizard="data-theme-wizard">
                <div class="row justify-content-between">
                    <div class="col-md-3 order-md-1">
                        <div class="scrollbar mb-4">
                            <ul class="nav justify-content-between flex-nowrap nav-wizard nav-wizard-vertical-md" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active py-0 py-md-3" href="#bootstrap-vertical-wizard-tab1" data-bs-toggle="tab" data-wizard-step="1" aria-selected="true" role="tab">
                                        <div class="text-center d-inline-block d-md-flex align-items-center gap-3">
                                            <span class="nav-item-circle-parent">
                                                <span class="nav-item-circle">
                                                    <svg class="svg-inline--fa fa-file nav-item-icon" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="file" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg="">
                                                        <path fill="currentColor" d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128z"></path>
                                                    </svg>
                                                    <!-- <span class="fa-solid fa-file nav-item-icon"></span> Font Awesome fontawesome.com -->
                                                    <svg class="svg-inline--fa fa-check check-icon" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                                        <path fill="currentColor" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"></path>
                                                    </svg>
                                                    <!-- <span class="fa-solid fa-check check-icon"></span> Font Awesome fontawesome.com -->
                                                </span>
                                            </span>
                                            <span class="nav-item-title fs-9 fs-xl-8">
                                                General
                                            </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link py-0 py-md-3" href="#bootstrap-vertical-wizard-tab2" data-bs-toggle="tab" data-wizard-step="2" aria-selected="false" tabindex="-1" role="tab">
                                        <div class="text-center d-inline-block d-md-flex align-items-center gap-3">
                                            <span class="nav-item-circle-parent">
                                                <span class="nav-item-circle">
                                                    <svg class="svg-inline--fa fa-location-dot nav-item-icon" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="location-dot" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg="">
                                                        <path fill="currentColor" d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"></path>
                                                    </svg>
                                                    <!-- <span class="fa-solid fa-location-dot nav-item-icon"></span> Font Awesome fontawesome.com -->
                                                    <svg class="svg-inline--fa fa-check check-icon" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                                        <path fill="currentColor" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"></path>
                                                    </svg>
                                                    <!-- <span class="fa-solid fa-check check-icon"></span> Font Awesome fontawesome.com -->
                                                </span>
                                            </span>
                                            <span class="nav-item-title fs-9 fs-xl-8">
                                                Measurement
                                            </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link py-0 py-md-3" href="#bootstrap-vertical-wizard-tab3" data-bs-toggle="tab" data-wizard-step="3" aria-selected="false" tabindex="-1" role="tab">
                                        <div class="text-center d-inline-block d-md-flex align-items-center gap-3">
                                            <span class="nav-item-circle-parent">
                                                <span class="nav-item-circle">
                                                    <svg class="svg-inline--fa fa-mug-saucer nav-item-icon" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="mug-saucer" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
                                                        <path fill="currentColor" d="M96 64c0-17.7 14.3-32 32-32H448h64c70.7 0 128 57.3 128 128s-57.3 128-128 128H480c0 53-43 96-96 96H192c-53 0-96-43-96-96V64zM480 224h32c35.3 0 64-28.7 64-64s-28.7-64-64-64H480V224zM32 416H544c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32z"></path></svg>
                                                    <!-- <span class="fa-solid fa-mug-saucer nav-item-icon"></span> Font Awesome fontawesome.com -->
                                                    <svg class="svg-inline--fa fa-check check-icon" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                                        <path fill="currentColor" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"></path>
                                                    </svg>
                                                    <!-- <span class="fa-solid fa-check check-icon"></span> Font Awesome fontawesome.com -->
                                                </span>
                                            </span>
                                            <span class="nav-item-title fs-9 fs-xl-8">
                                                Demodulation
                                            </span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link py-0 py-md-3" href="#bootstrap-vertical-wizard-tab4" data-bs-toggle="tab" data-wizard-step="4" aria-selected="false" tabindex="-1" role="tab">
                                        <div class="text-center d-inline-block d-md-flex align-items-center gap-3">
                                            <span class="nav-item-circle-parent">
                                                <span class="nav-item-circle">
                                                    <svg class="svg-inline--fa fa-images nav-item-icon" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="images" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                        <path fill="currentColor" d="M160 32c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H160zM396 138.7l96 144c4.9 7.4 5.4 16.8 1.2 24.6S480.9 320 472 320H328 280 200c-9.2 0-17.6-5.3-21.6-13.6s-2.9-18.2 2.9-25.4l64-80c4.6-5.7 11.4-9 18.7-9s14.2 3.3 18.7 9l17.3 21.6 56-84C360.5 132 368 128 376 128s15.5 4 20 10.7zM192 128a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zM48 120c0-13.3-10.7-24-24-24S0 106.7 0 120V344c0 75.1 60.9 136 136 136H456c13.3 0 24-10.7 24-24s-10.7-24-24-24H136c-48.6 0-88-39.4-88-88V120z"></path>
                                                    </svg>
                                                    <!-- <span class="fa-solid fa-images nav-item-icon"></span> Font Awesome fontawesome.com -->
                                                    <svg class="svg-inline--fa fa-check check-icon" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                                        <path fill="currentColor" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"></path>
                                                    </svg>
                                                    <!-- <span class="fa-solid fa-check check-icon"></span> Font Awesome fontawesome.com -->
                                                </span>
                                            </span>
                                            <span class="nav-item-title fs-9 fs-xl-8">
                                                Done
                                            </span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="tab-content">
                            <div class="tab-pane active" role="tabpanel" aria-labelledby="bootstrap-vertical-wizard-tab1" id="bootstrap-vertical-wizard-tab1">
                                <form id="wizardVerticalForm1" novalidate="novalidate" data-wizard-form="1">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="form-label text-body" for="bootstrap-vertical-wizard-wizard-setup-name">Setup Name</label>
                                        <input class="form-control" type="text" name="setup_name" placeholder="Setup Name" id="bootstrap-vertical-wizard-wizard-setup-name" value={{ $dataCollectionSetup->setup_name }}>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-body" for="bootstrap-vertical-wizard-wizard-cut-off-frequency">Cutoff Frequency</label>
                                        <select class="form-select" name="cutoff_frequency" id="bootstrap-vertical-wizard-wizard-cut-off-frequency">
                                            @foreach($cutoffFrequencies as $frequency)
                                                <option value="{{ $frequency }}"
                                                @if($general->cut_off_frequency == $frequency) selected @endif>{{ $frequency }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label" for="bootstrap-vertical-wizard-wizard-resolution">Resolution</label>
                                        <select class="form-select" name="resolution" id="bootstrap-vertical-wizard-wizard-resolution">
                                            @foreach($resolutions as $resolution)
                                                <option value="{{ $resolution }}"
                                                @if($general->resolution == $resolution) selected @endif
                                                >{{ $resolution }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col">
                                            <div class="mb-2 mb-sm-0">
                                                <label class="form-label text-body" for="bootstrap-vertical-wizard-wizard-transducer-type">Transducer Type</label>
                                                <select class="form-select" name="transducer_type" id="bootstrap-vertical-wizard-wizard-transducer-type">
                                                    @foreach($transducerTypes as $type)
                                                        <option value="{{ $type }}"
                                                        @if($general->transducer_type == $type) selected @endif>{{ $type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col">
                                            <div class="mb-2 mb-sm-0">
                                                <label class="form-label text-body" for="bootstrap-vertical-wizard-wizard-sensitivity">Sensitivity</label>
                                                <input class="form-control" type="text" name="sensitivity" placeholder="" id="bootstrap-vertical-wizard-wizard-sensitivity" value={{$general->sensitivity}}>
                                            </div>
                                        </div>

                                        <div class="col-auto d-flex justify-content-center align-items-center">
                                            <span class="nav-item-title fs-9 fs-xl-8">
                                                mV /
                                            </span>
                                        </div>

                                        <div class="col">
                                            <div class="mb-2">
                                                <label class="form-label text-body" for="bootstrap-vertical-wizard-wizard-sensitivity-unit">Unit</label>
                                                <select class="form-select" name="sensitivity_unit" id="bootstrap-vertical-wizard-wizard-sensitivity-unit">
                                                    @foreach($sensitivityUnits as $unit)
                                                        <option value="{{ $unit }}"
                                                        @if($general->unit == $unit) selected @endif>{{ $unit }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-sm-6">
                                        <div class="mb-2">
                                            <label class="form-label text-body" for="bootstrap-vertical-wizard-wizard-sensitivity-unit">Unit</label>
                                            <select class="form-select" name="sensitivity_unit" id="bootstrap-vertical-wizard-wizard-sensitivity-unit">
                                                @foreach($sensitivityUnits as $unit)
                                                    <option value="{{ $unit }}"
                                                    @if($general->unit == $unit) selected @endif>{{ $unit }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" role="tabpanel" aria-labelledby="bootstrap-vertical-wizard-tab2" id="bootstrap-vertical-wizard-tab2">
                                <form id="wizardVerticalForm2" novalidate="novalidate" data-wizard-form="2">
                                    @csrf
                                    <div class="mb-2 mb-sm-0">
                                        <label class="form-label text-body" for="bootstrap-vertical-wizard-wizard-average-type">Average Type</label>
                                        <select class="form-select" name="average_type" id="bootstrap-vertical-wizard-wizard-average-type">
                                            @foreach($averageTypes as $type)
                                                <option value="{{ $type }}"
                                                @if($measurement->average_type == $type) selected @endif>{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-2 mb-sm-0">
                                        <label class="form-label" for="bootstrap-vertical-wizard-wizard-no-of-averages">Number of Averages</label>
                                        <input class="form-control" type="text" name="number_of_averages" placeholder="" id="bootstrap-vertical-wizard-wizard-no-of-averages" value={{$measurement->number_of_averages}}>
                                    </div>

                                    <div class="mb-2 mb-sm-0">
                                        <label class="form-label text-body" for="bootstrap-vertical-wizard-wizard-average-overlap-percentage">Average overlap percentage</label>
                                        <select class="form-select" name="average_overlap_percentage" id="bootstrap-vertical-wizard-wizard-average-overlap-percentage">
                                            @foreach($averageOverlapPercentages as $percentage)
                                                <option value="{{ $percentage }}"
                                                @if($measurement->average_overlap_percentage == $percentage) selected @endif>{{ $percentage }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-2 mb-sm-0">
                                        <label class="form-label text-body" for="bootstrap-vertical-wizard-wizard-window-type">Window Type</label>
                                        <select class="form-select" name="window_type" id="bootstrap-vertical-wizard-wizard-window-type">
                                            @foreach($windowTypes as $type)
                                                <option value="{{ $type }}"
                                                @if($measurement->window_type == $type) selected @endif>{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" role="tabpanel" aria-labelledby="bootstrap-vertical-wizard-tab3" id="bootstrap-vertical-wizard-tab3">
                                <form id="wizardVerticalForm3" novalidate="novalidate" data-wizard-form="3">
                                    @csrf
                                    <div class="mb-2">
                                        <input type="checkbox" id="impact-demodulation" name="impact_demodulation"
                                        @if($demodulation->filter_type == "HighPassFilter") checked @endif>
                                        <label class="form-label text-body" for="impact-demodulation">Impact Demodulation</label>
                                    </div>

                                    <div class="mb-2" id="high-pass-filter-group">
                                        <label class="form-label text-body" for="high-pass-filter">High Pass Filter</label>
                                        <select class="form-select" name="high_pass_filter" id="high-pass-filter">
                                            @foreach($highPassFilters as $filter)
                                                    <option value="{{ $filter }}"
                                                            @if($demodulation->filter_value == $filter) selected @endif>{{ $filter }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-2" id="band-pass-filter-group">
                                        <label class="form-label text-body" for="band-pass-filter">Band Pass Filter</label>
                                        <select class="form-select" name="band_pass_filter" id="band-pass-filter" >
                                            @foreach($bandPassFilters as $filter)
                                                <option value="{{ $filter }}"
                                                @if($demodulation->filter_value == $filter) selected @endif>{{ $filter }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" role="tabpanel" aria-labelledby="bootstrap-vertical-wizard-tab4" id="bootstrap-vertical-wizard-tab4">
                                <div class="row flex-center pb-8 pt-4 gx-3 gy-4">
                                    <div class="col-12 col-sm-auto">
                                        <div class="text-center text-sm-start">
                                            <img class="d-dark-none" src="../../assets/img/spot-illustrations/38.webp" alt="" width="220">
                                            <img class="d-light-none" src="../../assets/img/spot-illustrations/dark_38.webp" alt="" width="220">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-auto">
                                        <div class="text-center text-sm-start">
                                            <h5 class="mb-3">You are all set!</h5>
                                            <p class="text-body-emphasis fs-9">
                                                Now you can access your account<br>anytime anywhere
                                            </p>
                                            <a class="btn btn-primary px-6" href="../../modules/forms/wizard.html">
                                                Start Over
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer border-top-0" data-wizard-footer="data-wizard-footer">
                    <div class="d-flex pager wizard list-inline mb-0">
                        <button class="d-none btn btn-link ps-0" type="button" data-wizard-prev-btn="data-wizard-prev-btn">
                            <svg class="svg-inline--fa fa-chevron-left me-1" data-fa-transform="shrink-3" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="" style="transform-origin: 0.3125em 0.5em;">
                                <g transform="translate(160 256)">
                                    <g transform="translate(0, 0)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                        <path fill="currentColor" d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z" transform="translate(-160 -256)"></path>
                                    </g>
                                </g>
                            </svg>
                            <!-- <span class="fas fa-chevron-left me-1" data-fa-transform="shrink-3"></span> Font Awesome fontawesome.com -->
                            Previous
                        </button>
                        <div class="flex-1 text-end">
                            <button class="btn btn-primary px-6 px-sm-6" type="submit" data-wizard-next-btn="data-wizard-next-btn">
                                Next
                                <svg class="svg-inline--fa fa-chevron-right ms-1" data-fa-transform="shrink-3" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="" style="transform-origin: 0.3125em 0.5em;">
                                    <g transform="translate(160 256)">
                                        <g transform="translate(0, 0)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                            <path fill="currentColor" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z" transform="translate(-160 -256)"></path>
                                        </g>
                                    </g>
                                </svg>
                                <!-- <span class="fas fa-chevron-right ms-1" data-fa-transform="shrink-3"> </span> Font Awesome fontawesome.com -->
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection()

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const impactDemodCheckbox = document.getElementById("impact-demodulation");
            const highPassFilterGroup = document.getElementById("high-pass-filter-group");
            const bandPassFilterGroup = document.getElementById("band-pass-filter-group");

            impactDemodCheckbox.addEventListener("change", function () {
                if (impactDemodCheckbox.checked) {
                    highPassFilterGroup.style.display = "block";
                    bandPassFilterGroup.style.display = "none";
                } else {
                    highPassFilterGroup.style.display = "none";
                    bandPassFilterGroup.style.display = "block";
                }
            });

            // Initial setup based on the checkbox state
            if (impactDemodCheckbox.checked) {
                highPassFilterGroup.style.display = "block";
                bandPassFilterGroup.style.display = "none";
            } else {
                highPassFilterGroup.style.display = "none";
                bandPassFilterGroup.style.display = "block";
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Select the form data and buttons
            const form1 = document.querySelector('#wizardVerticalForm1');
            const form2 = document.querySelector('#wizardVerticalForm2');
            const form3 = document.querySelector('#wizardVerticalForm3');
            const nextBtn = document.querySelector('[data-wizard-next-btn]');
            const prevBtn = document.querySelector('[data-wizard-prev-btn]');
            const wizardStep1 = document.querySelector('[data-wizard-step="1"]');
            const wizardStep2 = document.querySelector('[data-wizard-step="2"]');
            const wizardStep3 = document.querySelector('[data-wizard-step="3"]');

            nextBtn.addEventListener('click', function (e) {
                e.preventDefault();

                console.log('Next button clicked');
                console.log('Current wizard step states:');
                console.log('Step 1 active:', wizardStep1.classList.contains('active'));
                console.log('Step 2 active:', wizardStep2.classList.contains('active'));
                console.log('Step 3 active:', wizardStep3.classList.contains('active'));

                let formData;
                let url;
                let method;

                if (!wizardStep1.classList.contains('active') && wizardStep2.classList.contains('active') && !wizardStep3.classList.contains('active')) {
                    formData = new FormData(form1);
                    const setupId = {{ $dataCollectionSetup->id }};
                    url = '/api/admin/data-setup/general/' + setupId;
                    method = 'PUT';
                    const csrfToken = '{{ csrf_token() }}';

                    $.ajax({
                        url: url,
                        type: method,
                        data: JSON.stringify(Object.fromEntries(formData)), // Convert FormData to JSON
                        contentType: 'application/json',  // Set content type to JSON
                        headers: {
                            'X-CSRF-TOKEN': csrfToken  // Include CSRF token in headers
                        },
                        success: function (response) {
                            // Code to execute on success
                            console.log("Success:", response);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            // Code to execute on error
                            console.error("Error:", textStatus, errorThrown);
                        }
                    });
                } else if (!wizardStep1.classList.contains('active') && !wizardStep2.classList.contains('active') && wizardStep3.classList.contains('active')) {
                    formData = new FormData(form2);
                    const setupId = {{ $dataCollectionSetup->id }};
                    url = '/api/admin/data-setup/measurement/' + setupId;
                    method = 'PUT';
                    const csrfToken = '{{ csrf_token() }}';

                    $.ajax({
                        url: url,
                        type: method,
                        data: JSON.stringify(Object.fromEntries(formData)), // Convert FormData to JSON
                        contentType: 'application/json',  // Set content type to JSON
                        headers: {
                            'X-CSRF-TOKEN': csrfToken  // Include CSRF token in headers
                        },
                        success: function (response) {
                            // Code to execute on success
                            console.log("Success:", response);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            // Code to execute on error
                            console.error("Error:", textStatus, errorThrown);
                        }
                    });
                } else if (!wizardStep1.classList.contains('active') && !wizardStep2.classList.contains('active') && !wizardStep3.classList.contains('active')) {
                    formData = new FormData(form3);
                    const setupId = {{ $dataCollectionSetup->id }};
                    url = '/api/admin/data-setup/demodulation/' + setupId;
                    method = 'PUT';
                    const csrfToken = '{{ csrf_token() }}';

                    $.ajax({
                        url: url,
                        type: method,
                        data: JSON.stringify(Object.fromEntries(formData)), // Convert FormData to JSON
                        contentType: 'application/json',  // Set content type to JSON
                        headers: {
                            'X-CSRF-TOKEN': csrfToken  // Include CSRF token in headers
                        },
                        success: function (response) {
                            // Code to execute on success
                            console.log("Success:", response);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            // Code to execute on error
                            console.error("Error:", textStatus, errorThrown);
                        }
                    });
                } else {
                    console.log('No matching wizard step condition.');
                    return;
                }

                console.log('Preparing to send data to:', url);
                const jsonData = JSON.stringify(Object.fromEntries(formData));
                console.log('FormData converted to JSON:', jsonData);
            });

            // Optional: Add event listener for prevBtn if needed
            prevBtn.addEventListener('click', function (e) {
                e.preventDefault();
                console.log('Previous button clicked');
                // Handle previous button functionality if required
            });
        });
    </script>
@endpush
