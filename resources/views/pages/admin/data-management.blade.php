@extends('layouts.admin')

@section('title', 'Data Management')

@section('content')
<style>
    body {
        background: #ffffff;
        font-family: 'Poppins', sans-serif;
    }

    .simulation-wrapper {
        background-color: rgba(228, 228, 228, 0.45);
        border-radius: 16px;
        padding: 20px 28px;
        max-width: 520px;
        margin: 30px auto;
    }

    .simulation-wrapper h4 {
        font-size: 20px;
        font-weight: 600;
        color: #22577A;
        text-align: center;
        margin-bottom: 28px;
    }

    .simulation-wrapper label {
        font-size: 13px;
        font-weight: 500;
        color: #22577A;
        margin-bottom: 6px;
        display: block;
    }

    .simulation-wrapper input,
    .simulation-wrapper select {
        font-size: 13px;
        border: 2px solid #ccc;
        border-radius: 8px;
        padding: 9px 12px;
        width: 100%;
        margin-bottom: 20px;
        color: #000;
    }

    .simulation-wrapper input:focus,
    .simulation-wrapper select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .toggle-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        font-weight: 500;
        color: #22577A;
        margin-top: 6px;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0;
        right: 0; bottom: 0;
        background-color: #ccc;
        border-radius: 34px;
        transition: 0.4s;
    }

    .slider:before {
        content: "";
        position: absolute;
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        border-radius: 50%;
        transition: 0.4s;
    }

    input:checked + .slider {
        background-color: #22577A;
    }

    input:checked + .slider:before {
        transform: translateX(24px);
    }

    #status-text.running {
        color: #198754;
        font-weight: 600;
    }

    #status-text.stopped {
        color: #dc3545;
        font-weight: 600;
    }

    .filter-bar {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
        align-items: center;
    }

    .filter-bar select,
    .filter-bar input[type="date"] {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        border: 1.5px solid #ccc;
    }

    .filter-bar input:focus,
    .filter-bar select:focus {
        border-color: #80bdff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }

    .table-section {
        background-color: rgba(228, 228, 228, 0.45);
        border-radius: 16px;
        padding: 24px;
        margin-top: 40px;
    }

    .table-section h4 {
        color: #22577A;
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 16px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    table th, table td {
        padding: 12px 20px;
        text-align: left;
    }

    table thead {
        color: #22577A;
        border-bottom: 1px solid #ccc;
    }

    table tbody {
        color: #22577A;
    }

</style>

<div class="header-bar d-flex justify-content-between align-items-center mb-3">
    <h3 style="font-size: 18px;">Data Management</h3>
    <div class="text-muted mb-2" style="font-size: 13px;">
        Hello, {{ auth()->user()->full_name }}! <i class="bi bi-person-circle ms-1"></i>
    </div>
</div>

<div class="simulation-wrapper">
    <h4><i class="bi bi-gear-fill me-2"></i>Simulation Settings</h4>
    
    <div>
        <label for="frequency">Frequency of Data Generation</label>
        <input type="text" id="frequency" placeholder="e.g., every 5 minutes" />
    </div>

    <div>
        <label for="baseline">Baseline AQI Level</label>
        <input type="number" id="baseline" placeholder="e.g., 70" />
    </div>

    <div>
        <label for="variation">AQI Variation Pattern</label>
        <select id="variation">
            <option disabled selected>Select a pattern</option>
            <option value="random">Random</option>
            <option value="increasing">Increasing</option>
            <option value="fluctuating">Fluctuating</option>
        </select>
    </div>

    <div class="toggle-container">
        <span>Simulation Status: <span id="status-text" class="stopped">Stopped</span></span>
        <label class="switch">
            <input type="checkbox" id="simulationToggle" onchange="toggleSimulationStatus()">
            <span class="slider"></span>
        </label>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <button onclick="saveSimulationSettings()" class="btn btn-primary" style="background-color:#22577A; border:none; padding:8px 18px; border-radius:6px; font-size:13px;">
            Save Settings
        </button>
    </div>
</div>

<div class="table-section">
    <div class="filter-bar">
        <h4 style="font-size: 16px;">Recent AQI Readings</h4>
        <div style="display: flex; gap: 10px;">
            <select id="sensorFilter" onchange="applyFilters()">
                <option value="all">All Sensors</option>
            </select>
            <input type="date" id="dateFilter" onchange="applyFilters()">
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sensor ID</th>
                <th>AQI</th>
                <th>Recorded At</th>
            </tr>
        </thead>
        <tbody id="readingsBody">
            <!-- JS injects rows -->
        </tbody>
    </table>
</div>

<script>
    function toggleSimulationStatus() {
        const toggle = document.getElementById('simulationToggle');
        const statusText = document.getElementById('status-text');

        fetch("{{ route('simulation.settings.toggle') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        })
        .then(response => response.json())
        .then(data => {
            statusText.textContent = data.is_running ? 'Running' : 'Stopped';
            statusText.classList.toggle('running', data.is_running);
            statusText.classList.toggle('stopped', !data.is_running);
        });
    }

    function saveSimulationSettings() {
        const frequency = document.getElementById('frequency').value;
        const baseline = document.getElementById('baseline').value;
        const variation = document.getElementById('variation').value;

        fetch("{{ route('simulation.settings.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ frequency, baseline, variation })
        })
        .then(res => res.json())
        .then(data => alert(data.message || 'Settings saved!'))
        .catch(() => alert('Error saving settings'));
    }

    function getConditionColor(aqi) {
        if (aqi <= 50) return '#0A6304';
        if (aqi <= 100) return '#FFD70E';
        if (aqi <= 150) return '#FF7700';
        if (aqi <= 200) return '#980000';
        if (aqi <= 300) return '#681E83';
        return '#551515';
    }

    function loadReadings(sensor = 'all', date = null) {
        fetch("/admin/data-readings")
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('readingsBody');
                tbody.innerHTML = '';

                const filtered = data.filter(r => {
                    const matchSensor = sensor === 'all' || r.sensor_id === sensor;
                    const matchDate = !date || r.created_at.startsWith(date);
                    return matchSensor && matchDate;
                });

                filtered.forEach(reading => {
                    const color = getConditionColor(reading.aqi);
                    tbody.innerHTML += `
                        <tr>
                            <td style="padding: 10px;">${reading.sensor_id}</td>
                            <td style="padding: 10px;">
                                <span style="background:${color}; color:white; padding:6px 12px; border-radius:20px; font-size:13px; display:inline-block; text-align:center;">
                                    ${reading.aqi}
                                </span>
                            </td>
                            <td style="padding: 10px;">${new Date(reading.created_at).toLocaleString()}</td>
                        </tr>
                    `;
                });

                const select = document.getElementById('sensorFilter');
                if (select.options.length <= 1) {
                    const sensors = [...new Set(data.map(r => r.sensor_id))];
                    sensors.forEach(sensorId => {
                        const opt = document.createElement('option');
                        opt.value = sensorId;
                        opt.textContent = sensorId;
                        select.appendChild(opt);
                    });
                }
            });
    }

    function applyFilters() {
        const sensor = document.getElementById('sensorFilter').value;
        const date = document.getElementById('dateFilter').value;
        loadReadings(sensor, date);
    }

    window.onload = () => {
        applyFilters();
        setInterval(applyFilters, 60000);
    };
</script>
@endsection
