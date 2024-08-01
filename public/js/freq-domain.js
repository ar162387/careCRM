let originalValuesFreqX = [];
let originalValuesFreqY = [];
let originalValuesFreqZ = [];


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

function createChart(ctx, data, label, clearButtonId, deleteButtonId, chartId, id) {
    if (id === 'X') {
        originalValuesFreqX = [...data];// Store the original values
    }
    else if (id === 'Y') {
        originalValuesFreqY = [...data]; // Store the original values
    }
    else if (id === 'Z') {
        originalValuesFreqZ = [...data];
    }

    const chart = initializeChart(ctx, data, label);
    setupZoom(chart);
    setupLongPress(chart, data, chartId, id); // Pass chartId here
    updateInfo(chart, data, chartId, id);

    // Add double click event listener to shift local min/max on double click
    addDoubleClickEventListener(chart, data, chartId, id); // Add this line

    // Disable clear and delete buttons initially
    document.getElementById(clearButtonId).disabled = false;
    document.getElementById(deleteButtonId).disabled = false;

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
        localMinMaxFreqX = localMinMax;
        showLocalMinMaxFreqX(chart, data);
    } else if (id === 'Y') {
        localMinMaxFreqY = localMinMax;
        showLocalMinMaxFreqY(chart, data);
    } else if (id === 'Z') {
        localMinMaxFreqZ = localMinMax;
        showLocalMinMaxFreqZ(chart, data);
    }
}

function getMin(data) {
    return Math.min(...data);
}

