<!-- resources/views/combined.blade.php -->

@extends('layouts.comb')

@section('title', 'Time and Frequency Domain Plots')

@section('content')
    <h2>Time and Frequency Domain Plots</h2>

    <div class="chart-container">

        <!-- Time Domain Chart -->
        <div>
            <h3><br><br>Time Domain Plot</h3>
            <canvas id="timeDomainChart" width="600" height="330"></canvas>
            <div class="button-container">
                <button class="button" onclick="toggleLocalExtremaTimeDomain()">Next Local Extrema</button>
            </div>
            <div class="value-display-right" id="valueDisplayTimeDomain">
                <div id="globalExtremaTimeDomain"></div>
                <div id="currentExtremaTimeDomain"></div>
            </div>
        </div>

         <!-- Frequency Domain Chart -->
        <div>
            <h3><br><br>Frequency Domain Plot</h3>
            <canvas id="frequencyDomainChart" width="600" height="330"></canvas>
            <div class="button-container">
                <button class="button" onclick="toggleLocalExtremaFrequencyDomain()">Next Local Extrema</button>
            </div>
            <div class="value-display" id="valueDisplayFrequencyDomain">
                <div id="globalExtremaFrequencyDomain"></div>
                <div id="currentExtremaFrequencyDomain"></div>
            </div>
        </div>
    </div>

   <div class="button-container">
        <button class="button" onclick="saveCharts()">Save Charts</button>
    </div>
@endsection

@section('scripts')
    <script>

// Parse JSON data for Time Domain
var columnValues = {!! json_encode($columnValues) !!};

// Function to find global and local maxima and minima for Time Domain
function findTimeDomainExtrema(data) {
    let maxima = [];
    let minima = [];
    let globalMax = { index: 0, value: data[0] };
    let globalMin = { index: 0, value: data[0] };

    // Finding global maxima and minima for Time Domain
    for (let i = 1; i < data.length; i++) {
        if (data[i] > globalMax.value) {
            globalMax = { index: i, value: data[i] };
        }
        if (data[i] < globalMin.value) {
            globalMin = { index: i, value: data[i] };
        }
    }

    // Finding local maxima and minima for Time Domain
    for (let i = 1; i < data.length - 1; i++) {
        if (data[i] > data[i - 1] && data[i] > data[i + 1]) {
            maxima.push({ index: i, value: data[i] });
        }
        if (data[i] < data[i - 1] && data[i] < data[i + 1]) {
            minima.push({ index: i, value: data[i] });
        }
    }

    // Filter out global maxima and minima from local extrema for Time Domain
    maxima = maxima.filter(m => m.index !== globalMax.index);
    minima = minima.filter(m => m.index !== globalMin.index);

    return { globalMax, globalMin, maxima, minima };
}

// Generate labels for the x-axis for Time Domain
var timeDomainLabels = Array.from(Array(columnValues[19].length).keys());

