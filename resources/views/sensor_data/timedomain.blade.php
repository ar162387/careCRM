@extends('layouts.care')

@section('content')
<div class="container">
    <div class="text-center mb-3">
        <h1>Time Domain Combined Plots</h1>
    </div>

    <!-- Time Domain Plot for X -->
    <div class="mb-5">
        <h2 class="text-center">Time Domain Plot For X</h2>
        <div style="border: 1px solid #ddd; padding: 5px; display: flex; flex-direction: column; margin-bottom: 5px;">
            <p style="margin: 0;">Global Max: <span id="globalMaxTimeX"></span></p>
            <p style="margin: 0;">Global Min: <span id="globalMinTimeX"></span></p>
            <p style="margin: 0;">Current Local Max: <span id="currentLocalMaxTimeX"></span></p>
            <p style="margin: 0;">Current Local Min: <span id="currentLocalMinTimeX"></span></p>
        </div>
        <div class="mb-2">
            <canvas id="timeDomainChartX" width="700" height="400"></canvas>
        </div>
        <div class="d-flex justify-content-center mb-2">
            <button id="prevMinMaxTimeX" class="btn btn-sm btn-primary mx-1 prevMinMaxTime" data-chart-id="X">Previous Min/Max</button>
            <button id="nextMinMaxTimeX" class="btn btn-sm btn-primary mx-1 nextMinMaxTime" data-chart-id="X">Next Min/Max</button>
        </div>
        <div class="d-flex justify-content-center mb-2">
            <button id="clearTimeDomainX" class="btn btn-sm btn-secondary mx-1 clearTimeDomain" data-chart-id="X">Clear</button>
            <button id="deleteTimeDomainX" class="btn btn-sm btn-danger mx-1 deleteTimeDomain" data-chart-id="X">Delete</button>
        </div>
    </div>

    <!-- Time Domain Plot for Y -->
    <div class="mb-5">
        <h2 class="text-center">Time Domain Plot For Y</h2>
        <div style="border: 1px solid #ddd; padding: 5px; display: flex; flex-direction: column; margin-bottom: 5px;">
            <p style="margin: 0;">Global Max: <span id="globalMaxTimeY"></span></p>
            <p style="margin: 0;">Global Min: <span id="globalMinTimeY"></span></p>
            <p style="margin: 0;">Current Local Max: <span id="currentLocalMaxTimeY"></span></p>
            <p style="margin: 0;">Current Local Min: <span id="currentLocalMinTimeY"></span></p>
        </div>
        <div class="mb-2">
            <canvas id="timeDomainChartY" width="700" height="400"></canvas>
        </div>
        <div class="d-flex justify-content-center mb-2">
            <button id="prevMinMaxTimeY" class="btn btn-sm btn-primary mx-1 prevMinMaxTime" data-chart-id="Y">Previous Min/Max</button>
            <button id="nextMinMaxTimeY" class="btn btn-sm btn-primary mx-1 nextMinMaxTime" data-chart-id="Y">Next Min/Max</button>
        </div>
        <div class="d-flex justify-content-center mb-2">
            <button id="clearTimeDomainY" class="btn btn-sm btn-secondary mx-1 clearTimeDomain" data-chart-id="Y">Clear</button>
            <button id="deleteTimeDomainY" class="btn btn-sm btn-danger mx-1 deleteTimeDomain" data-chart-id="Y">Delete</button>
        </div>
    </div>

    <!-- Time Domain Plot for Z -->
    <div class="mb-5">
        <h2 class="text-center">Time Domain Plot For Z</h2>
        <div style="border: 1px solid #ddd; padding: 5px; display: flex; flex-direction: column; margin-bottom: 5px;">
            <p style="margin: 0;">Global Max: <span id="globalMaxTimeZ"></span></p>
            <p style="margin: 0;">Global Min: <span id="globalMinTimeZ"></span></p>
            <p style="margin: 0;">Current Local Max: <span id="currentLocalMaxTimeZ"></span></p>
            <p style="margin: 0;">Current Local Min: <span id="currentLocalMinTimeZ"></span></p>
        </div>
        <div class="mb-2">
            <canvas id="timeDomainChartZ" width="700" height="400"></canvas>
        </div>
        <div class="d-flex justify-content-center mb-2">
            <button id="prevMinMaxTimeZ" class="btn btn-sm btn-primary mx-1 prevMinMaxTime" data-chart-id="Z">Previous Min/Max</button>
            <button id="nextMinMaxTimeZ" class="btn btn-sm btn-primary mx-1 nextMinMaxTime" data-chart-id="Z">Next Min/Max</button>
        </div>
        <div class="d-flex justify-content-center mb-2">
            <button id="clearTimeDomainZ" class="btn btn-sm btn-secondary mx-1 clearTimeDomain" data-chart-id="Z">Clear</button>
            <button id="deleteTimeDomainZ" class="btn btn-sm btn-danger mx-1 deleteTimeDomain" data-chart-id="Z">Delete</button>
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
<script src="{{ asset('js/time-domain.js') }}"></script>