function getMax(data) {
    return Math.max(...data);
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


let localMinMaxFreqX = []
let localMinMaxFreqY = []
let localMinMaxFreqZ = []

let localMinIndexFreqX = 0;
let localMinIndexFreqY = 0;
let localMinIndexFreqZ = 0;


function showLocalMinMaxFreqX(chart, data) {
    const localMin = localMinMaxFreqX[localMinIndexFreqX]?.min ?? null;
    const localMax = localMinMaxFreqX[localMinIndexFreqX]?.max ?? null;
    const localMinIndexValue = localMinMaxFreqX[localMinIndexFreqX]?.minIndex ?? null;
    const localMaxIndexValue = localMinMaxFreqX[localMinIndexFreqX]?.maxIndex ?? null;

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

    updateInfoBoxFreqX({ x: data.indexOf(getMax(data)), y: getMax(data) }, { x: data.indexOf(getMin(data)), y: getMin(data) }, { x: localMaxIndexValue, y: localMax }, { x: localMinIndexValue, y: localMin });

    chart.update();
}

function showLocalMinMaxFreqY(chart, data) {
    const localMin = localMinMaxFreqY[localMinIndexFreqY]?.min ?? null;
    const localMax = localMinMaxFreqY[localMinIndexFreqY]?.max ?? null;
    const localMinIndexValue = localMinMaxFreqY[localMinIndexFreqY]?.minIndex ?? null;
    const localMaxIndexValue = localMinMaxFreqY[localMinIndexFreqY]?.maxIndex ?? null;

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

    updateInfoBoxFreqY({ x: data.indexOf(getMax(data)), y: getMax(data) }, { x: data.indexOf(getMin(data)), y: getMin(data) }, { x: localMaxIndexValue, y: localMax }, { x: localMinIndexValue, y: localMin });

    chart.update();
}

function showLocalMinMaxFreqZ(chart, data) {
    const localMin = localMinMaxFreqZ[localMinIndexFreqZ]?.min ?? null;
    const localMax = localMinMaxFreqZ[localMinIndexFreqZ]?.max ?? null;
    const localMinIndexValue = localMinMaxFreqZ[localMinIndexFreqZ]?.minIndex ?? null;
    const localMaxIndexValue = localMinMaxFreqZ[localMinIndexFreqZ]?.maxIndex ?? null;

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

    updateInfoBoxFreqZ({ x: data.indexOf(getMax(data)), y: getMax(data) }, { x: data.indexOf(getMin(data)), y: getMin(data) }, { x: localMaxIndexValue, y: localMax }, { x: localMinIndexValue, y: localMin });

    chart.update();
}

function updateInfoBoxFreqX(globalMax, globalMin, localMax, localMin) {
    document.getElementById('globalMaxFreqX').textContent = globalMax ? `(X: ${globalMax.x}, Y: ${globalMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('globalMinFreqX').textContent = globalMin ? `(X: ${globalMin.x}, Y: ${globalMin.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMaxFreqX').textContent = localMax ? `(X: ${localMax.x}, Y: ${localMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMinFreqX').textContent = localMin ? `(X: ${localMin.x}, Y: ${localMin.y.toFixed(2)})` : 'N/A';
}

function updateInfoBoxFreqY(globalMax, globalMin, localMax, localMin) {
    document.getElementById('globalMaxFreqY').textContent = globalMax ? `(X: ${globalMax.x}, Y: ${globalMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('globalMinFreqY').textContent = globalMin ? `(X: ${globalMin.x}, Y: ${globalMin.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMaxFreqY').textContent = localMax ? `(X: ${localMax.x}, Y: ${localMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMinFreqY').textContent = localMin ? `(X: ${localMin.x}, Y: ${localMin.y.toFixed(2)})` : 'N/A';
}

function updateInfoBoxFreqZ(globalMax, globalMin, localMax, localMin) {
    document.getElementById('globalMaxFreqZ').textContent = globalMax ? `(X: ${globalMax.x}, Y: ${globalMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('globalMinFreqZ').textContent = globalMin ? `(X: ${globalMin.x}, Y: ${globalMin.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMaxFreqZ').textContent = localMax ? `(X: ${localMax.x}, Y: ${localMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMinFreqZ').textContent = localMin ? `(X: ${localMin.x}, Y: ${localMin.y.toFixed(2)})` : 'N/A';
}

function addDoubleClickEventListener(chart, data, chartId, id) {
    chart.canvas.addEventListener('dblclick', function (event) {
        const xValue = chart.scales.x.getValueForPixel(event.offsetX);
        shiftLocalMinMax(chart, data, xValue, chartId, id);
    });
}


let currentLocalMinMaxIndexFreqX = 0;
let currentLocalMinMaxIndexFreqY = 0;
let currentLocalMinMaxIndexFreqZ = 0;
const localWindowSize = 100;

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

                updateInfoBoxFreqX(
                    { x: data.indexOf(getMax(data)), y: getMax(data) },
                    { x: data.indexOf(getMin(data)), y: getMin(data) },
                    { x: localMaxIndex, y: localMax },
                    { x: localMinIndex, y: localMin }
                );

                // Update the global index tracker
                currentLocalMinMaxIndexFreqX = localMinMaxFreqX.findIndex(minMax => minMax.minIndex === localMinIndex && minMax.maxIndex === localMaxIndex);
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

                updateInfoBoxFreqY(
                    { x: data.indexOf(getMax(data)), y: getMax(data) },
                    { x: data.indexOf(getMin(data)), y: getMin(data) },
                    { x: localMaxIndex, y: localMax },
                    { x: localMinIndex, y: localMin }
                );

                // Update the global index tracker
                currentLocalMinMaxIndexFreqY = localMinMaxFreqY.findIndex(minMax => minMax.minIndex === localMinIndex && minMax.maxIndex === localMaxIndex);
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

                updateInfoBoxFreqZ(
                    { x: data.indexOf(getMax(data)), y: getMax(data) },
                    { x: data.indexOf(getMin(data)), y: getMin(data) },
                    { x: localMaxIndex, y: localMax },
                    { x: localMinIndex, y: localMin }
                );

                // Update the global index tracker
                currentLocalMinMaxIndexFreqZ = localMinMaxFreqZ.findIndex(minMax => minMax.minIndex === localMinIndex && minMax.maxIndex === localMaxIndex);
                chart.update();
            }
        }

    }
}



