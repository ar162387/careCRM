<!-- resources/views/fft_graph.blade.php -->

@extends('layouts.fft')

@section('title', 'FFT of Selected Columns')

@section('content')
    <h2>FFT of Selected Columns</h2>

<div class="button-container">
    <button class="button" onclick="saveChart()">Save Chart</button>
    <button class="button" id="toggleExtremaBtn">Next Local Extrema</button>
</div>

<div class="info-container">
    <div id="extremaInfo"></div>
</div>

<canvas id="fftChart" width="800" height="400"></canvas>
@endsection

@section('scripts')
    <script>
        var ctx = document.getElementById('fftChart').getContext('2d');

    // Parse JSON data
    var fftResults = JSON.parse(@json($fftResults));
    var frequencies = JSON.parse(@json($frequencies));
    var columnsToRead = JSON.parse(@json($columnsToRead));
    var header = JSON.parse(@json($header));

    var datasets = [];
    var globalMaxX = -Infinity;
    var globalMinX = Infinity;
    var globalMaxY = -Infinity;
    var globalMinY = Infinity;

    columnsToRead.forEach(function(index, idx) {
        datasets.push({
            label: 'Column ' + header[index],
            data: fftResults[index],
            borderColor: 'rgba(' + (75 + idx * 50) + ', 192, 192, 1)',
            borderWidth: 1,
            fill: false,
            pointRadius: 0,
            borderWidth: 2
        });
    });

    // Function to find global and local maxima and minima
    function findExtrema(data) {
        let maxima = [];
        let minima = [];
        let globalMax = { x: null, y: -Infinity };
        let globalMin = { x: null, y: Infinity };

        for (let i = 1; i < data.length - 1; i++) {
            // Finding global maxima and minima
            if (data[i] > globalMax.y) {
                globalMax = { x: frequencies[i], y: data[i] };
            }
            if (data[i] < globalMin.y) {
                globalMin = { x: frequencies[i], y: data[i] };
            }

            // Finding local maxima and minima
            if (data[i] > data[i - 1] && data[i] > data[i + 1]) {
                maxima.push({ x: frequencies[i], y: data[i] });
            }
            if (data[i] < data[i - 1] && data[i] < data[i + 1]) {
                minima.push({ x: frequencies[i], y: data[i] });
            }
        }

        // Filter out global maxima and minima from local extrema
        maxima = maxima.filter(m => m.x !== globalMax.x || m.y !== globalMax.y);
        minima = minima.filter(m => m.x !== globalMin.x || m.y !== globalMin.y);

        return { globalMax, globalMin, maxima, minima };
    }

    var maxMinAnnotations = [];
    var allLocalExtrema = [];

    datasets.forEach(function(dataset, idx) {
        var extrema = findExtrema(dataset.data);

        // Update global max/min values
        if (extrema.globalMax.x > globalMaxX) globalMaxX = extrema.globalMax.x;
        if (extrema.globalMin.x < globalMinX) globalMinX = extrema.globalMin.x;
        if (extrema.globalMax.y > globalMaxY) globalMaxY = extrema.globalMax.y;
        if (extrema.globalMin.y < globalMinY) globalMinY = extrema.globalMin.y;

        // Add annotations for global maxima and minima
        maxMinAnnotations.push({
            type: 'point',
            backgroundColor: 'green',
            borderColor: 'green',
            radius: 5,
            xValue: extrema.globalMax.x,
            yValue: extrema.globalMax.y,
            label: {
                enabled: true,
                content: 'Global Max',
                position: 'top'
            }
        });
        maxMinAnnotations.push({
            type: 'point',
            backgroundColor: 'orange',
            borderColor: 'orange',
            radius: 5,
            xValue: extrema.globalMin.x,
            yValue: extrema.globalMin.y,
            label: {
                enabled: true,
                content: 'Global Min',
                position: 'bottom'
            }
        });

        // Collect all local extrema
        allLocalExtrema.push({
            maxima: extrema.maxima,
            minima: extrema.minima
        });
    });

    var currentLocalExtremaIndex = 0;

    // Function to update the chart with current local extrema
    function updateLocalExtrema() {
        // Clear previous local extrema annotations
        maxMinAnnotations = maxMinAnnotations.filter(function(annotation) {
            return annotation.label.content.indexOf('Local') === -1;
        });

        // Add the current local extrema annotations
        datasets.forEach(function(dataset, idx) {
            var localExtrema = allLocalExtrema[idx];
            if (localExtrema.maxima.length > currentLocalExtremaIndex) {
                var max = localExtrema.maxima[currentLocalExtremaIndex];
                maxMinAnnotations.push({
                    type: 'point',
                    backgroundColor: 'red',
                    borderColor: 'red',
                    radius: 5,
                    xValue: max.x,
                    yValue: max.y,
                    label: {
                        enabled: true,
                        content: 'Local Max',
                        position: 'top'
                    }
                });
            }
            if (localExtrema.minima.length > currentLocalExtremaIndex) {
                var min = localExtrema.minima[currentLocalExtremaIndex];
                maxMinAnnotations.push({
                    type: 'point',
                    backgroundColor: 'blue',
                    borderColor: 'blue',
                    radius: 5,
                    xValue: min.x,
                    yValue: min.y,
                    label: {
                        enabled: true,
                        content: 'Local Min',
                        position: 'bottom'
                    }
                });
            }
        });

        // Update chart annotations
        fftChart.options.plugins.annotation.annotations = maxMinAnnotations;
        fftChart.update();

        // Update the extrema info box
        updateExtremaInfoBox();
    }

    // Function to update the extrema info box
    function updateExtremaInfoBox() {
        var extremaInfo = document.getElementById('extremaInfo');
        var infoText = 'Global Max: (X: ' + globalMaxX + ', Y: ' + globalMaxY + ')<br>' +
                       'Global Min: (X: ' + globalMinX + ', Y: ' + globalMinY + ')<br>';

        datasets.forEach(function(dataset, idx) {
            var localExtrema = allLocalExtrema[idx];
            if (localExtrema.maxima.length > currentLocalExtremaIndex) {
                var max = localExtrema.maxima[currentLocalExtremaIndex];
                infoText += 'Current Local Max: (X: ' + max.x + ', Y: ' + max.y + ')<br>';
            }
            if (localExtrema.minima.length > currentLocalExtremaIndex) {
                var min = localExtrema.minima[currentLocalExtremaIndex];
                infoText += 'Current Local Min: (X: ' + min.x + ', Y: ' + min.y + ')<br>';
            }
        });

        extremaInfo.innerHTML = infoText;
    }

    // Create the chart
    var fftChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: frequencies,
            datasets: datasets
        },
        options: {
            scales: {
                 x: {
                    type: 'linear',
                    position: 'bottom',
                    title: {
                        display: true,
                        text: 'Frequency',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        beginAtZero: true,
                        color: '#333'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    min: globalMinX - 10,  // Adding some padding
                    max: globalMaxX + 10   // Adding some padding
                },
                y: {
                    type: 'linear',
                    title: {
                        display: true,
                        text: 'Amplitude',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        beginAtZero: false,
                        color: '#333'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    min: globalMinY - 10,  // Adding some padding
                    max: globalMaxY + 10   // Adding some padding
                }
            },
            plugins: {
                zoom: {
                    pan: {
                        enabled: true,
                        mode: 'xy'
                    },
                    zoom: {
                        wheel: {
                            enabled: true,
                        },
                        pinch: {
                            enabled: true
                        }
                    }
                },
                annotation: {
                    annotations: maxMinAnnotations
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'FFT of Selected Columns',
                    font: {
                        size: 16,
                        weight: 'bold'
                    }
                },
                tooltip: {
                    enabled: true,
                    mode: 'nearest',
                    intersect: false,
                    callbacks: {
                        label: function(tooltipItem) {
                            return 'Amplitude: ' + tooltipItem.raw.toFixed(2);
                        }
                    }
                },
                zoom: {
                    zoom: {
                        wheel: {
                            enabled: true,
                        },
                        pinch: {
                            enabled: true
                        },
                        mode: 'xy',
                    },
                    pan: {
                        enabled: true,
                        mode: 'xy',
                    }
                },
                annotation: {
                    annotations: maxMinAnnotations
                },
                legend: {
                    display: true,
                    labels: {
                        generateLabels: function(chart) {
                            const data = chart.data.datasets[0].data;
                            return [
                                {
                                    text: 'Global Max',
                                    fillStyle: 'green'
                                },
                                {
                                    text: 'Global Min',
                                    fillStyle: 'orange'
                                },
                                {
                                    text: 'Local Max',
                                    fillStyle: 'red'
                                },
                                {
                                    text: 'Local Min',
                                    fillStyle: 'blue'
                                }
                            ];
                        }
                    }
                }
            }
        }
    });

    // Function to toggle additional local extrema
    document.getElementById('toggleExtremaBtn').addEventListener('click', function() {
        currentLocalExtremaIndex++;
        if (currentLocalExtremaIndex >= allLocalExtrema[0].maxima.length || 
            currentLocalExtremaIndex >= allLocalExtrema[0].minima.length) {
            currentLocalExtremaIndex = 0;
        }
        updateLocalExtrema();
    });

    function saveChart() {
        var tmpCanvas = document.createElement('canvas');
        tmpCanvas.width = fftChart.width;
        tmpCanvas.height = fftChart.height;
        var tmpCtx = tmpCanvas.getContext('2d');

        tmpCtx.fillStyle = 'white';
        tmpCtx.fillRect(0, 0, tmpCanvas.width, tmpCanvas.height);

        tmpCtx.drawImage(fftChart.canvas, 0, 0, fftChart.width, fftChart.height);

        var link = document.createElement('a');
        link.href = tmpCanvas.toDataURL('image/png');
        link.download = 'fft_chart.png';
        link.click();
    }

    // Initial update of local extrema
    updateLocalExtrema();

    // Function to update the chart with current local extrema
    function updateLocalExtrema() {
        // Clear previous local extrema annotations
        maxMinAnnotations = maxMinAnnotations.filter(function(annotation) {
            return annotation.label.content.indexOf('Local') === -1;
        });

        // Add the current local extrema annotations
        datasets.forEach(function(dataset, idx) {
            var localExtrema = allLocalExtrema[idx];
            if (localExtrema.maxima.length > currentLocalExtremaIndex) {
                var max = localExtrema.maxima[currentLocalExtremaIndex];
                maxMinAnnotations.push({
                    type: 'point',
                    backgroundColor: 'red',
                    borderColor: 'red',
                    radius: 5,
                    xValue: max.x,
                    yValue: max.y,
                    label: {
                        enabled: true,
                        content: 'Local Max',
                        position: 'top'
                    }
                });
            }
            if (localExtrema.minima.length > currentLocalExtremaIndex) {
                var min = localExtrema.minima[currentLocalExtremaIndex];
                maxMinAnnotations.push({
                    type: 'point',
                    backgroundColor: 'blue',
                    borderColor: 'blue',
                    radius: 5,
                    xValue: min.x,
                    yValue: min.y,
                    label: {
                        enabled: true,
                        content: 'Local Min',
                        position: 'bottom'
                    }
                });
            }
        });

        // Update chart annotations
        fftChart.options.plugins.annotation.annotations = maxMinAnnotations;
        fftChart.update();

        // Update the extrema info box
        updateExtremaInfoBox();
    }

    // Function to update the extrema info box
    function updateExtremaInfoBox() {
        var extremaInfo = document.getElementById('extremaInfo');
        var infoText = '<b>Global Max:</b> (X: ' + globalMaxX + ', Y: ' + globalMaxY + ')<br>' +
                       '<b>Global Min:</b> (X: ' + globalMinX + ', Y: ' + globalMinY + ')<br>';

        datasets.forEach(function(dataset, idx) {
            var localExtrema = allLocalExtrema[idx];
            if (localExtrema.maxima.length > currentLocalExtremaIndex) {
                var max = localExtrema.maxima[currentLocalExtremaIndex];
                infoText += '<b>Current Local Max:</b> (X: ' + max.x + ', Y: ' + max.y + ')<br>';
            }
            if (localExtrema.minima.length > currentLocalExtremaIndex) {
                var min = localExtrema.minima[currentLocalExtremaIndex];
                infoText += '<b>Current Local Min:</b> (X: ' + min.x + ', Y: ' + min.y + ')<br>';
            }
        });

        extremaInfo.innerHTML = infoText;
    }
</script>
@endsection