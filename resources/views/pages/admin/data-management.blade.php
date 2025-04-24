@extends('layouts.admin')

@section('title', 'Data Management')

@section('content')
<style>
    .simulation-wrapper {
        background-color: rgba(228, 228, 228, 0.45);
        border-radius: 16px;
        padding: 36px 28px;
        max-width: 520px;
        margin: 40px auto;
        font-family: 'Poppins', sans-serif;
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
        color: #22577A; /* ✅ Theme color */
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
        transition: border 0.2s, box-shadow 0.2s;
        color: #000; /* ✅ Black text for filled fields */
    }

    .simulation-wrapper input::placeholder,
    .simulation-wrapper select::placeholder {
        color: rgba(0, 0, 0, 0.45); /* ✅ Soft gray placeholder */
        font-size: 12.5px;
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
        transition: background-color 0.4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        border-radius: 50%;
        transition: transform 0.4s;
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
        <select id="variation" style="color:rgba(0, 0, 0, 0.45);">
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
</div>

<script>
    function toggleSimulationStatus() {
        const toggle = document.getElementById('simulationToggle');
        const statusText = document.getElementById('status-text');
        if (toggle.checked) {
            statusText.textContent = 'Running';
            statusText.classList.remove('stopped');
            statusText.classList.add('running');
        } else {
            statusText.textContent = 'Stopped';
            statusText.classList.remove('running');
            statusText.classList.add('stopped');
        }
    }
</script>
@endsection
