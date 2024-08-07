@extends('layouts.care')

@section('content')
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li class="breadcrumb-item active">Setup</li>
        </ol>
    </nav>

    <h2 class="text-bold text-body-emphasis mb-5">Setup</h2>

    <div id="setups" data-list='{"valueNames":["setup"],"page":10,"pagination":true}'>
        <div class="row align-items-center justify-content-between g-3 mb-4">
            <div class="col col-auto">
                <div class="search-box">
                    <form class="position-relative">
                        <input class="form-control search-input search" type="search" placeholder="Search Setup"
                               aria-label="Search"/>
                        <span class="fas fa-search search-box-icon"></span>
                    </form>
                </div>
            </div>

            <div class="col-auto">
                <div class="d-flex align-items-center">
                    <button class="btn btn-link text-body me-4 px-0">
                        <span class="fa-solid fa-file-export fs-9 me-2"></span>Export
                    </button>
                    <a class="btn btn-primary" href="{{ route('setup.create') }}">
                        <span class="fas fa-plus me-2"></span>Add Setup
                    </a>
                </div>
            </div>
        </div>

        <div class="mx-n4 mx-lg-n6 px-4 px-lg-6 mb-9 bg-body-emphasis border-y mt-2 position-relative top-1">
            <div class="table-responsive scrollbar ms-n1 ps-1">
                <table class="table table-sm fs-9 mb-0">
                    <thead>
                    <tr>
                        <th class="sort align-middle" scope="col" data-sort="setup" style="width:15%; min-width:200px;">
                            Setup Name
                        </th>
{{--                        <th class="sort align-middle" scope="col" data-sort="email" style="width:15%; min-width:200px;">--}}
{{--                            EMAIL--}}
{{--                        </th>--}}
{{--                        <th class="sort align-middle pe-3" scope="col" data-sort="status"--}}
{{--                            style="width:20%; min-width:200px;">--}}
{{--                            STATUS--}}
{{--                        </th>--}}
{{--                        <th class="sort align-middle" scope="col" data-sort="role" style="width:10%;">--}}
{{--                            ROLE--}}
{{--                        </th>--}}
                        <th class="sort align-middle text-end" scope="col" style="width:21%;  min-width:200px;">
                            ACTIONS
                        </th>
                    </tr>
                    </thead>
                    <tbody class="list" id="setups-table-body">
                    @foreach($setups as $row)
                        <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                            <td class="align-middle white-space-nowrap">
{{--                                @php--}}
{{--                                    $src = url('assets/img/users/user1.png');--}}
{{--                                    if($row->avatar)--}}
{{--                                        $src = Storage::url($row->avatar);--}}
{{--                                @endphp--}}
                                <a class="d-flex align-items-center text-body text-hover-1000 ps-2" href="#">
{{--                                    <div class="avatar avatar-m">--}}
{{--                                        <img class="rounded-circle" src="{{ $src }}" alt="">--}}
{{--                                    </div>--}}
                                    <h6 class="mb-0 ms-3 fw-semibold">{{ $row->setup_name }}</h6>
                                </a>
                            </td>
{{--                            <td class="email align-middle white-space-nowrap">--}}
{{--                                <a class="fw-semibold" href="mailto:{{ $row->email }}">{{ $row->email }}</a>--}}
{{--                            </td>--}}
{{--                            <td class="mobile_number align-middle white-space-nowrap">--}}
{{--                                @if($row->status)--}}
{{--                                    <span class="badge badge-phoenix fs-10 badge-phoenix-success">--}}
{{--                                    <span class="badge-label">Active</span>--}}
{{--                                </span>--}}
{{--                                @else--}}
{{--                                    <span class="badge badge-phoenix fs-10 badge-phoenix-warning">--}}
{{--                                    <span class="badge-label">Blocked</span>--}}
{{--                                </span>--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                            <td class="city align-middle white-space-nowrap text-body">--}}
{{--                                {{ $row->role->title }}--}}
{{--                            </td>--}}
                            <td class="last_active align-middle text-end white-space-nowrap text-body-tertiary">
                                <div class="btn-reveal-trigger position-static">
                                    <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs-10"
                                            type="button" data-bs-toggle="dropdown" data-boundary="window"
                                            aria-haspopup="true" aria-expanded="false" data-bs-reference="parent">
                                        <svg class="svg-inline--fa fa-ellipsis fs-10" aria-hidden="true"
                                             focusable="false" data-prefix="fas" data-icon="ellipsis" role="img"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                            <path fill="currentColor"
                                                  d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end py-2" style="">
                                        <a class="dropdown-item" href="{{ route('setup.edit', $row->id) }}">Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0)" data-id="{{ $row->id }}" onclick="openModal(event, {{ $row->id }})">Show</a>
                                        <a class="dropdown-item" href="javascript:void(0)">Copy</a>
                                        <form id="update-status-{{ $row->id }}"
                                              action="{{ route('users.status', $row->id) }}" method="POST"
                                              style="display:none;">
                                            @csrf
                                            @method("PUT")
                                            <input type="hidden" name="status" value="{{ !$row->status }}">
                                        </form>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="#!">Remove</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row align-items-center justify-content-between py-2 pe-0 fs-9">
                <div class="col-auto d-flex">
                    <p class="mb-0 d-none d-sm-block me-3 fw-semibold text-body" data-list-info="data-list-info"></p>
                    <a class="fw-semibold" href="#!" data-list-view="*">View all<span class="fas fa-angle-right ms-1"
                                                                                      data-fa-transform="down-1"></span></a><a
                        class="fw-semibold d-none" href="#!" data-list-view="less">View Less<span
                            class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                </div>
                <div class="col-auto d-flex">
                    <button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span>
                    </button>
                    <ul class="mb-0 pagination"></ul>
                    <button class="page-link pe-0" data-list-pagination="next"><span
                            class="fas fa-chevron-right"></span></button>
                </div>
            </div>
        </div>
    </div>

    @include('admin.data_collection_setup.show');

