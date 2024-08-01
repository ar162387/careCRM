let originalValuesTimeX = [];
let originalValuesTimeY = [];
let originalValuesTimeZ = [];
const localWindowSize = 100

function getMin(data) {
    return Math.min(...data);
}

function getMax(data) {
    return Math.max(...data);
}

function createChart(ctx, data, label, clearButtonId, deleteButtonId, chartId, id) {
    console.log(`Creating chart for ${id} with label: ${label}`);

    if (id == 'X') {
        originalValuesTimeX = [...data]; // Store the original values
        console.log('Stored original values for X');
    }
    else if (id == 'Y') {
        originalValuesTimeY = [...data]; // Store the original values
        console.log('Stored original values for Y');
    }
    else if (id == 'Z') {
        originalValuesTimeZ = [...data]; // Store the original values
        console.log('Stored original values for Z');
    }

    const chart = initializeChart(ctx, data, label);
    setupZoom(chart);
    setupLongPress(chart, data, chartId, id); // Pass chartId here
    updateInfo(chart, data, chartId, id);

    // Add double click event listener to shift local min/max on double click
    addDoubleClickEventListener(chart, data, chartId, id); // Add this line



    console.log(`Chart for ${id} created successfully`);
    return chart;
}



function initializeChart(ctx, data, label) {
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: Array.from({ length: data.length }, (_, i) => i + 1),
            datasets: getDatasets(data, label)
        },
        options: getChartOptions()
    });
}

function getDatasets(data, label) {
    return [{
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
    }];
}

function getChartOptions() {
    return {
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
            tooltip: {
                enabled: true,
                mode: 'index',
                intersect: false,
                callbacks: {
                    label: function (context) {
                        const rawValue = context.raw;
                        const yValue = typeof rawValue === 'number' ? rawValue.toFixed(2) : rawValue;
                        return `Y: ${yValue}`;
                    }
                }
            }
        },
        interaction: {
            mode: 'nearest',
            intersect: false,
            axis: 'x'
        },
        onHover: function (event, chartElement) {
            const chart = this; // Get the chart instance
            const points = chartElement[0]?.element;
            if (points) {
                const index = points.index;
                if (index !== undefined) {
                    chart.tooltip.setActiveElements([{ datasetIndex: 0, index }], { x: event.x, y: event.y });
                    chart.tooltip.update();
                }
            }
        }
    };
}


function setupZoom(chart) {
    chart.options.plugins.zoom = {
        pan: {
            enabled: false,  // Initially set to false; toggle this with the button
            mode: 'x',
            threshold: 10,
            onPan: function ({ chart }) {
                console.log('Panning:', chart);
            }
        },
        zoom: {
            wheel: {
                enabled: true,
                onZoom: function ({ chart }) {
                    console.log('Zooming:', chart);
                }
            },
            pinch: {
                enabled: true
            },
            mode: 'x',
        }
    };
    chart.update();
    console.log('Zoom and Pan setup:', chart.options.plugins.zoom);
}



function setupLongPress(chart, data, chartId, id) {
    let longPressTimer;
    const longPressDuration = 500; // 500 milliseconds for a long press

    chart.canvas.addEventListener('mousedown', (event) => {
        const points = chart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);
        if (points.length) {
            longPressTimer = setTimeout(() => {
                const index = points[0].index;
                const value = chart.data.datasets[0].data[index];
                console.log(`Long press detected at index ${index} with value ${value}`);
                markMultiples(chart, data, value, chartId, id); // Pass chartId here
            }, longPressDuration);
        }
    });

    chart.canvas.addEventListener('mouseup', () => {
        clearTimeout(longPressTimer);
    });

    chart.canvas.addEventListener('mouseout', () => {
        clearTimeout(longPressTimer);
    });
}

