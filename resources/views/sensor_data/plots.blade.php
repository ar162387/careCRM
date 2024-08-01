@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h1>Time and Frequency Domain Plots</h1>
    </div>
    <div class="d-flex mb-3">
        <div style="width: 50%;">
            <h2>Time Domain Plot</h2>
            <div class="border mb-2 p-1">
                <p>Global Max: <span id="globalMaxTime"></span></p>
                <p>Global Min: <span id="globalMinTime"></span></p>
                <p>Current Local Max: <span id="currentLocalMaxTime"></span></p>
                <p>Current Local Min: <span id="currentLocalMinTime"></span></p>
            </div>
            <div class="mb-2">
                <canvas id="timeDomainChart" width="600" height="300"></canvas>
            </div>
            <div class="d-flex justify-content-center">
                <button id="clearTimeDomain" disabled class="btn btn-sm btn-secondary mx-1">Clear</button>
                <button id="deleteTimeDomain" disabled class="btn btn-sm btn-danger mx-1">Delete</button>
            </div>
        </div>
        <div style="width: 50%;">
            <h2>Frequency Domain Plot</h2>
            <div class="border mb-2 p-1">
                <p>Global Max: <span id="globalMaxFreq"></span></p>
                <p>Global Min: <span id="globalMinFreq"></span></p>
                <p>Current Local Max: <span id="currentLocalMaxFreq"></span></p>
                <p>Current Local Min: <span id="currentLocalMinFreq"></span></p>
            </div>
            <div class="mb-2">
                <canvas id="freqDomainChart" width="600" height="300"></canvas>
            </div>
            <div class="d-flex justify-content-center">
                <button id="clearFreqDomain" disabled class="btn btn-sm btn-secondary mx-1">Clear</button>
                <button id="deleteFreqDomain" disabled class="btn btn-sm btn-danger mx-1">Delete</button>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mb-3">
        <button id="prevMinMax" class="btn btn-sm btn-primary mx-1">Previous Min/Max</button>
        <button id="nextMinMax" class="btn btn-sm btn-primary mx-1">Next Min/Max</button>
    </div>
    <div class="d-flex justify-content-center">
        <button id="saveCharts" class="btn btn-sm btn-success mx-1">Save Charts</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@1.2.1/dist/chartjs-plugin-zoom.min.js"></script>
