<!-- resources/views/time_graph.blade.php -->

@extends('layouts.time')

@section('title', 'Time Domain Plot')

@section('content')
    <h2>Time Domain Plot</h2>
    <canvas id="timeDomainChart" width="800" height="400"></canvas>
    
    <div class="button-container">
        <button class="button" onclick="saveChart()">Save Chart</button>
        <button class="button" onclick="toggleLocalExtrema()">Next Local Extrema</button>
    </div>

    <div class="value-display" id="valueDisplay">
        <div id="globalExtrema"></div>
        <div id="initialLocalExtrema"></div>
        <div id="currentExtrema"></div>
    </div>

@endsection

@section('scripts')
    <script>
        // Get the CSV data passed from the controller
        var columnValues = @json($columnValues);

        // Function to find global and local maxima and minima
        function findExtrema(data) {
            let maxima = [];
            let minima = [];
            let globalMax = { index: 0, value: data[0] };
            let globalMin = { index: 0, value: data[0] };

            // Finding global maxima and minima
            for (let i = 1; i < data.length; i++) {
                if (data[i] > globalMax.value) {
                    globalMax = { index: i, value: data[i] };
                }
                if (data[i] < globalMin.value) {
                    globalMin = { index: i, value: data[i] };
                }
            }

            // Finding local maxima and minima
            for (let i = 1; i < data.length - 1; i++) {
                if (data[i] > data[i - 1] && data[i] > data[i + 1]) {
                    maxima.push({ index: i, value: data[i] });
                }
                if (data[i] < data[i - 1] && data[i] < data[i + 1]) {
                    minima.push({ index: i, value: data[i] });
                }
            }

            // Filter out global maxima and minima from local extrema
            maxima = maxima.filter(m => m.index !== globalMax.index);
            minima = minima.filter(m => m.index !== globalMin.index);

            return { globalMax, globalMin, maxima, minima };
        }

        // Generate labels for the x-axis
        var labels = Array.from(Array(columnValues[19].length).keys());

        // Prepare datasets for Chart.js
        var datasets = [];
        var extremaAnnotations = [];
        for (var index in columnValues) {
            var data = columnValues[index];
            var extrema = findExtrema(data);

            // Add global maxima and minima annotations
            extremaAnnotations.push({
                type: 'point',
                backgroundColor: 'green',
                borderColor: 'green',
                radius: 7,
                xValue: extrema.globalMax.index,
                yValue: extrema.globalMax.value,
                label: {
                    enabled: true,
                    content: 'Global Max: ' + extrema.globalMax.value.toFixed(2),
                    position: 'top'
                }
            });
            extremaAnnotations.push({
                type: 'point',
                backgroundColor: 'orange',
                borderColor: 'orange',
                radius: 7,
                xValue: extrema.globalMin.index,
                yValue: extrema.globalMin.value,
                label: {
                    enabled: true,
                    content: 'Global Min: ' + extrema.globalMin.value.toFixed(2),
                    position: 'bottom'
                }
            });

            // Add initial local extrema annotations (first cycle)
            extrema.maxima.slice(0, 1).forEach(function(max) {
                extremaAnnotations.push({
                    type: 'point',
                    backgroundColor: 'red',
                    borderColor: 'red',
                    radius: 5,
                    xValue: max.index,
                    yValue: max.value,
                    label: {
                        enabled: true,
                        content: 'Initial Local Max: ' + max.value.toFixed(2),
                        position: 'top'
                    }
                });
            });

            extrema.minima.slice(0, 1).forEach(function(min) {
                extremaAnnotations.push({
                    type: 'point',
                    backgroundColor: 'blue',
                    borderColor: 'blue',
                    radius: 5,
                    xValue: min.index,
                    yValue: min.value,
                    label: {
                        enabled: true,
                        content: 'Initial Local Min: ' + min.value.toFixed(2),
                        position: 'bottom'
                    }
                });
            });

            datasets.push({
                label: 'Column 19 (cd420)',
                data: data,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                fill: false,
                pointRadius: 0,
                borderWidth: 2
            });
        }

        // Create the time domain plot
        var ctx = document.getElementById('timeDomainChart').getContext('2d');
        var timeDomainChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                scales: {
                    x: {
                        type: 'linear',
                        position: 'bottom',
                        title: {
                            display: true,
                            text: 'Time',
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
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Amplitude',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        ticks:{
    color: '#333'
},
grid: {
    color: 'rgba(0, 0, 0, 0.1)'
}
},
},
plugins: {
    tooltip: {
        enabled: true,
        mode: 'nearest',
        intersect: false,
        callbacks: {
            label: function(tooltipItem) {
                return 'Value: ' + tooltipItem.raw.toFixed(2);
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
        annotations: extremaAnnotations
    },
    legend: {
        display: true,
        labels: {
            generateLabels: function(chart) {
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

// Function to toggle local extrema
var currentLocalExtremaIndex = 0; // Start with initial local extrema

function toggleLocalExtrema() {
    // Remove previous local extrema annotations
    extremaAnnotations = extremaAnnotations.filter(function(annotation) {
        return annotation.label.content.indexOf('Local') === -1;
    });

    // Increment current local extrema index
    currentLocalExtremaIndex++;

    // Add the current local extrema annotations
    datasets.forEach(function(dataset, idx) {
        var data = dataset.data;
        var extrema = findExtrema(data);

        var localMax = extrema.maxima.slice(currentLocalExtremaIndex, currentLocalExtremaIndex + 1);
        var localMin = extrema.minima.slice(currentLocalExtremaIndex, currentLocalExtremaIndex + 1);

        localMax.forEach(function(max) {
            extremaAnnotations.push({
                type: 'point',
                backgroundColor: 'red',
                borderColor: 'red',
                radius: 5,
                xValue: max.index,
                yValue: max.value,
                label: {
                    enabled: true,
                    content: 'Local Max: ' + max.value,
                    position: 'top'
                }
            });
        });

        localMin.forEach(function(min) {
            extremaAnnotations.push({
                type: 'point',
                backgroundColor: 'blue',
                borderColor: 'blue',
                radius: 5,
                xValue: min.index,
                yValue: min.value,
                label: {
                    enabled: true,
                    content: 'Local Min: ' + min.value.toFixed(2),
                    position: 'bottom'
                }
            });
        });
    });

    // Update chart annotations
    timeDomainChart.options.plugins.annotation.annotations = extremaAnnotations;
    timeDomainChart.update();

    // Update value display box
    updateValueDisplay(extrema);
}

// Function to update value display box
function updateValueDisplay(extrema) {
    var globalExtremaDiv = document.getElementById('globalExtrema');
    globalExtremaDiv.innerHTML = `
        <strong>Global Max:</strong> (X: ${extrema.globalMax.index}, Y: ${extrema.globalMax.value.toFixed(2)})<br>
        <strong>Global Min:</strong> (X: ${extrema.globalMin.index}, Y: ${extrema.globalMin.value.toFixed(2)})
    `;

    var currentExtremaDiv = document.getElementById('currentExtrema');
    currentExtremaDiv.innerHTML = `
        <strong>Current Local Max:</strong> (X: ${extrema.maxima[currentLocalExtremaIndex].index}, Y: ${extrema.maxima[currentLocalExtremaIndex].value.toFixed(2)})<br>
        <strong>Current Local Min:</strong> (X: ${extrema.minima[currentLocalExtremaIndex].index}, Y: ${extrema.minima[currentLocalExtremaIndex].value.toFixed(2)})
    `;
}

// Initial update of value display box with initial local and global extrema
var initialData = columnValues[19]; // Assuming data for the initial column
var initialExtrema = findExtrema(initialData);
updateValueDisplay(initialExtrema);


// Function to save the chart as an image with a white background
function saveChart() {
var originalCanvas = document.getElementById('timeDomainChart');
var canvas = document.createElement('canvas');
var context = canvas.getContext('2d');
canvas.width = originalCanvas.width;
canvas.height = originalCanvas.height;

// Fill the canvas with white background
context.fillStyle = 'white';
context.fillRect(0, 0, canvas.width, canvas.height);

// Draw the chart on top
context.drawImage(originalCanvas, 0, 0);

// Save the canvas as an image
var link = document.createElement('a');
link.href = canvas.toDataURL('image/png');
link.download = 'time_domain_chart.png';
link.click();
}

</script>
@endsection