// Prepare datasets for Chart.js for Time Domain
var timeDomainDatasets = [];
var timeDomainExtremaAnnotations = [];
for (var index in columnValues) {
    var data = columnValues[index];
    var extrema = findTimeDomainExtrema(data);

    // Add global maxima and minima annotations for Time Domain
    timeDomainExtremaAnnotations.push({
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
    timeDomainExtremaAnnotations.push({
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

    // Add initial local extrema annotations (first cycle) for Time Domain
    extrema.maxima.slice(0, 1).forEach(function(max) {
        timeDomainExtremaAnnotations.push({
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
        timeDomainExtremaAnnotations.push({
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

    timeDomainDatasets.push({
        label: 'Column ' + index,
        data: data,
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1,
        fill: false,
        pointRadius: 0,
        borderWidth: 2
    });
}

// Create the time domain plot using Chart.js
var ctxTimeDomain = document.getElementById('timeDomainChart').getContext('2d');
var timeDomainChart = new Chart(ctxTimeDomain, {
    type: 'line',
    data: {
        labels: timeDomainLabels,
        datasets: timeDomainDatasets
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
                ticks: {
                    color: '#333'
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            }
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
                annotations: timeDomainExtremaAnnotations
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

// Function to toggle local extrema for Time Domain
var currentTimeDomainLocalExtremaIndex = 0; // Start with initial local extrema

function toggleLocalExtremaTimeDomain() {
    // Remove previous local extrema annotations for Time Domain
    timeDomainExtremaAnnotations = timeDomainExtremaAnnotations.filter(function(annotation) {
        return annotation.label.content.indexOf('Local') === -1;
    });

    // Increment current local extrema index for Time Domain
    currentTimeDomainLocalExtremaIndex++;

    // Add the current local extrema annotations for Time Domain
    timeDomainDatasets.forEach(function(dataset, idx) {
        var data = dataset.data;
        var extrema = findTimeDomainExtrema(data);

        var localMax = extrema.maxima.slice(currentTimeDomainLocalExtremaIndex, currentTimeDomainLocalExtremaIndex + 1);
        var localMin = extrema.minima.slice(currentTimeDomainLocalExtremaIndex, currentTimeDomainLocalExtremaIndex + 1);

        localMax.forEach(function(max) {
            timeDomainExtremaAnnotations.push({
                type: 'point',
                backgroundColor: 'red',
                borderColor: 'red',
                radius: 5,
                xValue: max.index,
                yValue: max.value,
                label: {
                    enabled: true,
                    content: 'Local Max: ' + max.value.toFixed(2),
                    position: 'top'
                }
            });
        });

        localMin.forEach(function(min) {
            timeDomainExtremaAnnotations.push({
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

    // Update chart annotations for Time Domain
    timeDomainChart.options.plugins.annotation.annotations = timeDomainExtremaAnnotations;
    timeDomainChart.update();

    // Update value display box for Time Domain
    updateValueDisplayTimeDomain(extrema);
}

// Function to update value display box for Time Domain
function updateValueDisplayTimeDomain(extrema) {
    var globalExtremaDiv = document.getElementById('globalExtremaTimeDomain');
    globalExtremaDiv.innerHTML = `
        <strong>Global Max:</strong> (X: ${extrema.globalMax.index}, Y: ${extrema.globalMax.value.toFixed(2)})<br>
        <strong>Global Min:</strong> (X: ${extrema.globalMin.index}, Y: ${extrema.globalMin.value.toFixed(2)})
    `;

    var currentExtremaDiv = document.getElementById('currentExtremaTimeDomain');
    currentExtremaDiv.innerHTML = `
        <strong>Current Local Max:</strong> (X: ${extrema.maxima[currentTimeDomainLocalExtremaIndex].index}, Y: ${extrema.maxima[currentTimeDomainLocalExtremaIndex].value.toFixed(2)})<br>
        <strong>Current Local Min:</strong> (X: ${extrema.minima[currentTimeDomainLocalExtremaIndex].index}, Y: ${extrema.minima[currentTimeDomainLocalExtremaIndex].value.toFixed(2)})
    `;
}

// Initial update of value display box with initial local and global extrema
var initialData = columnValues[19]; // Assuming data for the initial column
var initialExtrema = findTimeDomainExtrema(initialData);
updateValueDisplayTimeDomain(initialExtrema);


// Add click event listener to the chart canvas
ctxTimeDomain.canvas.addEventListener('click', function(evt) {
    var activePoints = timeDomainChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);

    if (activePoints.length > 0) {
        var clickedIndex = activePoints[0].index;
        currentTimeDomainLocalExtremaIndex = clickedIndex;

        // Update annotations and display box to move both maxima and minima
        moveAnnotationsToIndex(clickedIndex);
    }
});

// Function to move both maxima and minima annotations to the clicked index
function moveAnnotationsToIndex(clickedIndex) {
    // Remove previous local extrema annotations for Time Domain
    timeDomainExtremaAnnotations = timeDomainExtremaAnnotations.filter(function(annotation) {
        return annotation.label.content.indexOf('Local') === -1;
    });

    // Initialize variables to store nearest maxima and minima values
    var nearestMaxValue = null;
    var nearestMinValue = null;

    // Add the nearest local maxima and minima annotations for Time Domain at the clicked index
    timeDomainDatasets.forEach(function(dataset, idx) {
        var data = dataset.data;
        var extrema = findTimeDomainExtrema(data);

        // Find the nearest local maxima to the clicked index
        var nearestMax = extrema.maxima.reduce(function(prev, curr) {
            return Math.abs(curr.index - clickedIndex) < Math.abs(prev.index - clickedIndex) ? curr : prev;
        });

        // Find the nearest local minima to the clicked index
        var nearestMin = extrema.minima.reduce(function(prev, curr) {
            return Math.abs(curr.index - clickedIndex) < Math.abs(prev.index - clickedIndex) ? curr : prev;
        });

        // Update nearest maxima and minima values
        if (nearestMax) {
            nearestMaxValue = nearestMax.value;
        }

        if (nearestMin) {
            nearestMinValue = nearestMin.value;
        }

        // Add annotation for the nearest local maxima
        if (nearestMax) {
            timeDomainExtremaAnnotations.push({
                type: 'point',
                backgroundColor: 'red',
                borderColor: 'red',
                radius: 5,
                xValue: nearestMax.index,
                yValue: nearestMax.value,
                label: {
                    enabled: true,
                    content: 'Local Max: ' + nearestMax.value.toFixed(2),
                    position: 'top'
                }
            });
        }

        // Add annotation for the nearest local minima
        if (nearestMin) {
            timeDomainExtremaAnnotations.push({
                type: 'point',
                backgroundColor: 'blue',
                borderColor: 'blue',
                radius: 5,
                xValue: nearestMin.index,
                yValue: nearestMin.value,
                label: {
                    enabled: true,
                    content: 'Local Min: ' + nearestMin.value.toFixed(2),
                    position: 'bottom'
                }
            });
        }

        // Update value display box for Time Domain within the same scope
        var globalExtremaDiv = document.getElementById('globalExtremaTimeDomain');
        globalExtremaDiv.innerHTML = `
            <strong>Global Max:</strong> (X: ${extrema.globalMax.index}, Y: ${extrema.globalMax.value.toFixed(2)})<br>
            <strong>Global Min:</strong> (X: ${extrema.globalMin.index}, Y: ${extrema.globalMin.value.toFixed(2)})
        `;

        var currentExtremaDiv = document.getElementById('currentExtremaTimeDomain');
        currentExtremaDiv.innerHTML = `
            <strong>Current Local Max:</strong> (X: ${nearestMax.index}, Y: ${nearestMaxValue.toFixed(2)})<br>
            <strong>Current Local Min:</strong> (X: ${nearestMin.index}, Y: ${nearestMinValue.toFixed(2)})
        `;
    });

    // Update chart annotations for Time Domain
    timeDomainChart.options.plugins.annotation.annotations = timeDomainExtremaAnnotations;
    timeDomainChart.update();
}



// Parse JSON data for Frequency Domain
var fftResults = {!! json_encode($fftResults) !!};
var frequencies = {!! json_encode($frequencies) !!};
var columnsToRead = {!! json_encode($columnsToRead) !!};
var header = {!! json_encode($header) !!};

// Function to find global and local maxima and minima for Frequency Domain
function findFrequencyDomainExtrema(data) {
    let maxima = [];
    let minima = [];
    let globalMax = { index: 0, value: data[0] };
    let globalMin = { index: 0, value: data[0] };

    // Finding global maxima and minima for Frequency Domain
    for (let i = 1; i < data.length; i++) {
        if (data[i] > globalMax.value) {
            globalMax = { index: i, value: data[i] };
        }
        if (data[i] < globalMin.value) {
            globalMin = { index: i, value: data[i] };
        }
    }

    // Finding local maxima and minima for Frequency Domain
    for (let i = 1; i < data.length - 1; i++) {
        if (data[i] > data[i - 1] && data[i] > data[i + 1]) {
            maxima.push({ index: i, value: data[i] });
        }
        if (data[i] < data[i - 1] && data[i] < data[i + 1]) {
            minima.push({ index: i, value: data[i] });
        }
    }

    // Filter out global maxima and minima from local extrema for Frequency Domain
    maxima = maxima.filter(m => m.index !== globalMax.index);
    minima = minima.filter(m => m.index !== globalMin.index);

    return { globalMax, globalMin, maxima, minima };
}

// Generate labels for the x-axis for Frequency Domain
var frequencyDomainLabels = frequencies;

// Prepare datasets for Chart.js for Frequency Domain
var frequencyDomainDatasets = [];
var frequencyDomainExtremaAnnotations = [];
for (var index in fftResults) {
    var data = fftResults[index];
    var extrema = findFrequencyDomainExtrema(data);

    // Add global maxima and minima annotations for Frequency Domain
    frequencyDomainExtremaAnnotations.push({
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
    frequencyDomainExtremaAnnotations.push({
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

    // Add initial local extrema annotations (first cycle) for Frequency Domain
    extrema.maxima.slice(0, 1).forEach(function(max) {
        frequencyDomainExtremaAnnotations.push({
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
        frequencyDomainExtremaAnnotations.push({
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

    frequencyDomainDatasets.push({
        label: 'Column ' + columnsToRead[index],
        data: data,
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1,
        fill: false,
        pointRadius: 0,
        borderWidth: 2
    });
}

// Create the frequency domain plot using Chart.js
var ctxFrequencyDomain = document.getElementById('frequencyDomainChart').getContext('2d');
var frequencyDomainChart = new Chart(ctxFrequencyDomain, {
    type: 'line',
    data: {
        labels: frequencyDomainLabels,
        datasets: frequencyDomainDatasets
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
            ticks: {
                color: '#333'
            },
            grid: {
                color: 'rgba(0, 0, 0, 0.1)'
            }
        }
    },
    plugins: {
        zoom: {
            pan: {
                enabled: true,
                mode: 'xy', // Enable pan in both x and y directions
                rangeMin: {
                    x: null,
                    y: null
                },
                rangeMax: {
                    x: null,
                    y: null
                },
                speed: 10,
                threshold: 10
            },
            zoom: {
                wheel: {
                    enabled: true
                },
                pinch: {
                    enabled: true
                },
                mode: 'xy', // Enable zoom in both x and y directions
                rangeMin: {
                    x: null,
                    y: null
                },
                rangeMax: {
                    x: null,
                    y: null
                }
            }
        },
        annotation: {
            annotations: frequencyDomainExtremaAnnotations
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

// Function to toggle local extrema for Frequency Domain
var currentFrequencyDomainLocalExtremaIndex = 0; // Start with initial local extrema

function toggleLocalExtremaFrequencyDomain() {
    // Remove previous local extrema annotations for Frequency Domain
    frequencyDomainExtremaAnnotations = frequencyDomainExtremaAnnotations.filter(function(annotation) {
        return annotation.label.content.indexOf('Local') === -1;
    });

    // Increment current local extrema index for Frequency Domain
    currentFrequencyDomainLocalExtremaIndex++;

    // Add the current local extrema annotations for Frequency Domain
    frequencyDomainDatasets.forEach(function(dataset, idx) {
        var data = dataset.data;
        var extrema = findFrequencyDomainExtrema(data);

        var localMax = extrema.maxima.slice(currentFrequencyDomainLocalExtremaIndex, currentFrequencyDomainLocalExtremaIndex + 1);
        var localMin = extrema.minima.slice(currentFrequencyDomainLocalExtremaIndex, currentFrequencyDomainLocalExtremaIndex + 1);

        localMax.forEach(function(max) {
            frequencyDomainExtremaAnnotations.push({
                type: 'point',
                backgroundColor: 'red',
                borderColor: 'red',
                radius: 5,
                xValue: max.index,
                yValue: max.value,
                label: {
                    enabled: true,
                    content: 'Local Max: ' + max.value.toFixed(2),
                    position: 'top'
                }
            });
        });

        localMin.forEach(function(min) {
            frequencyDomainExtremaAnnotations.push({
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

    // Update chart annotations for Frequency Domain
    frequencyDomainChart.options.plugins.annotation.annotations = frequencyDomainExtremaAnnotations;
    frequencyDomainChart.update();

    // Update value display box for Frequency Domain
    updateValueDisplayFrequencyDomain(extrema);
}

// Function to update value display box for Frequency Domain
function updateValueDisplayFrequencyDomain(extrema) {
    var globalExtremaDiv = document.getElementById('globalExtremaFrequencyDomain');
    globalExtremaDiv.innerHTML = `
        <strong>Global Max:</strong> (X: ${extrema.globalMax.index}, Y: ${extrema.globalMax.value.toFixed(2)})<br>
        <strong>Global Min:</strong> (X: ${extrema.globalMin.index}, Y: ${extrema.globalMin.value.toFixed(2)})
    `;

    var currentExtremaDiv = document.getElementById('currentExtremaFrequencyDomain');
currentExtremaDiv.innerHTML = `
    <strong>Current Local Max:</strong> (X: ${extrema.maxima[currentFrequencyDomainLocalExtremaIndex].index}, Y: ${extrema.maxima[currentFrequencyDomainLocalExtremaIndex].value.toFixed(2)})<br>
    <strong>Current Local Min:</strong> (X: ${extrema.minima[currentFrequencyDomainLocalExtremaIndex].index}, Y: ${extrema.minima[currentFrequencyDomainLocalExtremaIndex].value.toFixed(2)})
  `;
}

// Initial update of value display box with initial local and global extrema
var initialData = fftResults[19]; // Assuming data for the initial column
var initialExtrema = findFrequencyDomainExtrema(initialData);
updateValueDisplayFrequencyDomain(initialExtrema);

// Add click event listener to the chart canvas
ctxFrequencyDomain.canvas.addEventListener('click', function(evt) {
    var activePoints = frequencyDomainChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);

    if (activePoints.length > 0) {
        var clickedIndexFD = activePoints[0].index;

        currentFrequencyDomainLocalExtremaIndex = clickedIndexFD;

        // Update annotations and display box to move both maxima and minima
        moveAnnotationsToIndexFrequencyDomain(clickedIndexFD);
    }

    // Handle reset to initial annotations after frequent clicks
    if (frequencyDomainExtremaAnnotations.length > initialFrequencyDomainAnnotations.length * 2) {
        frequencyDomainExtremaAnnotations = initialFrequencyDomainAnnotations.slice(0); // Reset to initial annotations
    }

    // Update chart annotations for Frequency Domain
    frequencyDomainChart.options.plugins.annotation.annotations = frequencyDomainExtremaAnnotations;
    frequencyDomainChart.update();
});



// Function to move both maxima and minima annotations to the clicked index
function moveAnnotationsToIndexFrequencyDomain(clickedIndexFD) {
// Remove previous local extrema annotations for Frequency Domain
frequencyDomainExtremaAnnotations = frequencyDomainExtremaAnnotations.filter(function(annotation) {
return annotation.label.content.indexOf('Local') === -1;
});
// Initialize variables to store nearest maxima and minima values
var nearestMaxValue = null;
var nearestMinValue = null;

// Add the nearest local maxima and minima annotations for Frequency Domain at the clicked index
frequencyDomainDatasets.forEach(function(dataset, idx) {
    var data = dataset.data;
    var extrema = findFrequencyDomainExtrema(data);

    // Find the nearest local maxima to the clicked index
    var nearestMax = extrema.maxima.reduce(function(prev, curr) {
        return Math.abs(curr.index - clickedIndexFD) < Math.abs(prev.index - clickedIndexFD) ? curr : prev;
    });

    // Find the nearest local minima to the clicked index
    var nearestMin = extrema.minima.reduce(function(prev, curr) {
        return Math.abs(curr.index - clickedIndexFD) < Math.abs(prev.index - clickedIndexFD) ? curr : prev;
    });

    // Update nearest maxima and minima values
    if (nearestMax) {
        nearestMaxValue = nearestMax.value;
    }

    if (nearestMin) {
        nearestMinValue = nearestMin.value;
    }

    // Add annotation for the nearest local maxima
    if (nearestMax) {
        frequencyDomainExtremaAnnotations.push({
            type: 'point',
            backgroundColor: 'red',
            borderColor: 'red',
            radius: 5,
            xValue: nearestMax.index,
            yValue: nearestMax.value,
            label: {
                enabled: true,
                content: 'Local Max: ' + nearestMax.value.toFixed(2),
                position: 'top'
            }
        });
    }

    // Add annotation for the nearest local minima
    if (nearestMin) {
        frequencyDomainExtremaAnnotations.push({
            type: 'point',
            backgroundColor: 'blue',
            borderColor: 'blue',
            radius: 5,
            xValue: nearestMin.index,
            yValue: nearestMin.value,
            label: {
                enabled: true,
                content: 'Local Min: ' + nearestMin.value.toFixed(2),
                position: 'bottom'
            }
        });
    }

    // Update value display box for Frequency Domain within the same scope
    var globalExtremaDiv = document.getElementById('globalExtremaFrequencyDomain');
    globalExtremaDiv.innerHTML = `
        <strong>Global Max:</strong> (X: ${extrema.globalMax.index}, Y: ${extrema.globalMax.value.toFixed(2)})<br>
        <strong>Global Min:</strong> (X: ${extrema.globalMin.index}, Y: ${extrema.globalMin.value.toFixed(2)})
    `;

    var currentExtremaDiv = document.getElementById('currentExtremaFrequencyDomain');
    currentExtremaDiv.innerHTML = `
        <strong>Current Local Max:</strong> (X: ${nearestMax.index}, Y: ${nearestMaxValue.toFixed(2)})<br>
        <strong>Current Local Min:</strong> (X: ${nearestMin.index}, Y: ${nearestMinValue.toFixed(2)})
    `;
});

// Update chart annotations for Frequency Domain
frequencyDomainChart.options.plugins.annotation.annotations = frequencyDomainExtremaAnnotations;
frequencyDomainChart.update();
}

 // Function to save both charts as one image
        function saveCharts() {
            var tmpCanvas = document.createElement('canvas');
            tmpCanvas.width = 1800; // Total width of both charts
            tmpCanvas.height = 1000; // Height of each chart
            var tmpCtx = tmpCanvas.getContext('2d');

            // Set background color to white
            tmpCtx.fillStyle = 'white';
            tmpCtx.fillRect(0, 0, tmpCanvas.width, tmpCanvas.height);

            // Draw FFT Chart on tmpCanvas
            tmpCtx.drawImage(frequencyDomainChart.canvas, 0, 0);
            // Draw Time Domain Chart on tmpCanvas
            tmpCtx.drawImage(timeDomainChart.canvas, 900, 0);

            // Save the canvas as an image
            var link = document.createElement('a');
            link.href = tmpCanvas.toDataURL('image/png');
            link.download = 'combined_charts.png';
            link.click();
        }

    </script>
@endsection