<script>
 // Data initialization
const data = @json($sensorData);
const valuesX = data.map(item => item.X);
const valuesY = data.map(item => item.Y);
const valuesZ = data.map(item => item.Z);

// Initialize chart variables
let timeDomainChartX;
let timeDomainChartY;
let timeDomainChartZ;

function updateCharts() {
    console.log('Updating charts...');
    
    // Destroy existing charts if they exist
    if (timeDomainChartX) {
        console.log('Destroying existing timeDomainChartX');
        timeDomainChartX.destroy();
    }
    if (timeDomainChartY) {
        console.log('Destroying existing timeDomainChartY');
        timeDomainChartY.destroy();
    }
    if (timeDomainChartZ) {
        console.log('Destroying existing timeDomainChartZ');
        timeDomainChartZ.destroy();
    }

    // Get canvas contexts
    console.log('Retrieving canvas contexts...');
    const timeDomainXCtx = document.getElementById('timeDomainChartX').getContext('2d');
    const timeDomainYCtx = document.getElementById('timeDomainChartY').getContext('2d');
    const timeDomainZCtx = document.getElementById('timeDomainChartZ').getContext('2d');

    // Create new charts
    console.log('Creating timeDomainChartX');
    timeDomainChartX = createChart(timeDomainXCtx, valuesX, 'Time Domain (X)', 'clearTimeDomainX', 'deleteTimeDomainX', 'Time', 'X');
    console.log('Creating timeDomainChartY');
    timeDomainChartY = createChart(timeDomainYCtx, valuesY, 'Time Domain (Y)', 'clearTimeDomainY', 'deleteTimeDomainY', 'Time', 'Y');
    console.log('Creating timeDomainChartZ');
    timeDomainChartZ = createChart(timeDomainZCtx, valuesZ, 'Time Domain (Z)', 'clearTimeDomainZ', 'deleteTimeDomainZ', 'Time', 'Z');
}
    // Initial update of charts
    updateCharts();

    // // Event listeners for navigation buttons
    // document.querySelectorAll('.prevMinMaxTime').forEach(button => {
    //     button.addEventListener('click', () => {
    //         const chartId = button.getAttribute('data-chart-id');
    //         if (currentLocalMinMaxIndexTime > 0) {
    //             currentLocalMinMaxIndexTime -= 1;
    //             showLocalMinMaxTime(charts[chartId], values[chartId]);
    //         }
    //     });
    // });

    // document.querySelectorAll('.nextMinMaxTime').forEach(button => {
    //     button.addEventListener('click', () => {
    //         const chartId = button.getAttribute('data-chart-id');
    //         if (currentLocalMinMaxIndexTime < localMinMaxTime.length - 1) {
    //             currentLocalMinMaxIndexTime += 1;
    //             showLocalMinMaxTime(charts[chartId], values[chartId]);
    //         }
    //     });
    // });

    // // Event listeners for clear buttons
    // document.querySelectorAll('.clearTimeDomain').forEach(button => {
    //     button.addEventListener('click', () => {
    //         const chartId = button.getAttribute('data-chart-id');
    //         currentMultipleValueTime = null;
    //         charts[chartId].data.datasets = charts[chartId].data.datasets.filter(dataset => !dataset.label.includes('Multiples of'));
    //         charts[chartId].data.datasets[0].data = [...originalValuesTime];
    //         charts[chartId].update();
    //         button.disabled = true;
    //         document.getElementById(`deleteTimeDomain${chartId}`).disabled = true;
    //     });
    // });

    // // Event listeners for delete buttons
    // document.querySelectorAll('.deleteTimeDomain').forEach(button => {
    //     button.addEventListener('click', () => {
    //         const chartId = button.getAttribute('data-chart-id');
    //         if (currentMultipleValueTime !== null) {
    //             deleteMultiples(charts[chartId], charts[chartId].data.datasets[0].data, currentMultipleValueTime);
    //             currentMultipleValueTime = null;
    //             button.disabled = true;
    //         }
    //     });
    // });

    // // Event listener for save charts button
    // document.getElementById('saveCharts').addEventListener('click', () => {
    //     ['X', 'Y', 'Z'].forEach(column => {
    //         const link = document.createElement('a');
    //         link.download = `time_domain_chart_${column.toLowerCase()}.png`;
    //         link.href = charts[column].toBase64Image();
    //         link.click();
    //     });
    // });
</script>
@endsection
