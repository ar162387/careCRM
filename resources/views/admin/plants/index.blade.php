@extends('layouts.care')

@section('content')
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Companies</li>
        </ol>
    </nav>

    <div class="mb-5">
        <h2 class="text-bold text-body-emphasis">Manage Companies</h2>
    </div>

    <div class="mb-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-none border" data-component-card="data-component-card">
                    <div class="card-header p-4 border-bottom bg-body">
                        <div class="row g-3 justify-content-between align-items-center">
                            <div class="col-12 col-md">
                                <h4 class="text-body mb-0">
                                    Companies
                                </h4>
                            </div>
                            <div class="col-12 col-md">
                                <a class="float-md-end" href="{{ route('company.create') }}">
                                    <span class="fas fa-plus me-2"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group">
                            @foreach($companies as $row)
                                <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                   data-id="{{ $row->id }}">
                                    {{ ucwords($row->company_name) }}
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8" id="handlers-data">
                <div class="card shadow-none border" data-component-card="data-component-card">
                    <div class="card-header p-4 border-bottom bg-body">
                        <div class="row g-3 justify-content-between align-items-center">
                            <div class="col-12 col-md">
                                <h4 class="text-body mb-0">
                                    Company Name
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Link Service Representative Modal -->
    <div class="modal fade" id="linkServiceRepModal" tabindex="-1" aria-labelledby="linkServiceRepModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="linkServiceRepModalLabel">Link Service Representative</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="linkServiceRepForm">
                        @csrf
                        <input type="hidden" name="plant_id" id="plant_id">
                        <div class="mb-2">
                            <label for="service_rep_id" class="form-label">Select Service Representative</label>

                            <select class="form-select" name="service_rep" id="service-rep-link" required>
                                <option value="">Select Service Representative</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Link Service Representative</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin.plants.plant-show')


@endsection

@push("scripts")
    <script>
        $(".list-group-item").on("click", function() {
            let id = $(this).data('id');
            $(".list-group-item-success").removeClass('list-group-item-success');
            $(this).addClass('list-group-item-success');

            $.get(`{{ url('/api/plant/fetch-plants') }}/${id}`, function(response) {
                $("#handlers-data").html(response);
            });
        });

        function openLinkServiceRepModal(plantId) {
            $.get(`/api/plant/fetch-plant-service-rep/${plantId}`, function(response) {
                let serviceReps = response.serviceReps;
                let serviceRepsAll = response.serviceRepsAll;

                serviceReps.forEach(function(serviceRep) {
                    serviceRepsAll = serviceRepsAll.filter(function(item) {
                        return item.id !== serviceRep.id;
                    });
                });

                $('#service-rep-link').empty();
                serviceRepsAll.forEach(function(serviceRep) {
                    $('#service-rep-link').append(`<option value="${serviceRep.id}">${serviceRep.service_rep_name}</option>`);
                });
            });

            $("#plant_id").val(plantId);
            $("#linkServiceRepModal").modal("show");
        }

        $("#linkServiceRepForm").on("submit", function(event) {
            event.preventDefault();
            $plantId = $("#plant_id").val();
            $serviceRepIds = [];
            $serviceRepIds.push($("#service-rep-link").val());
            $.post("{{ url('/api/service-rep/link-service-rep') }}",
                {service_rep_ids: $serviceRepIds, plant_id: $plantId}
                , function(response) {
                console.log(response);
                if (response.success) {
                    $("#linkServiceRepForm")[0].reset();
                    $("#linkServiceRepModal").modal("hide");
                } else {
                    // Handle errors
                }
            });
        });

        function openShowPlantModal(event, plantId) {
            event.preventDefault();
            $.get(`/api/plant/fetch-plant/${plantId}`, function(response) {
                $("#plant-name").val(response.plant.title);
                $("#plant-status").val(response.plant.status === 1 ? 'Active' : 'Inactive');
                $('#notes').val(response.note.note);

                $('#service-rep').empty();

                response.serviceReps.forEach(function(serviceRep) {
                    $('#service-rep').append(`<option value="${serviceRep.id}">${serviceRep.service_rep_name}</option>`);
                });

                var modal = new bootstrap.Modal(document.getElementById('show-data-collection-setup'), {});
                modal.show();
            });
        }
    </script>
@endpush

