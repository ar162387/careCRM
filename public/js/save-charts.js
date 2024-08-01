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
