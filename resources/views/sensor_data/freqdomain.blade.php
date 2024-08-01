@extends('layouts.care')

@section('content')
<div class="container">
    <div class="text-center mb-3">
        <h1>Frequency Domain Combined Plots</h1>
    </div>
    <!-- Frequency Domain Plot for X -->
    <div class="mb-5">
        <h2 class="text-center">Frequency Domain Plot For X</h2>
        <div style="border: 1px solid #ddd; padding: 5px; display: flex; flex-direction: column; margin-bottom: 5px;">
            <p style="margin: 0;">Global Max: <span id="globalMaxFreqX"></span></p>
            <p style="margin: 0;">Global Min: <span id="globalMinFreqX"></span></p>
            <p style="margin: 0;">Current Local Max: <span id="currentLocalMaxFreqX"></span></p>
            <p style="margin: 0;">Current Local Min: <span id="currentLocalMinFreqX"></span></p>
        </div>
        <div class="mb-2">
            <canvas id="freqDomainChartX" width="700" height="400"></canvas>
        </div>
        <div class="d-flex justify-content-center mb-2">
            <button id="prevMinMaxFreqX" class="btn btn-sm btn-primary mx-1">Previous Min/Max</button>
            <button id="nextMinMaxFreqX" class="btn btn-sm btn-primary mx-1">Next Min/Max</button>
        </div>
        <div class="d-flex justify-content-center mb-2">
            <button id="clearFreqDomainX" disabled class="btn btn-sm btn-secondary mx-1">Clear</button>
            <button id="deleteFreqDomainX" disabled class="btn btn-sm btn-danger mx-1">Delete</button>
        </div>
    </div>

    <!-- Frequency Domain Plot for Y -->
    <div class="mb-5">
        <h2 class="text-center">Frequency Domain Plot For Y</h2>
        <div style="border: 1px solid #ddd; padding: 5px; display: flex; flex-direction: column; margin-bottom: 5px;">
            <p style="margin: 0;">Global Max: <span id="globalMaxFreqY"></span></p>
            <p style="margin: 0;">Global Min: <span id="globalMinFreqY"></span></p>
            <p style="margin: 0;">Current Local Max: <span id="currentLocalMaxFreqY"></span></p>
            <p style="margin: 0;">Current Local Min: <span id="currentLocalMinFreqY"></span></p>
        </div>
        <div class="mb-2">
            <canvas id="freqDomainChartY" width="700" height="400"></canvas>
        </div>
        <div class="d-flex justify-content-center mb-2">
            <button id="prevMinMaxFreqY" class="btn btn-sm btn-primary mx-1">Previous Min/Max</button>
            <button id="nextMinMaxFreqY" class="btn btn-sm btn-primary mx-1">Next Min/Max</button>
        </div>
        <div class="d-flex justify-content-center mb-2">
            <button id="clearFreqDomainY" disabled class="btn btn-sm btn-secondary mx-1">Clear</button>
            <button id="deleteFreqDomainY" disabled class="btn btn-sm btn-danger mx-1">Delete</button>
        </div>
    </div>

    <!-- Frequency Domain Plot for Z -->
    <div class="mb-5">
        <h2 class="text-center">Frequency Domain Plot For Z</h2>
        <div style="border: 1px solid #ddd; padding: 5px; display: flex; flex-direction: column; margin-bottom: 5px;">
            <p style="margin: 0;">Global Max: <span id="globalMaxFreqZ"></span></p>
            <p style="margin: 0;">Global Min: <span id="globalMinFreqZ"></span></p>
            <p style="margin: 0;">Current Local Max: <span id="currentLocalMaxFreqZ"></span></p>
            <p style="margin: 0;">Current Local Min: <span id="currentLocalMinFreqZ"></span></p>
        </div>
        <div class="mb-2">
            <canvas id="freqDomainChartZ" width="700" height="400"></canvas>
        </div>
        <div class="d-flex justify-content-center mb-2">
            <button id="prevMinMaxFreqZ" class="btn btn-sm btn-primary mx-1">Previous Min/Max</button>
            <button id="nextMinMaxFreqZ" class="btn btn-sm btn-primary mx-1">Next Min/Max</button>
        </div>
        <div class="d-flex justify-content-center mb-2">
            <button id="clearFreqDomainZ" disabled class="btn btn-sm btn-secondary mx-1">Clear</button>
            <button id="deleteFreqDomainZ" disabled class="btn btn-sm btn-danger mx-1">Delete</button>
        </div>
    </div>

    <div class="d-flex justify-content-center mb-3">
    <button id="goBack" class="btn btn-sm btn-secondary mx-1">Go Back</button>
        <button id="saveCharts" class="btn btn-sm btn-success mx-1">Save Charts</button>
    </div>
</div>

<!-- Custom Tooltip Element -->
<div id="chartjs-tooltip" style="opacity: 50;"></div>

<!-- Including necessary scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@1.2.1/dist/chartjs-plugin-zoom.min.js"></script>
<script src="{{ asset('js/freq-domain.js') }}"></script>

<script>
    // Data initialization
    const data = @json($sensorData);
    const valuesX = data.map(item => item.X);
    const valuesY = data.map(item => item.Y);
    const valuesZ = data.map(item => item.Z);

    // Chart instances
    let freqDomainChartX;
    let freqDomainChartY;
    let freqDomainChartZ;

    function updateCharts() {
        // Destroy existing charts if they exist
        if (freqDomainChartX) {
            freqDomainChartX.destroy();
        }
        if (freqDomainChartY) {
            freqDomainChartY.destroy();
        }
        if (freqDomainChartZ) {
            freqDomainChartZ.destroy();
        }

        // Get canvas contexts
        const freqDomainCtxX = document.getElementById('freqDomainChartX').getContext('2d');
        const freqDomainCtxY = document.getElementById('freqDomainChartY').getContext('2d');
        const freqDomainCtxZ = document.getElementById('freqDomainChartZ').getContext('2d');

        // Create new charts
        freqDomainChartX = createChart(freqDomainCtxX, fft(valuesX), 'Frequency Domain (X)', 'clearFreqDomainX', 'deleteFreqDomainX', 'Freq' , 'X');
        freqDomainChartY = createChart(freqDomainCtxY, fft(valuesY), 'Frequency Domain (Y)', 'clearFreqDomainY', 'deleteFreqDomainY', 'Freq', 'Y');
        freqDomainChartZ = createChart(freqDomainCtxZ, fft(valuesZ), 'Frequency Domain (Z)', 'clearFreqDomainZ', 'deleteFreqDomainZ', 'Freq' , 'Z');
    }

    updateCharts();
</script>
@endsection
