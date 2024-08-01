<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FFT of Complete CSV File</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f7f7f7;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        canvas {
            display: block;
            margin: 0 auto;
        }
        .button-container {
            text-align: center;
            margin: 20px;
        }
        .button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include Chart.js Zoom Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
</head>
<body>

<h2>FFT of Complete CSV File</h2>

<div class="button-container">
    <button class="button" onclick="saveChart()">Save Chart</button>
</div>

<canvas id="fftChart" width="800" height="400"></canvas>

<script>
    // Get the FFT results, frequencies, and column indices from PHP
    var fftResults = @json($fftResults);
    var frequencies = @json($frequencies);

    // Create the Chart.js plot with zoom plugin
    var ctx = document.getElementById('fftChart').getContext('2d');
    var datasets = [];

    // Prepare datasets for each column
    Object.keys(fftResults).forEach(function(columnIndex, idx) {
        datasets.push({
            label: 'Column ' + columnIndex,
            data: fftResults[columnIndex],
            borderColor: 'rgba(' + (75 + idx * 50) + ', 192, 192, 1)',
            borderWidth: 1,
            fill: false,
            pointRadius: 0,
            borderWidth: 2
        });
    });

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
                title: {
                    display: true,
                    text: 'FFT of Complete CSV File',
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
                }
            }
        }
    });

   // Function to save the chart as an image with white background
function saveChart() {
    // Create a temporary canvas to draw the chart on with a white background
    var tmpCanvas = document.createElement('canvas');
    tmpCanvas.width = fftChart.width;
    tmpCanvas.height = fftChart.height;
    var tmpCtx = tmpCanvas.getContext('2d');

    // Fill the canvas with white background
    tmpCtx.fillStyle = 'white';
    tmpCtx.fillRect(0, 0, tmpCanvas.width, tmpCanvas.height);

    // Draw the chart on the new canvas
    tmpCtx.drawImage(fftChart.canvas, 0, 0, fftChart.width, fftChart.height);

    // Create a link to download the image
    var link = document.createElement('a');
    link.href = tmpCanvas.toDataURL('image/png');
    link.download = 'fft_chart.png';
    link.click();
}

</script>

</body>
</html>
