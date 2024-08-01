let localMinMax = [];
// let localMinIndex = 0;
// const localWindowSize = 100; // Assuming 100 as the window size

// Event listener for the previous button
document.getElementById('prevMinMax').addEventListener('click', () => {
    if (localMinIndex > 0) {
        localMinIndex -= 1;
        showLocalMinMax(timeDomainChart, timeDomainChart.data.datasets[0].data, 'Time');
        showLocalMinMax(freqDomainChart, freqDomainChart.data.datasets[0].data, 'Freq');
    }
});

// Event listener for the next button
document.getElementById('nextMinMax').addEventListener('click', () => {
    if (localMinIndex < localMinMax.length - 1) {
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
