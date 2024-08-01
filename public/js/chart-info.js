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
