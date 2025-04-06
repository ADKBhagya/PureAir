@extends('layouts.user')

@section('title', 'Historical Data')

@section('content')
<style>
    .primary-blue { color: #22577A; }
    .accent-orange { color: #FF7700; }
    .bg-card {
        background-color: rgba(228, 228, 228, 0.45);
        border-radius: 10px;
    }

    .sensor-select-box {
        background-color: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .filter-buttons button {
        border: none;
        padding: 8px 20px;
        margin: 0 5px;
        border-radius: 8px;
        background-color: #22577A;
        color: white;
        font-family: 'Poppins';
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .filter-buttons button.active,
    .filter-buttons button:hover {
        background-color: #FF7700;
    }

    .chart-card {
        background-color: rgba(228, 228, 228, 0.45);
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .section-header {
        font-size: 22px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        text-align: center;
        margin-bottom: 20px;
        color: #22577A;
    }
</style>

<div class="container mt-5 mb-5">
    <div class="text-center mb-4">
        <h3 class="fw-bold primary-blue">Historical AQI Trends</h3>
        <p class="text-muted">Visualize past air quality data across different time periods</p>
    </div>

    <!-- Sensor Location Selector -->
    <div class="sensor-select-box">
        <div class="row align-items-center">
            <div class="col-md-6 mb-2">
                <label for="sensorLocation" class="form-label primary-blue">Select Sensor Location</label>
                <select id="locationSelect" class="form-select">
                    <option value="">-- Choose a city --</option>
                    <option value="homagama">Homagama</option>
                    <option value="moratuwa">Moratuwa</option>
                    <option value="colombo">Colombo Central</option>
                </select>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0 filter-buttons">
                <button class="active" data-range="day">Last Day</button>
                <button data-range="week">Last Week</button>
                <button data-range="month">Last Month</button>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="chart-card mt-4">
        <h5 class="section-header">AQI Overview (Daily)</h5>
        <canvas id="aqiChart" height="120"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('aqiChart').getContext('2d');

    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['12AM', '4AM', '8AM', '12PM', '4PM', '8PM'],
            datasets: [{
                label: 'AQI',
                data: [55, 60, 70, 80, 76, 65],
                borderColor: '#FF7700',
                backgroundColor: 'rgba(255, 119, 0, 0.2)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 200,
                    ticks: {
                        color: '#22577A',
                        font: { family: 'Poppins' }
                    }
                },
                x: {
                    ticks: {
                        color: '#22577A',
                        font: { family: 'Poppins' }
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#22577A',
                        font: { family: 'Poppins' }
                    }
                }
            }
        }
    });

    // Change chart range logic (mock data for now)
    document.querySelectorAll('.filter-buttons button').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.filter-buttons button').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const range = this.dataset.range;
            if (range === 'week') {
                chart.data.labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                chart.data.datasets[0].data = [60, 65, 70, 66, 80, 75, 68];
                chart.options.plugins.title = { display: true, text: 'AQI Weekly Trend' };
            } else if (range === 'month') {
                chart.data.labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                chart.data.datasets[0].data = [72, 85, 78, 80];
            } else {
                chart.data.labels = ['12AM', '4AM', '8AM', '12PM', '4PM', '8PM'];
                chart.data.datasets[0].data = [55, 60, 70, 80, 76, 65];
            }
            chart.update();
        });
    });
});
</script>
@endsection
