<div class="card shadow-none border" data-component-card="data-component-card">
    <div class="card-header p-4 border-bottom bg-body">
        <div class="row g-3 justify-content-between align-items-center">
            <div class="col col-md">
                <h4 class="text-body mb-0">
                    {{ ucwords($company->company_name) }}
                </h4>
            </div>
            <div class="col-12 col-md text-md-end">
                <a class="float-md-end" href="{{ route('plant.create',['id' => $company->id]) }}">
                    <span class="fas fa-plus me-2"></span>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                <tr>
                    <th scope="col">Plant Name</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="align-middle text-end white-space-nowrap text-body-tertiary">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($plants as $row)
                    <tr>
                        <td>{{$row->title}}</td>
                        <td>{{$row->status === 1 ? 'Active' : 'Inactive'}}</td>
                        <td class="align-middle text-end white-space-nowrap text-body-tertiary">
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
                                    <a class="dropdown-item" href="{{ route('plant.edit', $row->id) }}">Edit</a>
                                    <a class="dropdown-item" href="javascript:void(0)" data-id="{{ $row->id }}" onclick="openShowPlantModal(event, {{ $row->id }})">Show</a>

                                    <button class="dropdown-item" onclick="openLinkServiceRepModal({{$row->id}})">Link Service Representative</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
