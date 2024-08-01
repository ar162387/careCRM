<!-- resources/views/layouts/fft.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FFT of Selected Columns')</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
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
            background-color: white;
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
        .info-container {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
    @yield('head')
</head>
<body>
    @yield('content')
    @yield('scripts')
</body>
</html>
