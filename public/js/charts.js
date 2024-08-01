let localMinMaxTime = [];
let localMinMaxFreq = [];
let localMinIndexTime = 0;
let localMinIndexFreq = 0;
let currentMultipleValueTime = null;
let currentMultipleValueFreq = null;
let originalValuesTime = [];
let originalValuesFreq = [];
const localWindowSize = 100;

let currentLocalMinMaxIndexTime = 0;
let currentLocalMinMaxIndexFreq = 0;


function getMin(data) {
    return Math.min(...data);
}

function getMax(data) {
    return Math.max(...data);
}

function updateInfoBoxTime(globalMax, globalMin, localMax, localMin) {
    document.getElementById('globalMaxTime').textContent = globalMax ? `(X: ${globalMax.x}, Y: ${globalMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('globalMinTime').textContent = globalMin ? `(X: ${globalMin.x}, Y: ${globalMin.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMaxTime').textContent = localMax ? `(X: ${localMax.x}, Y: ${localMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMinTime').textContent = localMin ? `(X: ${localMin.x}, Y: ${localMin.y.toFixed(2)})` : 'N/A';
}

function updateInfoBoxFreq(globalMax, globalMin, localMax, localMin) {
    document.getElementById('globalMaxFreq').textContent = globalMax ? `(X: ${globalMax.x}, Y: ${globalMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('globalMinFreq').textContent = globalMin ? `(X: ${globalMin.x}, Y: ${globalMin.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMaxFreq').textContent = localMax ? `(X: ${localMax.x}, Y: ${localMax.y.toFixed(2)})` : 'N/A';
    document.getElementById('currentLocalMinFreq').textContent = localMin ? `(X: ${localMin.x}, Y: ${localMin.y.toFixed(2)})` : 'N/A';
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

function showLocalMinMaxTime(chart, data) {
    const localMin = localMinMaxTime[localMinIndexTime]?.min ?? null;
    const localMax = localMinMaxTime[localMinIndexTime]?.max ?? null;
    const localMinIndexValue = localMinMaxTime[localMinIndexTime]?.minIndex ?? null;
    const localMaxIndexValue = localMinMaxTime[localMinIndexTime]?.maxIndex ?? null;

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

    updateInfoBoxTime({ x: data.indexOf(getMax(data)), y: getMax(data) }, { x: data.indexOf(getMin(data)), y: getMin(data) }, { x: localMaxIndexValue, y: localMax }, { x: localMinIndexValue, y: localMin });

    chart.update();
}

function showLocalMinMaxFreq(chart, data) {
    const localMin = localMinMaxFreq[localMinIndexFreq]?.min ?? null;
    const localMax = localMinMaxFreq[localMinIndexFreq]?.max ?? null;
    const localMinIndexValue = localMinMaxFreq[localMinIndexFreq]?.minIndex ?? null;
    const localMaxIndexValue = localMinMaxFreq[localMinIndexFreq]?.maxIndex ?? null;

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

    updateInfoBoxFreq({ x: data.indexOf(getMax(data)), y: getMax(data) }, { x: data.indexOf(getMin(data)), y: getMin(data) }, { x: localMaxIndexValue, y: localMax }, { x: localMinIndexValue, y: localMin });

    chart.update();
}

function markMultiples(chart, data, value, chartId) {
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
    const clearButtonId = `clear${chartId}Domain`;
    const deleteButtonId = `delete${chartId}Domain`;

    const clearButton = document.getElementById(clearButtonId);
    const deleteButton = document.getElementById(deleteButtonId);

    if (clearButton) {
        clearButton.disabled = false;
    } else {
        console.error(`Clear button with ID '${clearButtonId}' not found.`);
    }

    if (deleteButton) {
        deleteButton.disabled = false;
        if (chartId === 'Time') {
            currentMultipleValueTime = roundedValue;
        } else if (chartId === 'Freq') {
            currentMultipleValueFreq = roundedValue;
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

function createChart(ctx, data, label, clearButtonId, deleteButtonId, chartId) {
    if (chartId === 'Time') {
        originalValuesTime = [...data]; // Store the original values
    } else {
        originalValuesFreq = [...data]; // Store the original values
    }

    const chart = initializeChart(ctx, data, label);
    setupZoom(chart);
    setupLongPress(chart, data, chartId); // Pass chartId here
    updateInfo(chart, data, chartId);

    // Add double click event listener to shift local min/max on double click
    addDoubleClickEventListener(chart, data, chartId); // Add this line

    // Disable clear and delete buttons initially
    document.getElementById(clearButtonId).disabled = true;
    document.getElementById(deleteButtonId).disabled = true;

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



function setupLongPress(chart, data, chartId) {
    let longPressTimer;
    const longPressDuration = 500; // 500 milliseconds for a long press

    chart.canvas.addEventListener('mousedown', (event) => {
        const points = chart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);
        if (points.length) {
            longPressTimer = setTimeout(() => {
                const index = points[0].index;
                const value = chart.data.datasets[0].data[index];
                console.log(`Long press detected at index ${index} with value ${value}`);
                markMultiples(chart, data, value, chartId); // Pass chartId here
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

function updateInfo(chart, data, chartId) {
    const globalMax = { x: data.indexOf(getMax(data)), y: getMax(data) };
    const globalMin = { x: data.indexOf(getMin(data)), y: getMin(data) };
    const localMinMax = findLocalMinMax(data, 100);
    const currentLocalMinMax = localMinMax[0] || {};
    const localMax = { x: currentLocalMinMax.maxIndex, y: currentLocalMinMax.max };
    const localMin = { x: currentLocalMinMax.minIndex, y: currentLocalMinMax.min };

    if (chartId === 'Time') {
        localMinMaxTime = localMinMax;
        showLocalMinMaxTime(chart, data);
    } else {
        localMinMaxFreq = localMinMax;
        showLocalMinMaxFreq(chart, data);
    }
}

// Event listeners for navigation buttons
document.getElementById('prevMinMaxTime').addEventListener('click', () => {
    if (currentLocalMinMaxIndexTime > 0) {
        currentLocalMinMaxIndexTime -= 1;
        const { minIndex, maxIndex } = localMinMaxTime[currentLocalMinMaxIndexTime];
        const localMin = localMinMaxTime[currentLocalMinMaxIndexTime].min;
        const localMax = localMinMaxTime[currentLocalMinMaxIndexTime].max;

        const localMinDataset = timeDomainChart.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = timeDomainChart.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxTime(
            { x: timeDomainChart.data.datasets[0].data.indexOf(getMax(timeDomainChart.data.datasets[0].data)), y: getMax(timeDomainChart.data.datasets[0].data) },
            { x: timeDomainChart.data.datasets[0].data.indexOf(getMin(timeDomainChart.data.datasets[0].data)), y: getMin(timeDomainChart.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        timeDomainChart.update();
    }
});

document.getElementById('nextMinMaxTime').addEventListener('click', () => {
    if (currentLocalMinMaxIndexTime < localMinMaxTime.length - 1) {
        currentLocalMinMaxIndexTime += 1;
        const { minIndex, maxIndex } = localMinMaxTime[currentLocalMinMaxIndexTime];
        const localMin = localMinMaxTime[currentLocalMinMaxIndexTime].min;
        const localMax = localMinMaxTime[currentLocalMinMaxIndexTime].max;

        const localMinDataset = timeDomainChart.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = timeDomainChart.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxTime(
            { x: timeDomainChart.data.datasets[0].data.indexOf(getMax(timeDomainChart.data.datasets[0].data)), y: getMax(timeDomainChart.data.datasets[0].data) },
            { x: timeDomainChart.data.datasets[0].data.indexOf(getMin(timeDomainChart.data.datasets[0].data)), y: getMin(timeDomainChart.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        timeDomainChart.update();
    }
});

document.getElementById('prevMinMaxFreq').addEventListener('click', () => {
    if (currentLocalMinMaxIndexFreq > 0) {
        currentLocalMinMaxIndexFreq -= 1;
        const { minIndex, maxIndex } = localMinMaxFreq[currentLocalMinMaxIndexFreq];
        const localMin = localMinMaxFreq[currentLocalMinMaxIndexFreq].min;
        const localMax = localMinMaxFreq[currentLocalMinMaxIndexFreq].max;

        const localMinDataset = freqDomainChart.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = freqDomainChart.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxFreq(
            { x: freqDomainChart.data.datasets[0].data.indexOf(getMax(freqDomainChart.data.datasets[0].data)), y: getMax(freqDomainChart.data.datasets[0].data) },
            { x: freqDomainChart.data.datasets[0].data.indexOf(getMin(freqDomainChart.data.datasets[0].data)), y: getMin(freqDomainChart.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        freqDomainChart.update();
    }
});

document.getElementById('nextMinMaxFreq').addEventListener('click', () => {
    if (currentLocalMinMaxIndexFreq < localMinMaxFreq.length - 1) {
        currentLocalMinMaxIndexFreq += 1;
        const { minIndex, maxIndex } = localMinMaxFreq[currentLocalMinMaxIndexFreq];
        const localMin = localMinMaxFreq[currentLocalMinMaxIndexFreq].min;
        const localMax = localMinMaxFreq[currentLocalMinMaxIndexFreq].max;

        const localMinDataset = freqDomainChart.data.datasets.find(dataset => dataset.label === 'Local Min');
        const localMaxDataset = freqDomainChart.data.datasets.find(dataset => dataset.label === 'Local Max');

        if (localMinDataset) {
            localMinDataset.data = [{ x: minIndex, y: localMin }];
        }

        if (localMaxDataset) {
            localMaxDataset.data = [{ x: maxIndex, y: localMax }];
        }

        updateInfoBoxFreq(
            { x: freqDomainChart.data.datasets[0].data.indexOf(getMax(freqDomainChart.data.datasets[0].data)), y: getMax(freqDomainChart.data.datasets[0].data) },
            { x: freqDomainChart.data.datasets[0].data.indexOf(getMin(freqDomainChart.data.datasets[0].data)), y: getMin(freqDomainChart.data.datasets[0].data) },
            { x: maxIndex, y: localMax },
            { x: minIndex, y: localMin }
        );

        freqDomainChart.update();
    }
});




// Event listeners for clear buttons
document.getElementById('clearTimeDomain').addEventListener('click', () => {
    currentMultipleValueTime = null;
    timeDomainChart.data.datasets = timeDomainChart.data.datasets.filter(dataset => !dataset.label.includes('Multiples of'));
    timeDomainChart.data.datasets[0].data = [...originalValuesTime];
    timeDomainChart.update();
    document.getElementById('clearTimeDomain').disabled = true;
    document.getElementById('deleteTimeDomain').disabled = true;
});

document.getElementById('clearFreqDomain').addEventListener('click', () => {
    currentMultipleValueFreq = null;
    freqDomainChart.data.datasets = freqDomainChart.data.datasets.filter(dataset => !dataset.label.includes('Multiples of'));
    freqDomainChart.data.datasets[0].data = [...originalValuesFreq];
    freqDomainChart.update();
    document.getElementById('clearFreqDomain').disabled = true;
    document.getElementById('deleteFreqDomain').disabled = true;
});

// Event listeners for delete buttons
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

// Event listener for save charts button
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

    // Time Domain Chart
    const timeDomainLink = document.createElement('a');
    timeDomainLink.download = 'time_domain_chart.png';
    timeDomainLink.href = getBase64ImageWithWhiteBackground(timeDomainChart);
    timeDomainLink.click();

    // Frequency Domain Chart
    const freqDomainLink = document.createElement('a');
    freqDomainLink.download = 'freq_domain_chart.png';
    freqDomainLink.href = getBase64ImageWithWhiteBackground(freqDomainChart);
    freqDomainLink.click();
});





function shiftLocalMinMax(chart, data, xValue, chartId) {
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
        if (chartId === 'Time') {
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

            updateInfoBoxTime(
                { x: data.indexOf(getMax(data)), y: getMax(data) },
                { x: data.indexOf(getMin(data)), y: getMin(data) },
                { x: localMaxIndex, y: localMax },
                { x: localMinIndex, y: localMin }
            );

            // Update the global index tracker
            currentLocalMinMaxIndexTime = localMinMaxTime.findIndex(minMax => minMax.minIndex === localMinIndex && minMax.maxIndex === localMaxIndex);
            chart.update();
        } else {
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

            updateInfoBoxFreq(
                { x: data.indexOf(getMax(data)), y: getMax(data) },
                { x: data.indexOf(getMin(data)), y: getMin(data) },
                { x: localMaxIndex, y: localMax },
                { x: localMinIndex, y: localMin }
            );

            // Update the global index tracker
            currentLocalMinMaxIndexFreq = localMinMaxFreq.findIndex(minMax => minMax.minIndex === localMinIndex && minMax.maxIndex === localMaxIndex);
            chart.update();
        }
    }
}

function addDoubleClickEventListener(chart, data, chartId) {
    chart.canvas.addEventListener('dblclick', function (event) {
        const xValue = chart.scales.x.getValueForPixel(event.offsetX);
        shiftLocalMinMax(chart, data, xValue, chartId);
    });
}


document.getElementById('goBack').addEventListener('click', function () {
    window.history.back();
});

console.log('Canvas Y:', document.getElementById('timeDomainChartY'));
console.log('Canvas Z:', document.getElementById('timeDomainChartZ'));
console.log('Values Y:', values.Y);
console.log('Values Z:', values.Z);
console.log('Values:', values);