<script>
    const data = @json($sensorData);
    const values = data.map(item => item.{{ $column }});

    let localMinIndex = 0;
    const localWindowSize = 100;
    let currentMultipleValueTime = null;
    let currentMultipleValueFreq = null;
    const originalValues = [...values];

    let timeDomainChart;
    let freqDomainChart;

    function getMin(data) {
        return Math.min(...data);
    }

    function getMax(data) {
        return Math.max(...data);
    }

    function updateInfoBox(chartId, globalMax, globalMin, localMax, localMin) {
        document.getElementById(`globalMax${chartId}`).textContent = `(X: ${globalMax.x}, Y: ${globalMax.y.toFixed(2)})`;
        document.getElementById(`globalMin${chartId}`).textContent = `(X: ${globalMin.x}, Y: ${globalMin.y.toFixed(2)})`;
        document.getElementById(`currentLocalMax${chartId}`).textContent = `(X: ${localMax.x}, Y: ${localMax.y.toFixed(2)})`;
        document.getElementById(`currentLocalMin${chartId}`).textContent = `(X: ${localMin.x}, Y: ${localMin.y.toFixed(2)})`;
    }

    function fft(data) {
        const N = data.length;
        const fft = new Array(N).fill(0).map(() => [0, 0]);
        for (let k = 0; k < N; k++) {
            for (let n = 0; n < N; n++) {
                const realPart = Math.cos((2 * Math.PI * k * n) / N) * data[n];
                const imagPart = -Math.sin((2 * Math.PI * k * n) / N) * data[n];
                fft[k][0] += realPart;
                fft[k][1] += imagPart;
            }
            fft[k] = Math.sqrt(fft[k][0] ** 2 + fft[k][1] ** 2);
        }
        return fft;
    }

    function createChart(ctx, data, label, clearButtonId, deleteButtonId, chartId, isFreqDomain = false) {
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array.from({ length: data.length }, (_, i) => i + 1),
                datasets: [{
                    label: label,
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                }, {
                    label: 'Global Min',
                    data: [{ x: data.indexOf(getMin(data)), y: getMin(data) }],
                    pointBackgroundColor: 'blue',
                    pointBorderColor: 'blue',
                    pointRadius: 8,
                    type: 'scatter'
                }, {
                    label: 'Global Max',
                    data: [{ x: data.indexOf(getMax(data)), y: getMax(data) }],
                    pointBackgroundColor: 'red',
                    pointBorderColor: 'red',
                    pointRadius: 8,
                    type: 'scatter'
                }, {
                    label: 'Local Min',
                    data: [],
                    pointBackgroundColor: 'green',
                    pointBorderColor: 'green',
                    pointRadius: 8,
                    type: 'scatter'
                }, {
                    label: 'Local Max',
                    data: [],
                    pointBackgroundColor: 'orange',
                    pointBorderColor: 'orange',
                    pointRadius: 8,
                    type: 'scatter'
                }]
            },
            options: {
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true
                    },
                    zoom: {
                        pan: {
                            enabled: true,
                            mode: 'x',
                            threshold: 10
                        },
                        zoom: {
                            wheel: {
                                enabled: true,
                            },
                            pinch: {
                                enabled: true
                            },
                            mode: 'x',
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                onClick: function (event, elements) {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const value = data[index];
                        if (clearButtonId === 'clearTimeDomain' && currentMultipleValueTime === null) {
                            currentMultipleValueTime = value;
                            document.getElementById(clearButtonId).disabled = false;
                            document.getElementById(deleteButtonId).disabled = false;
                            markMultiples(this, data, value);
                        } else if (clearButtonId === 'clearFreqDomain' && currentMultipleValueFreq === null) {
                            currentMultipleValueFreq = value;
                            document.getElementById(clearButtonId).disabled = false;
                            document.getElementById(deleteButtonId).disabled = false;
                            markMultiples(this, data, value);
                        }
                    }
                }
            }
        });

        const globalMax = { x: data.indexOf(getMax(data)), y: getMax(data) };
        const globalMin = { x: data.indexOf(getMin(data)), y: getMin(data) };
        const localMinMax = findLocalMinMax(data, localWindowSize);
        const currentLocalMinMax = localMinMax[localMinIndex] || {};
        const localMax = { x: currentLocalMinMax.maxIndex, y: currentLocalMinMax.max };
        const localMin = { x: currentLocalMinMax.minIndex, y: currentLocalMinMax.min };

        updateInfoBox(chartId, globalMax, globalMin, localMax, localMin);

        return chart;
    }

    function updateCharts() {
        if (timeDomainChart) {
            timeDomainChart.destroy();
        }

        if (freqDomainChart) {
            freqDomainChart.destroy();
        }

        const timeDomainCtx = document.getElementById('timeDomainChart').getContext('2d');
        const freqDomainCtx = document.getElementById('freqDomainChart').getContext('2d');

        timeDomainChart = createChart(timeDomainCtx, values, `Time Domain ({{ $column }})`, 'clearTimeDomain', 'deleteTimeDomain', 'Time');
        const fftData = fft(values);
        freqDomainChart = createChart(freqDomainCtx, fftData, `Frequency Domain ({{ $column }})`, 'clearFreqDomain', 'deleteFreqDomain', 'Freq');

        showLocalMinMax(timeDomainChart, values, 'Time');
        showLocalMinMax(freqDomainChart, fftData, 'Freq');
    }

    function findLocalMinMax(data, windowSize) {
        const localMinMax = [];
        for (let i = 0; i < data.length; i += windowSize) {
            const windowData = data.slice(i, i + windowSize);
            const min = Math.min(...windowData);
            const max = Math.max(...windowData);
            localMinMax.push({ min, max, minIndex: i + windowData.indexOf(min), maxIndex: i + windowData.indexOf(max) });
        }
        return localMinMax;
    }

    function showLocalMinMax(chart, data, chartId) {
        const localMinMax = findLocalMinMax(data, localWindowSize);
        const localMin = localMinMax[localMinIndex] ? localMinMax[localMinIndex].min : null;
        const localMax = localMinMax[localMinIndex] ? localMinMax[localMinIndex].max : null;
        const localMinIndexValue = localMinMax[localMinIndex] ? localMinMax[localMinIndex].minIndex : null;
        const localMaxIndexValue = localMinMax[localMinIndex] ? localMinMax[localMinIndex].maxIndex : null;

        const localMinDataset = chart.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = chart.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: localMinIndexValue, y: localMin }];
        }
        if (localMaxDataset) {
            localMaxDataset.data = [{ x: localMaxIndexValue, y: localMax }];
        }

        updateInfoBox(chartId, { x: data.indexOf(getMax(data)), y: getMax(data) }, { x: data.indexOf(getMin(data)), y: getMin(data) }, { x: localMaxIndexValue, y: localMax }, { x: localMinIndexValue, y: localMin });

        chart.update();
    }

    function markMultiples(chart, data, value) {
        const multiplesData = data.map((v, i) => {
            if (v % value === 0) {
                return { x: i, y: v };
            }
            return null;
        }).filter(item => item);

        const multiplesDataset = {
            label: 'Multiples of ' + value,
            data: multiplesData,
            pointBackgroundColor: 'purple',
            pointBorderColor: 'purple',
            pointRadius: 8,
            type: 'scatter'
        };

        chart.data.datasets = chart.data.datasets.filter(dataset => dataset.label.indexOf('Multiples of') === -1);
        chart.data.datasets.push(multiplesDataset);
        chart.update();
    }

    function deleteMultiples(chart, data, value) {
        const threshold = Math.floor(data.length * 0.1);
        const indicesToDelete = [];

        data.forEach((v, i) => {
            if (v % value === 0) {
                for (let j = Math.max(i - threshold, 0); j <= Math.min(i + threshold, data.length - 1); j++) {
                    indicesToDelete.push(j);
                }
            }
        });

        chart.data.datasets.forEach((dataset) => {
            if (dataset.label.includes('Time Domain') || dataset.label.includes('Frequency Domain')) {
                indicesToDelete.forEach((index) => {
                    dataset.data[index] = null;
                });
            }
        });
        chart.update();
    }

    document.getElementById('prevMinMax').addEventListener('click', () => {
        if (localMinIndex > 0) {
            localMinIndex -= 1;
            showLocalMinMax(timeDomainChart, timeDomainChart.data.datasets[0].data, 'Time');
            showLocalMinMax(freqDomainChart, freqDomainChart.data.datasets[0].data, 'Freq');
        }
    });

    document.getElementById('nextMinMax').addEventListener('click', () => {
        const dataLength = timeDomainChart.data.datasets[0].data.length;
        if (localMinIndex < Math.floor(dataLength / localWindowSize) - 1) {
            localMinIndex += 1;
            showLocalMinMax(timeDomainChart, timeDomainChart.data.datasets[0].data, 'Time');
            showLocalMinMax(freqDomainChart, freqDomainChart.data.datasets[0].data, 'Freq');
        }
    });

    document.getElementById('clearTimeDomain').addEventListener('click', () => {
        currentMultipleValueTime = null;
        timeDomainChart.data.datasets = timeDomainChart.data.datasets.filter(dataset => dataset.label.indexOf('Multiples of') === -1);
        timeDomainChart.data.datasets[0].data = [...originalValues];
        timeDomainChart.update();
        document.getElementById('clearTimeDomain').disabled = true;
        document.getElementById('deleteTimeDomain').disabled = true;
    });

    document.getElementById('clearFreqDomain').addEventListener('click', () => {
        currentMultipleValueFreq = null;
        freqDomainChart.data.datasets = freqDomainChart.data.datasets.filter(dataset => dataset.label.indexOf('Multiples of') === -1);
        freqDomainChart.data.datasets[0].data = [...originalValues];
        freqDomainChart.update();
        document.getElementById('clearFreqDomain').disabled = true;
        document.getElementById('deleteFreqDomain').disabled = true;
    });

    document.getElementById('deleteTimeDomain').addEventListener('click', () => {
        if (currentMultipleValueTime !== null) {
            deleteMultiples(timeDomainChart, timeDomainChart.data.datasets[0].data, currentMultipleValueTime);
            currentMultipleValueTime = null;
            document.getElementById('deleteTimeDomain').disabled = true;
        }
    });

    document.getElementById('deleteFreqDomain').addEventListener('click', () => {
        if (currentMultipleValueFreq !== null) {
            deleteMultiples(freqDomainChart, freqDomainChart.data.datasets[0].data, currentMultipleValueFreq);
            currentMultipleValueFreq = null;
            document.getElementById('deleteFreqDomain').disabled = true;
        }
    });

    document.getElementById('saveCharts').addEventListener('click', () => {
        const timeDomainLink = document.createElement('a');
        timeDomainLink.download = 'time_domain_chart.png';
        timeDomainLink.href = timeDomainChart.toBase64Image();
        timeDomainLink.click();

        const freqDomainLink = document.createElement('a');
        freqDomainLink.download = 'freq_domain_chart.png';
        freqDomainLink.href = freqDomainChart.toBase64Image();
        freqDomainLink.click();
    });

    // Initial call to show local min/max
    updateCharts();
</script>
@endsection
