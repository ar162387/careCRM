@extends('layouts.care')

@section('content')
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Sensor Data</li>
        </ol>
    </nav>

    <div class="mb-5">
        <h2 class="text-bold text-body-emphasis">Sensor Data</h2>
        <p class="text-body-tertiary lead">
            Generate Plots from Sensor Data
        </p>
    </div>

    <form id="plotForm" method="POST">
        @csrf
        <div class="mb-4">
            <label for="data_file_id" class="form-label">Please select File ID:</label>
            <select class="form-select" id="data_file_id" name="file_id" onchange="checkSelections()">
                <option value="">Select File ID</option>
                @foreach($dataFileIds as $fileId)
                    <option value="{{ $fileId->data_file_id }}">{{ $fileId->data_file_id }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="column" class="form-label">Please select the specified column for plot Generation:</label>
            <select class="form-select" id="column" name="column" disabled onchange="checkSelections()">
                <option value="">Select Column</option>
                <option value="X">X</option>
                <option value="Y">Y</option>
                <option value="Z">Z</option>
                <option value="combined-time">Combined Time Domain</option>
                <option value="combined-frequency">Combined Frequency Domain</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" id="generate-plot" disabled>Generate Plot</button>
    </form>

    <script>
        function checkSelections() {
            const fileId = document.getElementById('data_file_id').value;
            const column = document.getElementById('column').value;
            const columnSelect = document.getElementById('column');
            const generatePlotButton = document.getElementById('generate-plot');
            const plotForm = document.getElementById('plotForm');
            
            if (fileId) {
                columnSelect.removeAttribute('disabled');
            } else {
                columnSelect.setAttribute('disabled', 'disabled');
            }

            if (fileId && column) {
                generatePlotButton.removeAttribute('disabled');
                if (column === 'combined-time') {
                    plotForm.action = '{{ route('sensor_data.generate_time_domain_plot') }}';
                } else if (column === 'combined-frequency') {
                    plotForm.action = '{{ route('sensor_data.generate_frequency_domain_plot') }}';
                } else {
                    plotForm.action = '{{ route('sensor_data.generate_plot') }}';
                }
            } else {
                generatePlotButton.setAttribute('disabled', 'disabled');
            }
        }
    </script>
@endsection