function updateInfo(chart, data, chartId, id) {
    const globalMax = { x: data.indexOf(getMax(data)), y: getMax(data) };
    const globalMin = { x: data.indexOf(getMin(data)), y: getMin(data) };
    const localMinMax = findLocalMinMax(data, 100);
    const currentLocalMinMax = localMinMax[0] || {};
    const localMax = { x: currentLocalMinMax.maxIndex, y: currentLocalMinMax.max };
    const localMin = { x: currentLocalMinMax.minIndex, y: currentLocalMinMax.min };

    if (id === 'X') {
        localMinMaxTimeX = localMinMax;
        showLocalMinMaxTimeX(chart, data);
    } else if (id === 'Y') {
        localMinMaxTimeY = localMinMax;
        showLocalMinMaxTimeY(chart, data);
    } else if (id === 'Z') {
        localMinMaxTimeZ = localMinMax;
        showLocalMinMaxTimeZ(chart, data);
    }
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


let localMinMaxTimeX = []
let localMinMaxTimeY = []
let localMinMaxTimeZ = []

let localMinIndexTimeX = 0;
let localMinIndexTimeY = 0;
let localMinIndexTimeZ = 0;

function showLocalMinMaxTimeX(chart, data) {
    const localMin = localMinMaxTimeX[localMinIndexTimeX]?.min ?? null;
    const localMax = localMinMaxTimeX[localMinIndexTimeX]?.max ?? null;
    const localMinIndexValue = localMinMaxTimeX[localMinIndexTimeX]?.minIndex ?? null;
    const localMaxIndexValue = localMinMaxTimeX[localMinIndexTimeX]?.maxIndex ?? null;

    const localMinDataset = chart.data.datasets.find(dataset => dataset.label === 'Local Min');
    const localMaxDataset = chart.data.datasets.find(dataset => dataset.label === 'Local Max');

    if (localMinDataset) {
        localMinDataset.data = [{ x: localMinIndexValue, y: localMin }];
    } else {
        chart.data.datasets.push({
            label: 'Local Min',
            data: [{ x: localMinIndexValue, y: localMin }],
            pointBackgroundColor: 'green',
            pointBorderColor: 'green',
            pointRadius: 8,
            type: 'scatter'
        });
    }

    if (localMaxDataset) {
        localMaxDataset.data = [{ x: localMaxIndexValue, y: localMax }];
    } else {
        chart.data.datasets.push({
            label: 'Local Max',
            data: [{ x: localMaxIndexValue, y: localMax }],
            pointBackgroundColor: 'orange',
            pointBorderColor: 'orange',
            pointRadius: 8,
            type: 'scatter'
        });
    }

    updateInfoBoxTimeX({ x: data.indexOf(getMax(data)), y: getMax(data) }, { x: data.indexOf(getMin(data)), y: getMin(data) }, { x: localMaxIndexValue, y: localMax }, { x: localMinIndexValue, y: localMin });

    chart.update();
}
function showLocalMinMaxTimeY(chart, data) {
    const localMin = localMinMaxTimeY[localMinIndexTimeY]?.min ?? null;
    const localMax = localMinMaxTimeY[localMinIndexTimeY]?.max ?? null;
    const localMinIndexValue = localMinMaxTimeY[localMinIndexTimeY]?.minIndex ?? null;
    const localMaxIndexValue = localMinMaxTimeY[localMinIndexTimeY]?.maxIndex ?? null;

    const localMinDataset = chart.data.datasets.find(dataset => dataset.label === 'Local Min');
    const localMaxDataset = chart.data.datasets.find(dataset => dataset.label === 'Local Max');

    if (localMinDataset) {
        localMinDataset.data = [{ x: localMinIndexValue, y: localMin }];
    } else {
        chart.data.datasets.push({
            label: 'Local Min',
            data: [{ x: localMinIndexValue, y: localMin }],
            pointBackgroundColor: 'green',
            pointBorderColor: 'green',
            pointRadius: 8,
            type: 'scatter'
        });
    }

    if (localMaxDataset) {
        localMaxDataset.data = [{ x: localMaxIndexValue, y: localMax }];
    } else {
        chart.data.datasets.push({
            label: 'Local Max',
            data: [{ x: localMaxIndexValue, y: localMax }],
            pointBackgroundColor: 'orange',
            pointBorderColor: 'orange',
            pointRadius: 8,
            type: 'scatter'
        });
    }

    updateInfoBoxTimeY({ x: data.indexOf(getMax(data)), y: getMax(data) }, { x: data.indexOf(getMin(data)), y: getMin(data) }, { x: localMaxIndexValue, y: localMax }, { x: localMinIndexValue, y: localMin });

    chart.update();
}
function showLocalMinMaxTimeZ(chart, data) {
    const localMin = localMinMaxTimeZ[localMinIndexTimeZ]?.min ?? null;
    const localMax = localMinMaxTimeZ[localMinIndexTimeZ]?.max ?? null;
    const localMinIndexValue = localMinMaxTimeZ[localMinIndexTimeZ]?.minIndex ?? null;
    const localMaxIndexValue = localMinMaxTimeZ[localMinIndexTimeZ]?.maxIndex ?? null;

    const localMinDataset = chart.data.datasets.find(dataset => dataset.label === 'Local Min');
    const localMaxDataset = chart.data.datasets.find(dataset => dataset.label === 'Local Max');

    if (localMinDataset) {
        localMinDataset.data = [{ x: localMinIndexValue, y: localMin }];
    } else {
        chart.data.datasets.push({
            label: 'Local Min',
            data: [{ x: localMinIndexValue, y: localMin }],
            pointBackgroundColor: 'green',
            pointBorderColor: 'green',
            pointRadius: 8,
            type: 'scatter'
        });
    }

    if (localMaxDataset) {
        localMaxDataset.data = [{ x: localMaxIndexValue, y: localMax }];
    } else {
        chart.data.datasets.push({
            label: 'Local Max',
            data: [{ x: localMaxIndexValue, y: localMax }],
            pointBackgroundColor: 'orange',
            pointBorderColor: 'orange',
            pointRadius: 8,
            type: 'scatter'
        });
    }

    updateInfoBoxTimeZ({ x: data.indexOf(getMax(data)), y: getMax(data) }, { x: data.indexOf(getMin(data)), y: getMin(data) }, { x: localMaxIndexValue, y: localMax }, { x: localMinIndexValue, y: localMin });

    chart.update();
}

function updateInfoBoxTimeX(globalMax, globalMin, localMax, localMin) {
    document.getElementById('globalMaxTimeX').textContent = globalMax ? `(X: ${globalMax.x}, Y: ${globalMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('globalMinTimeX').textContent = globalMin ? `(X: ${globalMin.x}, Y: ${globalMin.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMaxTimeX').textContent = localMax ? `(X: ${localMax.x}, Y: ${localMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMinTimeX').textContent = localMin ? `(X: ${localMin.x}, Y: ${localMin.y.toFixed(2)})` : 'N/A';
}
function updateInfoBoxTimeY(globalMax, globalMin, localMax, localMin) {
    document.getElementById('globalMaxTimeY').textContent = globalMax ? `(X: ${globalMax.x}, Y: ${globalMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('globalMinTimeY').textContent = globalMin ? `(X: ${globalMin.x}, Y: ${globalMin.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMaxTimeY').textContent = localMax ? `(X: ${localMax.x}, Y: ${localMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMinTimeY').textContent = localMin ? `(X: ${localMin.x}, Y: ${localMin.y.toFixed(2)})` : 'N/A';
}
function updateInfoBoxTimeZ(globalMax, globalMin, localMax, localMin) {
    document.getElementById('globalMaxTimeZ').textContent = globalMax ? `(X: ${globalMax.x}, Y: ${globalMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('globalMinTimeZ').textContent = globalMin ? `(X: ${globalMin.x}, Y: ${globalMin.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMaxTimeZ').textContent = localMax ? `(X: ${localMax.x}, Y: ${localMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMinTimeZ').textContent = localMin ? `(X: ${localMin.x}, Y: ${localMin.y.toFixed(2)})` : 'N/A';
}

function addDoubleClickEventListener(chart, data, chartId, id) {
    chart.canvas.addEventListener('dblclick', function (event) {
        const xValue = chart.scales.x.getValueForPixel(event.offsetX);
        shiftLocalMinMax(chart, data, xValue, chartId, id);
    });
}

let currentLocalMinMaxIndexTimeX = 0;
let currentLocalMinMaxIndexTimeY = 0;
let currentLocalMinMaxIndexTimeZ = 0;


function shiftLocalMinMax(chart, data, xValue, chartId, id) {
    const nearestIndex = Math.round(xValue) - 1; // Get the nearest index to the clicked position

    if (nearestIndex >= 0 && nearestIndex < data.length) {
        const start = Math.max(nearestIndex - Math.floor(localWindowSize / 2), 0);
        const end = Math.min(nearestIndex + Math.floor(localWindowSize / 2), data.length - 1);

        const windowData = data.slice(start, end + 1);

        const localMin = Math.min(...windowData);
        const localMax = Math.max(...windowData);
        const localMinIndex = start + windowData.indexOf(localMin);
        const localMaxIndex = start + windowData.indexOf(localMax);

        // Update the chart datasets with new local min and max
        if (id === 'X') {

            {
                const localMinDataset = chart.data.datasets.find(dataset => dataset.label === 'Local Min');
                const localMaxDataset = chart.data.datasets.find(dataset => dataset.label === 'Local Max');

                if (localMinDataset) {
                    localMinDataset.data = [{ x: localMinIndex, y: localMin }];
                } else {
                    chart.data.datasets.push({
                        label: 'Local Min',
                        data: [{ x: localMinIndex, y: localMin }],
                        pointBackgroundColor: 'green',
                        pointBorderColor: 'green',
                        pointRadius: 8,
                        type: 'scatter'
                    });
                }

                if (localMaxDataset) {
                    localMaxDataset.data = [{ x: localMaxIndex, y: localMax }];
                } else {
                    chart.data.datasets.push({
                        label: 'Local Max',
                        data: [{ x: localMaxIndex, y: localMax }],
                        pointBackgroundColor: 'orange',
                        pointBorderColor: 'orange',
                        pointRadius: 8,
                        type: 'scatter'
                    });
                }

                updateInfoBoxTimeX(
                    { x: data.indexOf(getMax(data)), y: getMax(data) },
                    { x: data.indexOf(getMin(data)), y: getMin(data) },
                    { x: localMaxIndex, y: localMax },
                    { x: localMinIndex, y: localMin }
                );

                // Update the global index tracker
                currentLocalMinMaxIndexTimeX = localMinMaxTimeX.findIndex(minMax => minMax.minIndex === localMinIndex && minMax.maxIndex === localMaxIndex);
                chart.update();
            }
        }
        else if (id === 'Y') {

            {
                const localMinDataset = chart.data.datasets.find(dataset => dataset.label === 'Local Min');
                const localMaxDataset = chart.data.datasets.find(dataset => dataset.label === 'Local Max');

                if (localMinDataset) {
                    localMinDataset.data = [{ x: localMinIndex, y: localMin }];
                } else {
                    chart.data.datasets.push({
                        label: 'Local Min',
                        data: [{ x: localMinIndex, y: localMin }],
                        pointBackgroundColor: 'green',
                        pointBorderColor: 'green',
                        pointRadius: 8,
                        type: 'scatter'
                    });
                }

                if (localMaxDataset) {
                    localMaxDataset.data = [{ x: localMaxIndex, y: localMax }];
                } else {
                    chart.data.datasets.push({
                        label: 'Local Max',
                        data: [{ x: localMaxIndex, y: localMax }],
                        pointBackgroundColor: 'orange',
                        pointBorderColor: 'orange',
                        pointRadius: 8,
                        type: 'scatter'
                    });
                }

                updateInfoBoxTimeY(
                    { x: data.indexOf(getMax(data)), y: getMax(data) },
                    { x: data.indexOf(getMin(data)), y: getMin(data) },
                    { x: localMaxIndex, y: localMax },
                    { x: localMinIndex, y: localMin }
                );

                // Update the global index tracker
                currentLocalMinMaxIndexTimeY = localMinMaxTimeY.findIndex(minMax => minMax.minIndex === localMinIndex && minMax.maxIndex === localMaxIndex);
                chart.update();
            }
        }
        else if (id === 'Z') {

            {
                const localMinDataset = chart.data.datasets.find(dataset => dataset.label === 'Local Min');
                const localMaxDataset = chart.data.datasets.find(dataset => dataset.label === 'Local Max');

                if (localMinDataset) {
                    localMinDataset.data = [{ x: localMinIndex, y: localMin }];
                } else {
                    chart.data.datasets.push({
                        label: 'Local Min',
                        data: [{ x: localMinIndex, y: localMin }],
                        pointBackgroundColor: 'green',
                        pointBorderColor: 'green',
                        pointRadius: 8,
                        type: 'scatter'
                    });
                }

                if (localMaxDataset) {
                    localMaxDataset.data = [{ x: localMaxIndex, y: localMax }];
                } else {
                    chart.data.datasets.push({
                        label: 'Local Max',
                        data: [{ x: localMaxIndex, y: localMax }],
                        pointBackgroundColor: 'orange',
                        pointBorderColor: 'orange',
                        pointRadius: 8,
                        type: 'scatter'
                    });
                }

                updateInfoBoxTimeZ(
                    { x: data.indexOf(getMax(data)), y: getMax(data) },
                    { x: data.indexOf(getMin(data)), y: getMin(data) },
                    { x: localMaxIndex, y: localMax },
                    { x: localMinIndex, y: localMin }
                );

                // Update the global index tracker
                currentLocalMinMaxIndexTimeZ = localMinMaxTimeZ.findIndex(minMax => minMax.minIndex === localMinIndex && minMax.maxIndex === localMaxIndex);
                chart.update();
            }
        }

    }
}


let currentMultipleValueTimeX = null;
let currentMultipleValueTimeY = null;
let currentMultipleValueTimeZ = null;

function markMultiples(chart, data, value, chartId, id) {
    console.log(`Marking multiples for chartId: ${chartId}`);  // Debug log

    // Round the clicked value to 2 decimal places
    const roundedValue = Math.round(value * 100) / 100;

    const multiplesData = data.map((v, i) => {
        // Round the data point value to 2 decimal places
        const roundedV = Math.round(v * 100) / 100;
        if (roundedV !== 0 && roundedV % roundedValue === 0 && roundedV >= roundedValue) {  // Check for exact multiples greater than or equal to the clicked value
            return { x: i + 1, y: v };  // Ensure x coordinate is the same as in the chart
        }
        return null;
    }).filter(item => item !== null);

    const multiplesDataset = {
        label: `Multiples of ${roundedValue}`,
        data: multiplesData,
        pointBackgroundColor: 'purple',
        pointBorderColor: 'purple',
        pointRadius: 8,
        type: 'scatter'
    };

    chart.data.datasets = chart.data.datasets.filter(dataset => !dataset.label.includes('Multiples of'));
    chart.data.datasets.push(multiplesDataset);
    chart.update();

    // Enable clear and delete buttons
    const clearButtonId = `clear${chartId}Domain${id}`;
    const deleteButtonId = `delete${chartId}Domain${id}`;

    const clearButton = document.getElementById(clearButtonId);
    const deleteButton = document.getElementById(deleteButtonId);


    console.error(`Clear button with ID '${clearButtonId}' not found.`);


    if (deleteButton) {
        deleteButton.disabled = false;
        if (id === 'X') {
            currentMultipleValueTimeX = roundedValue;
        } else if (id === 'Y') {
            currentMultipleValueTimeY = roundedValue;
        }
        else if (id === 'Z') {
            currentMultipleValueTimeZ = roundedValue;
        }
    } else {
        console.error(`Delete button with ID '${deleteButtonId}' not found.`);
    }
}

function deleteMultiples(chart, data, value) {
    // Round the value to match the marked multiples (to 2 decimal places)
    const roundedValue = Math.round(value * 100) / 100;

    // A set to store indices of data points that need to be deleted
    const indicesToDelete = new Set();

    // Iterate over the data to find multiples of the rounded value
    data.forEach((v, i) => {
        // Round the data point value to 2 decimal places
        const roundedV = Math.round(v * 100) / 100;

        // Check if the data point is a multiple of the rounded value and greater than or equal to the rounded value
        if (roundedV !== 0 && roundedV % roundedValue === 0 && roundedV >= roundedValue) {
            // Calculate the range to delete around the index
            // Start index is 10% of the data length before the current index
            const start = Math.max(i - Math.floor(data.length * 0.01), 0);

            // End index is 10% of the data length after the current index
            const end = Math.min(i + Math.floor(data.length * 0.01), data.length - 1);

            // Add all indices in this range to the set
            for (let j = start; j <= end; j++) {
                indicesToDelete.add(j);
            }
        }
    });

    // Update the chart's datasets
    chart.data.datasets.forEach((dataset) => {
        if (dataset.label.includes('Time Domain') || dataset.label.includes('Frequency Domain')) {
            // Set data points to null if their index is in the indicesToDelete set
            dataset.data = dataset.data.map((point, index) => indicesToDelete.has(index) ? null : point);
        }
    });

    // Update the chart to reflect changes
    chart.update();
}

document.getElementById('deleteTimeDomainX').addEventListener('click', () => {
    if (currentMultipleValueTimeX !== null) {
        deleteMultiples(timeDomainChartX, timeDomainChartX.data.datasets[0].data, currentMultipleValueTimeX);
        currentMultipleValueTimeX = null;
        document.getElementById('deleteTimeDomainX').disabled = false;
    }
});
document.getElementById('deleteTimeDomainY').addEventListener('click', () => {
    if (currentMultipleValueTimeY !== null) {
        deleteMultiples(timeDomainChartY, timeDomainChartY.data.datasets[0].data, currentMultipleValueTimeY);
        currentMultipleValueTimeY = null;
        document.getElementById('deleteTimeDomainY').disabled = false;
    }
});
document.getElementById('deleteTimeDomainZ').addEventListener('click', () => {
    if (currentMultipleValueTimeZ !== null) {
        deleteMultiples(timeDomainChartZ, timeDomainChartZ.data.datasets[0].data, currentMultipleValueTimeZ);
        currentMultipleValueTimeZ = null;
        document.getElementById('deleteTimeDomainZ').disabled = false;
    }
});

document.getElementById('clearTimeDomainX').addEventListener('click', () => {
    currentMultipleValueFreqX = null;
    timeDomainChartX.data.datasets = timeDomainChartX.data.datasets.filter(dataset => !dataset.label.includes('Multiples of'));
    timeDomainChartX.data.datasets[0].data = [...originalValuesTimeX];
    timeDomainChartX.update();
    document.getElementById('clearTimeDomainX').disabled = false;
    document.getElementById('deleteTimeDomainX').disabled = false;
});
document.getElementById('clearTimeDomainY').addEventListener('click', () => {
    currentMultipleValueFreqY = null;
    timeDomainChartY.data.datasets = timeDomainChartY.data.datasets.filter(dataset => !dataset.label.includes('Multiples of'));
    timeDomainChartY.data.datasets[0].data = [...originalValuesTimeY];
    timeDomainChartY.update();
    document.getElementById('clearTimeDomainY').disabled = false;
    document.getElementById('deleteTimeDomainY').disabled = false;
});

document.getElementById('clearTimeDomainZ').addEventListener('click', () => {
    currentMultipleValueFreqZ = null;
    timeDomainChartZ.data.datasets = timeDomainChartZ.data.datasets.filter(dataset => !dataset.label.includes('Multiples of'));
    timeDomainChartZ.data.datasets[0].data = [...originalValuesTimeZ];
    timeDomainChartZ.update();
    document.getElementById('clearTimeDomainZ').disabled = false;
    document.getElementById('deleteTimeDomainZ').disabled = false;
});


document.getElementById('saveCharts').addEventListener('click', () => {
    // Function to set white background and get base64 image
    const getBase64ImageWithWhiteBackground = (chart) => {
        const canvas = chart.canvas;
        const ctx = canvas.getContext('2d');

        // Create a temporary canvas to draw the chart with white background
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = canvas.width;
        tempCanvas.height = canvas.height;
        const tempCtx = tempCanvas.getContext('2d');

        // Fill with white background
        tempCtx.fillStyle = 'white';
        tempCtx.fillRect(0, 0, canvas.width, canvas.height);

        // Draw the original chart onto the white background
        tempCtx.drawImage(canvas, 0, 0);

        // Return the base64 image data
        return tempCanvas.toDataURL('image/png');
    };

    // Time Domain Chart X
    const timeDomainLinkX = document.createElement('a');
    timeDomainLinkX.download = 'time_domain_chart_X.png';
    timeDomainLinkX.href = getBase64ImageWithWhiteBackground(timeDomainChartX);
    timeDomainLinkX.click();

    // Time Domain Chart Y
    const timeDomainLinkY = document.createElement('a');
    timeDomainLinkY.download = 'time_domain_chart_Y.png';
    timeDomainLinkY.href = getBase64ImageWithWhiteBackground(timeDomainChartY);
    timeDomainLinkY.click();

    // Time Domain Chart Z
    const timeDomainLinkZ = document.createElement('a');
    timeDomainLinkZ.download = 'time_domain_chart_Z.png';
    timeDomainLinkZ.href = getBase64ImageWithWhiteBackground(timeDomainChartZ);
    timeDomainLinkZ.click();
});
document.getElementById('goBack').addEventListener('click', function () {
    window.history.back();
});

document.getElementById('prevMinMaxTimeX').addEventListener('click', () => {
    if (currentLocalMinMaxIndexTimeX > 0) {
        currentLocalMinMaxIndexTimeX -= 1;
        const { minIndex, maxIndex } = localMinMaxTimeX[currentLocalMinMaxIndexTimeX];
        const localMin = localMinMaxTimeX[currentLocalMinMaxIndexTimeX].min;
        const localMax = localMinMaxTimeX[currentLocalMinMaxIndexTimeX].max;

        const localMinDataset = timeDomainChartX.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = timeDomainChartX.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxTimeX(
            { x: timeDomainChartX.data.datasets[0].data.indexOf(getMax(timeDomainChartX.data.datasets[0].data)), y: getMax(timeDomainChartX.data.datasets[0].data) },
            { x: timeDomainChartX.data.datasets[0].data.indexOf(getMin(timeDomainChartX.data.datasets[0].data)), y: getMin(timeDomainChartX.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        timeDomainChartX.update();
    }
});
document.getElementById('prevMinMaxTimeY').addEventListener('click', () => {
    if (currentLocalMinMaxIndexTimeY > 0) {
        currentLocalMinMaxIndexTimeY -= 1;
        const { minIndex, maxIndex } = localMinMaxTimeY[currentLocalMinMaxIndexTimeY];
        const localMin = localMinMaxTimeY[currentLocalMinMaxIndexTimeY].min;
        const localMax = localMinMaxTimeY[currentLocalMinMaxIndexTimeY].max;

        const localMinDataset = timeDomainChartY.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = timeDomainChartY.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxTimeY(
            { x: timeDomainChartY.data.datasets[0].data.indexOf(getMax(timeDomainChartY.data.datasets[0].data)), y: getMax(timeDomainChartY.data.datasets[0].data) },
            { x: timeDomainChartY.data.datasets[0].data.indexOf(getMin(timeDomainChartY.data.datasets[0].data)), y: getMin(timeDomainChartY.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        timeDomainChartY.update();
    }
});
document.getElementById('prevMinMaxTimeZ').addEventListener('click', () => {
    if (currentLocalMinMaxIndexTimeZ > 0) {
        currentLocalMinMaxIndexTimeZ -= 1;
        const { minIndex, maxIndex } = localMinMaxTimeZ[currentLocalMinMaxIndexTimeZ];
        const localMin = localMinMaxTimeZ[currentLocalMinMaxIndexTimeZ].min;
        const localMax = localMinMaxTimeZ[currentLocalMinMaxIndexTimeZ].max;

        const localMinDataset = timeDomainChartZ.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = timeDomainChartZ.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxTimeZ(
            { x: timeDomainChartZ.data.datasets[0].data.indexOf(getMax(timeDomainChartZ.data.datasets[0].data)), y: getMax(timeDomainChartZ.data.datasets[0].data) },
            { x: timeDomainChartZ.data.datasets[0].data.indexOf(getMin(timeDomainChartZ.data.datasets[0].data)), y: getMin(timeDomainChartZ.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        timeDomainChartZ.update();
    }
});

document.getElementById('nextMinMaxTimeX').addEventListener('click', () => {
    if (currentLocalMinMaxIndexTimeX < localMinMaxTimeX.length - 1) {
        currentLocalMinMaxIndexTimeX += 1;
        const { minIndex, maxIndex } = localMinMaxTimeX[currentLocalMinMaxIndexTimeX];
        const localMin = localMinMaxTimeX[currentLocalMinMaxIndexTimeX].min;
        const localMax = localMinMaxTimeX[currentLocalMinMaxIndexTimeX].max;

        const localMinDataset = timeDomainChartX.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = timeDomainChartX.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxTimeX(
            { x: timeDomainChartX.data.datasets[0].data.indexOf(getMax(timeDomainChartX.data.datasets[0].data)), y: getMax(timeDomainChartX.data.datasets[0].data) },
            { x: timeDomainChartX.data.datasets[0].data.indexOf(getMin(timeDomainChartX.data.datasets[0].data)), y: getMin(timeDomainChartX.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        timeDomainChartX.update();
    }
});
document.getElementById('nextMinMaxTimeY').addEventListener('click', () => {
    if (currentLocalMinMaxIndexTimeY < localMinMaxTimeY.length - 1) {
        currentLocalMinMaxIndexTimeY += 1;
        const { minIndex, maxIndex } = localMinMaxTimeY[currentLocalMinMaxIndexTimeY];
        const localMin = localMinMaxTimeY[currentLocalMinMaxIndexTimeY].min;
        const localMax = localMinMaxTimeY[currentLocalMinMaxIndexTimeY].max;

        const localMinDataset = timeDomainChartY.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = timeDomainChartY.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxTimeY(
            { x: timeDomainChartY.data.datasets[0].data.indexOf(getMax(timeDomainChartY.data.datasets[0].data)), y: getMax(timeDomainChartY.data.datasets[0].data) },
            { x: timeDomainChartY.data.datasets[0].data.indexOf(getMin(timeDomainChartY.data.datasets[0].data)), y: getMin(timeDomainChartY.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        timeDomainChartY.update();
    }
});
document.getElementById('nextMinMaxTimeZ').addEventListener('click', () => {
    if (currentLocalMinMaxIndexTimeZ < localMinMaxTimeZ.length - 1) {
        currentLocalMinMaxIndexTimeZ += 1;
        const { minIndex, maxIndex } = localMinMaxTimeZ[currentLocalMinMaxIndexTimeZ];
        const localMin = localMinMaxTimeZ[currentLocalMinMaxIndexTimeZ].min;
        const localMax = localMinMaxTimeZ[currentLocalMinMaxIndexTimeZ].max;

        const localMinDataset = timeDomainChartZ.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = timeDomainChartZ.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxTimeZ(
            { x: timeDomainChartZ.data.datasets[0].data.indexOf(getMax(timeDomainChartZ.data.datasets[0].data)), y: getMax(timeDomainChartZ.data.datasets[0].data) },
            { x: timeDomainChartZ.data.datasets[0].data.indexOf(getMin(timeDomainChartZ.data.datasets[0].data)), y: getMin(timeDomainChartZ.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        timeDomainChartZ.update();
    }
});












