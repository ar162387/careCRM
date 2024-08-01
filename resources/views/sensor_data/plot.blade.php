@extends('layouts.care')

@section('content')
<div class="container">
    <div class="text-center mb-3">
        <h1>Time and Frequency Domain Plots</h1>
    </div>
    <div class="d-flex mb-3">
        <div style="width: 50%; position: relative;">
            <h2>Time Domain Plot</h2>
            <div style="border: 1px solid #ddd; padding: 5px; display: flex; flex-direction: column; margin-bottom: 5px; margin-right: 10px;">
                <p style="margin: 0;">Global Max: <span id="globalMaxTime"></span></p>
                <p style="margin: 0;">Global Min: <span id="globalMinTime"></span></p>
                <p style="margin: 0;">Current Local Max: <span id="currentLocalMaxTime"></span></p>
                <p style="margin: 0;">Current Local Min: <span id="currentLocalMinTime"></span></p>
            </div>
            <div class="mb-2">
                <canvas id="timeDomainChart" width="700" height="400"></canvas>
            </div>
            <button id="panToggleTime" style="position: absolute; top: 10px; right: 10px;" class="btn btn-sm btn-secondary">Enable Pan</button>
            <div class="d-flex justify-content-center mb-2">
                <button id="prevMinMaxTime" class="btn btn-sm btn-primary mx-1">Previous Min/Max</button>
                <button id="nextMinMaxTime" class="btn btn-sm btn-primary mx-1">Next Min/Max</button>
            </div>
            <div class="d-flex justify-content-center">
                <button id="clearTimeDomain" disabled class="btn btn-sm btn-secondary mx-1">Clear</button>
                <button id="deleteTimeDomain" disabled class="btn btn-sm btn-danger mx-1">Delete</button>
            </div>
        </div>
        <div style="width: 50%; position: relative;">
            <h2>Frequency Domain Plot</h2>
            <div style="border: 1px solid #ddd; padding: 5px; display: flex; flex-direction: column; margin-bottom: 5px; margin-right: 10px;">
                <p style="margin: 0;">Global Max: <span id="globalMaxFreq"></span></p>
                <p style="margin: 0;">Global Min: <span id="globalMinFreq"></span></p>
                <p style="margin: 0;">Current Local Max: <span id="currentLocalMaxFreq"></span></p>
                <p style="margin: 0;">Current Local Min: <span id="currentLocalMinFreq"></span></p>
            </div>
            <div class="mb-2">
                <canvas id="freqDomainChart" width="700" height="400"></canvas>
            </div>
            <button id="panToggleFreq" style="position: absolute; top: 10px; right: 10px;" class="btn btn-sm btn-secondary">Enable Pan</button>
            <div class="d-flex justify-content-center mb-2">
                <button id="prevMinMaxFreq" class="btn btn-sm btn-primary mx-1">Previous Min/Max</button>
                <button id="nextMinMaxFreq" class="btn btn-sm btn-primary mx-1">Next Min/Max</button>
            </div>
            <div class="d-flex justify-content-center">
                <button id="clearFreqDomain" disabled class="btn btn-sm btn-secondary mx-1">Clear</button>
                <button id="deleteFreqDomain" disabled class="btn btn-sm btn-danger mx-1">Delete</button>
            </div>
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
<script src="{{ asset('js/charts.js') }}"></script>

<script>
    // Data initialization
    const data = @json($sensorData);
    const values = data.map(item => item.{{ $column }});

    // Chart instances
    let timeDomainChart;
    let freqDomainChart;
    let isPanningTime = false;
    let isPanningFreq = false;

    function updateCharts() {
    // Destroy existing charts if they exist
    if (timeDomainChart) {
        timeDomainChart.destroy();
    }
    if (freqDomainChart) {
        freqDomainChart.destroy();
    }

    // Get canvas contexts
    const timeDomainCtx = document.getElementById('timeDomainChart').getContext('2d');
    const freqDomainCtx = document.getElementById('freqDomainChart').getContext('2d');

    // Create new charts
    timeDomainChart = createChart(timeDomainCtx, values, `Time Domain ({{ $column }})`, 'clearTimeDomain', 'deleteTimeDomain', 'Time');
    freqDomainChart = createChart(freqDomainCtx, fft(values), `Frequency Domain ({{ $column }})`, 'clearFreqDomain', 'deleteFreqDomain', 'Freq');
}

    updateCharts();

    
</script>
@endsection
