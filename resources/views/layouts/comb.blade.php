<!-- resources/views/layouts/comb.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time and Frequency Domain Plots</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f7f7f7;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .graph-container {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        canvas {
            margin-top: 20px;
        }
        .chart-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .chart-container canvas {
            margin: 0 auto;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-right: 10px;
        }
        .value-display {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: white;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .value-display-right {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: white;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    @yield('content')
    @yield('scripts')
</body>
</html>