@endsection

@push("scripts")
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

        function openModal(event, id) {
            event.preventDefault(); // Prevent the default action of the link

            // Perform AJAX request to fetch data for the given id
            fetch('/api/admin/data-setup/' + id + '/details')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(data);
                        const setup = data.data.setup;
                        const general = data.data.general;
                        const measurement = data.data.measurement;
                        const demodulation = data.data.demodulation;

                        // Populate the fields for Form 1 (Setup and General)
                        document.querySelector('#bootstrap-vertical-wizard-wizard-setup-name').value = setup.setup_name;
                        document.querySelector('#bootstrap-vertical-wizard-wizard-cut-off-frequency').value = general.cut_off_frequency;
                        document.querySelector('#bootstrap-vertical-wizard-wizard-resolution').value = general.resolution;
                        document.querySelector('#bootstrap-vertical-wizard-wizard-transducer-type').value = general.transducer_type;
                        document.querySelector('#bootstrap-vertical-wizard-wizard-sensitivity').value = general.sensitivity;
                        document.querySelector('#bootstrap-vertical-wizard-wizard-sensitivity-unit').value = general.unit;

                        // Populate the fields for Form 2 (Measurement)
                        document.querySelector('#bootstrap-vertical-wizard-wizard-average-type').value = measurement.average_type;
                        document.querySelector('#bootstrap-vertical-wizard-wizard-no-of-averages').value = measurement.number_of_averages;
                        document.querySelector('#bootstrap-vertical-wizard-wizard-average-overlap-percentage').value = measurement.average_overlap_percentage;
                        document.querySelector('#bootstrap-vertical-wizard-wizard-window-type').value = measurement.window_type;

                        // Populate the fields for Form 3 (Demodulation)
                        document.querySelector('#impact-demodulation').checked = demodulation.filter_type === "HighPassFilter";
                        document.querySelector('#high-pass-filter').value = demodulation.filter_value;
                        document.querySelector('#band-pass-filter').value = demodulation.filter_value;

                        // Show the modal
                        var modal = new bootstrap.Modal(document.getElementById('show-data-collection-setup'), {});
                        modal.show();
                    } else {
                        alert(data.message); // Show error message
                    }
                })
                .catch(error => {
                    console.error('Error fetching setup details:', error);
                    alert('An error occurred while fetching setup details.');
                });
        }
    </script>
@endpush
