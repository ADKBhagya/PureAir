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
    .switch input {opacity: 0; width: 0; height: 0;}
    .slider {
        position: absolute; cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #ccc; border-radius: 34px; transition: 0.4s;
    }
    .slider:before {
        content: ""; position: absolute; height: 18px; width: 18px;
        left: 3px; bottom: 3px;
        background-color: white; border-radius: 50%; transition: 0.4s;
    }
    input:checked + .slider { background-color: #22577A; }
    input:checked + .slider:before { transform: translateX(24px); }
    #status-text.running { color: #198754; font-weight: 600; }
    #status-text.stopped { color: #dc3545; font-weight: 600; }

    .filter-bar {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 20px 0;
        flex-wrap: wrap;
    }
    .filter-bar select, .filter-bar input[type="date"] {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        border: 1.5px solid #ccc;
        color: #737577;
    }
    .filter-bar select:focus, .filter-bar input[type="date"]:focus {
        border-color: #80bdff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    .aqi-card {
        background-color: rgba(228, 228, 228, 0.45);
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        transition: 0.3s;
    }
    .aqi-card:hover { box-shadow: 0 6px 12px rgba(34, 87, 122, 0.2); }
    .aqi-badge {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 600;
        color: #ffffff;
        min-width: 40px;
    }
    .aqi-status { font-size: 12px; font-weight: 500; margin-top: 6px; }
    .updated-text { font-size: 12px; color: #555; margin-top: 6px; }
    .empty-state { text-align: center; font-size: 14px; margin-top: 20px; color: #888; }
</style>

<div class="header-bar d-flex justify-content-between align-items-center mb-3">
    <h3 style="font-size: 18px;">Data Management</h3>
    <div class="text-muted mb-2" style="font-size: 13px;">
        Hello, {{ auth()->user()->full_name }}! <i class="bi bi-person-circle ms-1"></i>
    </div>
</div>

<div class="simulation-wrapper">
    <h4><i class="bi bi-gear-fill me-2"></i>Simulation Settings</h4>
    <label for="frequency">Frequency of Data Generation</label>
    <input type="text" id="frequency" placeholder="e.g., every 5 minutes" />
    <label for="baseline">Baseline AQI Level</label>
    <input type="number" id="baseline" placeholder="e.g., 70" />
    <label for="variation">AQI Variation Pattern</label>
    <select id="variation">
        <option disabled selected>Select a pattern</option>
        <option value="random">Random</option>
        <option value="increasing">Increasing</option>
        <option value="fluctuating">Fluctuating</option>
    </select>
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

<div class="filter-bar">
    <select id="sensorFilter" onchange="applyFilters()">
        <option value="all">All Sensors</option>
    </select>
    <input type="date" id="dateFilter" onchange="applyFilters()">
</div>

<div id="readingsBody" class="card-grid"></div>
<div id="emptyState" class="empty-state" style="display:none;">No readings found.</div>

<div class="modal fade" id="aqiHistoryModal" tabindex="-1" aria-labelledby="aqiHistoryLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content p-4">
      <h5 id="aqiHistoryLabel" class="mb-3" style="color: #22577A;">AQI History - <span id="modalLocation"></span></h5>
      <canvas id="aqiHistoryChart" height="120"></canvas>
      <div class="text-end mt-3">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
function toggleSimulationStatus() {
    const toggle = document.getElementById('simulationToggle');
    const statusText = document.getElementById('status-text');
    fetch("{{ route('simulation.settings.toggle') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    }).then(res => res.json()).then(data => {
        statusText.textContent = data.is_running ? 'Running' : 'Stopped';
        statusText.classList.toggle('running', data.is_running);
        statusText.classList.toggle('stopped', !data.is_running);
    });
}

function saveSimulationSettings() {
    const btn = event.target;
    btn.disabled = true;
    const frequency = document.getElementById('frequency').value;
    const baseline = document.getElementById('baseline').value;
    const variation = document.getElementById('variation').value;
    fetch("{{ route('simulation.settings.store') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ frequency, baseline, variation })
    }).then(res => res.json())
    .then(data => alert(data.message || 'Settings saved!'))
    .catch(() => alert('Error saving settings'))
    .finally(() => btn.disabled = false);
}

function getConditionColor(aqi) {
    if (aqi <= 50) return '#0A6304';
    if (aqi <= 100) return '#FFD70E';
    if (aqi <= 150) return '#FF7700';
    if (aqi <= 200) return '#980000';
    if (aqi <= 300) return '#681E83';
    return '#551515';
}

function getAqiStatus(aqi) {
    if (aqi <= 50) return 'Good';
    if (aqi <= 100) return 'Moderate';
    if (aqi <= 150) return 'Unhealthy for Sensitive Groups';
    if (aqi <= 200) return 'Unhealthy';
    if (aqi <= 300) return 'Very Unhealthy';
    return 'Hazardous';
}

function loadReadings(sensor = 'all', date = null) {
    fetch("/admin/data-readings").then(res => res.json()).then(data => {
        const container = document.getElementById('readingsBody');
        const emptyState = document.getElementById('emptyState');
        container.innerHTML = '';

        const filtered = data.filter(r => (sensor === 'all' || r.sensor_id === sensor) && (!date || r.created_at.startsWith(date)));

        filtered.forEach(r => {
            const color = getConditionColor(r.aqi);
            const status = getAqiStatus(r.aqi);

            const activeBadge = (r.status === 'active')
                ? `<span style="color:  rgba(228, 228, 228, 0.45); font-weight:600; font-size:13px;"> </span>`
                : `<span style="color: rgba(228, 228, 228, 0.45); font-weight:600; font-size:13px;"></span>`;

            container.innerHTML += `
                <div class="aqi-card" onclick="openHistoryModal('${r.sensor_id}', '${r.location}')">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                        <h5 style="color:#22577A; font-weight:600; font-size:15px;">${r.location ?? r.sensor_id}</h5>
                        ${activeBadge}
                    </div>
                    <div style="margin-bottom: 8px;">
                        <div class="aqi-badge" style="background:${color}; font-size:16px;">${r.aqi}</div>
                    </div>
                    <div class="aqi-status" style="font-size:14px; font-weight:600;">${status}</div>
                    <div class="updated-text" style="font-size:12px; color:#737577; margin-top:8px;">
                        Last updated: ${new Date(r.created_at).toLocaleString()}
                    </div>
                    
                </div>`;
        });

        emptyState.style.display = filtered.length === 0 ? 'block' : 'none';

        const select = document.getElementById('sensorFilter');
        if (select.options.length <= 1) {
            [...new Set(data.map(r => r.sensor_id))].forEach(sensorId => {
                const opt = document.createElement('option');
                opt.value = sensorId;
                opt.textContent = sensorId;
                select.appendChild(opt);
            });
        }
    });
}


function openHistoryModal(sensorId, location) {
    document.getElementById('modalLocation').textContent = location;

    // Fetch readings for this sensor (last 24 hours)
    axios.get(`/admin/sensor-history/${sensorId}`)
        .then(response => {
            const readings = response.data;

            const ctx = document.getElementById('aqiHistoryChart').getContext('2d');
            if (window.historyChart) {
                window.historyChart.destroy();
            }

            window.historyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: readings.map(r => new Date(r.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})),
                    datasets: [{
                        label: 'AQI',
                        data: readings.map(r => r.aqi),
                        borderColor: '#22577A',
                        backgroundColor: 'rgba(34, 87, 122, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 2,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                        }
                    }
                }
            });

            var modal = new bootstrap.Modal(document.getElementById('aqiHistoryModal'));
            modal.show();
        })
        .catch(error => {
            console.error(error);
            alert('Failed to load sensor history.');
        });
}


function applyFilters() {
    loadReadings(document.getElementById('sensorFilter').value, document.getElementById('dateFilter').value);
}

window.onload = () => {
    applyFilters();
    setInterval(applyFilters, 60000);
};
</script>
@endsection
