@extends('layouts.care')

@section('content')

    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li class="breadcrumb-item active">Data Files</li>
        </ol>
    </nav>
    <h2 class="text-bold text-body-emphasis mb-5">Data Files</h2>
    <div id="files" data-list='{"valueNames":["title","device","site","factory"],"page":10,"pagination":true}'>
        <div class="row align-items-center justify-content-between g-3 mb-4">
            <div class="col col-auto">
                <div class="search-box">
                    <form class="position-relative">
                        <input class="form-control search-input search" type="search" placeholder="Search files" aria-label="Search" />
                        <span class="fas fa-search search-box-icon"></span>
                    </form>
                </div>
            </div>

            <div class="col-auto">
                <div class="d-flex align-items-center">
                    <a href="javascript:void(0)" class="btn btn-primary"
                       data-bs-toggle="modal" data-bs-target=".bd-add-modal-lg">
                        <span class="fas fa-plus me-2"></span>Add file
                    </a>
                </div>
            </div>
        </div>

        <div class="mx-n4 mx-lg-n6 px-4 px-lg-6 mb-9 bg-body-emphasis border-y mt-2 position-relative top-1">
            <div class="table-responsive scrollbar ms-n1 ps-1">
                <table class="table table-sm fs-9 mb-0">
                    <thead>
                    <tr>
                        <th class="sort align-middle" scope="col" data-sort="title" style="width:15%; min-width:200px;">
                            FILE TITLE
                        </th>
                        <th class="sort align-middle" scope="col" data-sort="device" style="width:15%; min-width:200px;">
                            DEVICE
                        </th>
                        <th class="sort align-middle pe-3" scope="col" data-sort="site" style="width:20%; min-width:200px;">
                            SITE
                        </th>
                        <th class="sort align-middle" scope="col" data-sort="factory" style="width:10%;">
                            FACTORY
                        </th>
                        <th class="sort align-middle" scope="col" data-sort="factory" style="width:10%;">
                            UPLOADED
                        </th>
                        <th class="sort align-middle text-end" scope="col" style="width:21%;  min-width:200px;">
                            ACTIONS
                        </th>
                    </tr>
                    </thead>
                    <tbody class="list" id="files-table-body">
                    @foreach($files as $row)
                        <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                            <td class="title align-middle white-space-nowrap text-body">
                                {{ $row->file_name }}
                            </td>
                            <td class="device align-middle white-space-nowrap text-body">
                                {{ $row->device->serial_number }}
                            </td>
                            <td class="site align-middle white-space-nowrap text-body">
                                {{ $row->site->title }}
                            </td>
                            <td class="factory align-middle white-space-nowrap text-body">
                                {{ $row->site->factory->title }}
                            </td>
                            <td class="factory align-middle white-space-nowrap text-body" title="{{ $row->created_at }}">
                                {{ $row->created_at->diffForHumans() }}
                            </td>
                            <td class="last_active align-middle text-end white-space-nowrap text-body-tertiary">
                                <div class="btn-reveal-trigger position-static">
                                    <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs-10"
                                            type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent">
                                        <svg class="svg-inline--fa fa-ellipsis fs-10" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                            <path fill="currentColor" d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end py-2" style="">
                                        <a class="dropdown-item" href="{{ route('users.edit', $row->id) }}"
                                           data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                            Edit
                                        </a>
                                        <a class="dropdown-item" href="{{ route('files.download', $row->id) }}"
                                           data-bs-toggle="tooltip" data-bs-placement="top" title="Download">
                                            Download
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="tooltip"
                                           data-bs-placement="top" title="Replace" onclick="OpenReplaceModal({{ $row->id }})">
                                            Replace
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="javascript:void(0)" data-bs-toggle="tooltip"
                                           data-bs-placement="top" title="Remove File" onclick="deleteFile(this, {{ $row->id }})" >
                                            Remove
                                        </a>
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
                    <a class="fw-semibold" href="#!" data-list-view="*">View all<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a><a class="fw-semibold d-none" href="#!" data-list-view="less">View Less<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                </div>
                <div class="col-auto d-flex"><button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                    <ul class="mb-0 pagination"></ul><button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-add-modal-lg" tabindex="-1" data-bs-backdrop="static" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New File</h5>
                    <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fas fa-times fs-9"></span>
                    </button>
                </div>
                <form id="posted" method="POST" action="" class="row g-3 needs-validation"
                      enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-body px-5">
                        <div class="mb-3">
                            <label class="form-label" for="factory_id">Factory</label>
                            <select class="form-select" id="factory_id" data-choices="data-choices"
                                    data-options='{"removeItemButton":true,"placeholder":true}' required>
                                <option value="">Select Factory</option>
                                @foreach($factories as $factory)
                                    <option value="{{ $factory->id }}">{{ $factory->title }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Select a factory name...</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="site_id">Site</label>
                            <select class="form-select" id="site_id" name="site_id" required>
                                <option value="">Select Site</option>
                            </select>
                            <div class="invalid-feedback">Select a site name...</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="component_id">Component (optional)</label>
                            <select class="form-select" id="component_id" name="component_id"
                                    data-choices="data-choices" data-options='{"removeItemButton":true,"placeholder":true}'>
                                <option value="">Not Applicable</option>
                            </select>
                            <div class="invalid-feedback">Select a component name...</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="device_serial">Device</label>
                            <select class="form-select" id="device_serial" name="device_serial" data-choices="data-choices"
                                    data-options='{"removeItemButton":true,"placeholder":true}' required>
                                <option value="">Select Device</option>
                                @foreach($devices as $device)
                                    <option value="{{ $device->serial_number }}">{{ $device->serial_number }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Select a device name...</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="data-file">Data file</label>
                            <input type="file" class="form-control" name="file" id="data-file" required>
                            <div class="invalid-feedback">Data file needs to be selected.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Upload Data</button>
                        <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade bd-replace-modal-lg" tabindex="-1" data-bs-backdrop="static" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="staticBackdropLabel">Replace File</h5>
                    <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fas fa-times fs-9"></span>
                    </button>
                </div>
                <form id="replace-form" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="record-id" name="id" value="">
                        <div class="mb-3">
                            <label class="form-label" for="data-file">Data file</label>
                            <input type="file" class="form-control" name="file" id="data-file" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Replace File</button>
                        <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function deleteFile(ctrl, id) {
            if (confirm('Are you sure to delete this file?')) {
                fetch(`{{ url('data') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                    alert(data.message);
                    $(ctrl).closest('tr').hide().remove();
                });
            }
        }

        $("#factory_id").on("change", function() {
            var id = $(this).val();
            $("#site_id").empty();
            fetch(`{{ url('api/factories?id=') }}${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response is not OK');
                    }
                    return response.json();
                })
                .then(data => {
                    $("#site_id").append(`<option value=''>Select Site</option>`);
                    console.log(data.sites);
                    data.sites.forEach((item, index) => {
                        $("#site_id").append(`<option value='${item.id}'>${item.title}</option>`);
                    });
                })
                .catch(error => {
                    alert('There was a problem with the fetch operation:' + error);
                });
        });

        $("#site_id").on("change", function() {
            var id = $(this).val();
            $("#component_id").empty();
            fetch(`{{ url('api/sites?id=') }}${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response is not OK');
                    }
                    return response.json();
                })
                .then(data => {
                    $("#component_id").append(`<option value=''>Not Applicable</option>`);
                    data.components.forEach((item, index) => {
                        $("#component_id").append(`<option value='${item.id}'>${item.title}</option>`);
                    });
                })
                .catch(error => {
                    alert('There was a problem with the fetch operation:' + error);
                });
        });

        const form = document.querySelector('#posted');
        form.addEventListener("submit", (event) => {
            event.preventDefault();

            const formData = new FormData(form);
            fetch("{{ url('api/data/upload') }}", {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                });
        });

        function OpenReplaceModal(id)
        {
            $("#record-id").val(id);
            $(".bd-replace-modal-lg").modal('show');
        }

        const form2 = document.querySelector('#replace-form');
        form2.addEventListener("submit", (event) => {
            event.preventDefault();

            const formData = new FormData(form2);
            fetch(`{{ url('api/data/replace') }}`, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                });
        });
    </script>
@endpush