let currentMultipleValueFreqX = null;
let currentMultipleValueFreqY = null;
let currentMultipleValueFreqZ = null;

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
            currentMultipleValueFreqX = roundedValue;
        } else if (id === 'Y') {
            currentMultipleValueFreqY = roundedValue;
        }
        else if (id === 'Z') {
            currentMultipleValueFreqZ = roundedValue;
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

document.getElementById('deleteFreqDomainX').addEventListener('click', () => {
    if (currentMultipleValueFreqX !== null) {
        deleteMultiples(freqDomainChartX, freqDomainChartX.data.datasets[0].data, currentMultipleValueFreqX);
        currentMultipleValueFreqX = null;
        document.getElementById('deleteFreqDomainX').disabled = false;
    }
});
document.getElementById('deleteFreqDomainY').addEventListener('click', () => {
    if (currentMultipleValueFreqY !== null) {
        deleteMultiples(freqDomainChartY, freqDomainChartY.data.datasets[0].data, currentMultipleValueFreqY);
        currentMultipleValueFreqY = null;
        document.getElementById('deleteFreqDomainY').disabled = false;
    }
});
document.getElementById('deleteFreqDomainZ').addEventListener('click', () => {
    if (currentMultipleValueFreqZ !== null) {
        deleteMultiples(freqDomainChartZ, freqDomainChartZ.data.datasets[0].data, currentMultipleValueFreqZ);
        currentMultipleValueFreqZ = null;
        document.getElementById('deleteFreqDomainZ').disabled = false;
    }
});

document.getElementById('clearFreqDomainX').addEventListener('click', () => {
    currentMultipleValueFreqX = null;
    freqDomainChartX.data.datasets = freqDomainChartX.data.datasets.filter(dataset => !dataset.label.includes('Multiples of'));
    freqDomainChartX.data.datasets[0].data = [...originalValuesFreqX];
    freqDomainChartX.update();
    document.getElementById('clearFreqDomainX').disabled = false;
    document.getElementById('deleteFreqDomainX').disabled = false;
});
document.getElementById('clearFreqDomainY').addEventListener('click', () => {
    currentMultipleValueFreqY = null;
    freqDomainChartY.data.datasets = freqDomainChartY.data.datasets.filter(dataset => !dataset.label.includes('Multiples of'));
    freqDomainChartY.data.datasets[0].data = [...originalValuesFreqY];
    freqDomainChartY.update();
    document.getElementById('clearFreqDomainY').disabled = false;
    document.getElementById('deleteFreqDomainY').disabled = false;
});
document.getElementById('clearFreqDomainZ').addEventListener('click', () => {
    currentMultipleValueFreqZ = null;
    freqDomainChartZ.data.datasets = freqDomainChartZ.data.datasets.filter(dataset => !dataset.label.includes('Multiples of'));
    freqDomainChartZ.data.datasets[0].data = [...originalValuesFreqZ];
    freqDomainChartZ.update();
    document.getElementById('clearFreqDomainZ').disabled = false;
    document.getElementById('deleteFreqDomainZ').disabled = false;
});


document.getElementById('prevMinMaxFreqX').addEventListener('click', () => {
    if (currentLocalMinMaxIndexFreqX > 0) {
        currentLocalMinMaxIndexFreqX -= 1;
        const { minIndex, maxIndex } = localMinMaxFreqX[currentLocalMinMaxIndexFreqX];
        const localMin = localMinMaxFreqX[currentLocalMinMaxIndexFreqX].min;
        const localMax = localMinMaxFreqX[currentLocalMinMaxIndexFreqX].max;

        const localMinDataset = freqDomainChartX.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = freqDomainChartX.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxFreqX(
            { x: freqDomainChartX.data.datasets[0].data.indexOf(getMax(freqDomainChartX.data.datasets[0].data)), y: getMax(freqDomainChartX.data.datasets[0].data) },
            { x: freqDomainChartX.data.datasets[0].data.indexOf(getMin(freqDomainChartX.data.datasets[0].data)), y: getMin(freqDomainChartX.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        freqDomainChartX.update();
    }
});
document.getElementById('prevMinMaxFreqY').addEventListener('click', () => {
    if (currentLocalMinMaxIndexFreqY > 0) {
        currentLocalMinMaxIndexFreqY -= 1;
        const { minIndex, maxIndex } = localMinMaxFreqY[currentLocalMinMaxIndexFreqY];
        const localMin = localMinMaxFreqY[currentLocalMinMaxIndexFreqY].min;
        const localMax = localMinMaxFreqY[currentLocalMinMaxIndexFreqY].max;

        const localMinDataset = freqDomainChartY.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = freqDomainChartY.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxFreqY(
            { x: freqDomainChartY.data.datasets[0].data.indexOf(getMax(freqDomainChartY.data.datasets[0].data)), y: getMax(freqDomainChartY.data.datasets[0].data) },
            { x: freqDomainChartY.data.datasets[0].data.indexOf(getMin(freqDomainChartY.data.datasets[0].data)), y: getMin(freqDomainChartY.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        freqDomainChartY.update();
    }
});
document.getElementById('prevMinMaxFreqZ').addEventListener('click', () => {
    if (currentLocalMinMaxIndexFreqZ > 0) {
        currentLocalMinMaxIndexFreqZ -= 1;
        const { minIndex, maxIndex } = localMinMaxFreqZ[currentLocalMinMaxIndexFreqZ];
        const localMin = localMinMaxFreqZ[currentLocalMinMaxIndexFreqZ].min;
        const localMax = localMinMaxFreqZ[currentLocalMinMaxIndexFreqZ].max;

        const localMinDataset = freqDomainChartZ.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = freqDomainChartZ.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxFreqZ(
            { x: freqDomainChartZ.data.datasets[0].data.indexOf(getMax(freqDomainChartZ.data.datasets[0].data)), y: getMax(freqDomainChartZ.data.datasets[0].data) },
            { x: freqDomainChartZ.data.datasets[0].data.indexOf(getMin(freqDomainChartZ.data.datasets[0].data)), y: getMin(freqDomainChartZ.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        freqDomainChartZ.update();
    }
});

document.getElementById('nextMinMaxFreqX').addEventListener('click', () => {
    if (currentLocalMinMaxIndexFreqX < localMinMaxFreqX.length - 1) {
        currentLocalMinMaxIndexFreqX += 1;
        const { minIndex, maxIndex } = localMinMaxFreqX[currentLocalMinMaxIndexFreqX];
        const localMin = localMinMaxFreqX[currentLocalMinMaxIndexFreqX].min;
        const localMax = localMinMaxFreqX[currentLocalMinMaxIndexFreqX].max;

        const localMinDataset = freqDomainChartX.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = freqDomainChartX.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxFreqX(
            { x: freqDomainChartX.data.datasets[0].data.indexOf(getMax(freqDomainChartX.data.datasets[0].data)), y: getMax(freqDomainChartX.data.datasets[0].data) },
            { x: freqDomainChartX.data.datasets[0].data.indexOf(getMin(freqDomainChartX.data.datasets[0].data)), y: getMin(freqDomainChartX.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        freqDomainChartX.update();
    }
});



document.getElementById('nextMinMaxFreqY').addEventListener('click', () => {
    if (currentLocalMinMaxIndexFreqY < localMinMaxFreqY.length - 1) {
        currentLocalMinMaxIndexFreqY += 1;
        const { minIndex, maxIndex } = localMinMaxFreqY[currentLocalMinMaxIndexFreqY];
        const localMin = localMinMaxFreqY[currentLocalMinMaxIndexFreqY].min;
        const localMax = localMinMaxFreqY[currentLocalMinMaxIndexFreqY].max;

        const localMinDataset = freqDomainChartY.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = freqDomainChartY.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxFreqY(
            { x: freqDomainChartY.data.datasets[0].data.indexOf(getMax(freqDomainChartY.data.datasets[0].data)), y: getMax(freqDomainChartY.data.datasets[0].data) },
            { x: freqDomainChartY.data.datasets[0].data.indexOf(getMin(freqDomainChartY.data.datasets[0].data)), y: getMin(freqDomainChartY.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        freqDomainChartY.update();
    }
});


document.getElementById('nextMinMaxFreqZ').addEventListener('click', () => {
    if (currentLocalMinMaxIndexFreqZ < localMinMaxFreqZ.length - 1) {
        currentLocalMinMaxIndexFreqZ += 1;
        const { minIndex, maxIndex } = localMinMaxFreqZ[currentLocalMinMaxIndexFreqZ];
        const localMin = localMinMaxFreqZ[currentLocalMinMaxIndexFreqZ].min;
        const localMax = localMinMaxFreqZ[currentLocalMinMaxIndexFreqZ].max;

        const localMinDataset = freqDomainChartZ.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = freqDomainChartZ.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxFreqZ(
            { x: freqDomainChartZ.data.datasets[0].data.indexOf(getMax(freqDomainChartZ.data.datasets[0].data)), y: getMax(freqDomainChartZ.data.datasets[0].data) },
            { x: freqDomainChartZ.data.datasets[0].data.indexOf(getMin(freqDomainChartZ.data.datasets[0].data)), y: getMin(freqDomainChartZ.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        freqDomainChartZ.update();
    }
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

    // Frequency Domain Chart X
    const freqDomainLinkX = document.createElement('a');
    freqDomainLinkX.download = 'freq_domain_chart_X.png';
    freqDomainLinkX.href = getBase64ImageWithWhiteBackground(freqDomainChartX);
    freqDomainLinkX.click();

    // Frequency Domain Chart Y
    const freqDomainLinkY = document.createElement('a');
    freqDomainLinkY.download = 'freq_domain_chart_Y.png';
    freqDomainLinkY.href = getBase64ImageWithWhiteBackground(freqDomainChartY);
    freqDomainLinkY.click();

    // Frequency Domain Chart Z
    const freqDomainLinkZ = document.createElement('a');
    freqDomainLinkZ.download = 'freq_domain_chart_Z.png';
    freqDomainLinkZ.href = getBase64ImageWithWhiteBackground(freqDomainChartZ);
    freqDomainLinkZ.click();
});


document.getElementById('goBack').addEventListener('click', function () {
    window.history.back();
});