@extends('layouts.admin')

@section('title', 'Data Management')

@section('content')
<style>
    .simulation-wrapper {
        background-color: #f5f5f5;
        border-radius: 18px;
        padding: 40px;
        max-width: 600px;
        margin: 40px auto;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .simulation-wrapper h4 {
        font-size: 20px;
        font-weight: 600;
        color: #22577A;
        text-align: center;
        margin-bottom: 30px;
    }

    .simulation-wrapper label {
        font-weight: 500;
        color: #22577A;
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .simulation-wrapper input,
    .simulation-wrapper select {
        width: 100%;
        padding: 10px 14px;
        font-size: 15px;
        border: 2px solid #22577A;
        border-radius: 8px;
        margin-bottom: 24px;
        background-color: white;
        color: #22577A;
    }

    .toggle-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 500;
        font-size: 14px;
        color: #22577A;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 46px;
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
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #22577A;
    }

    input:checked + .slider:before {
        transform: translateX(22px);
    }
</style>

<div class="simulation-wrapper">
    <h4>Configure Simulations</h4>
    
    <div>
        <label for="frequency">Frequency of data generation:</label>
        <input type="text" id="frequency" placeholder="e.g., every 5 minutes">
    </div>

    <div>
        <label for="baseline">Baseline AQI Levels:</label>
        <input type="number" id="baseline" placeholder="e.g., 70">
    </div>

    <div>
        <label for="variation">Variation Pattern:</label>
        <select id="variation">
            <option selected disabled>Select pattern</option>
            <option>Random</option>
            <option>Increasing</option>
            <option>Fluctuating</option>
        </select>
    </div>

    <div class="toggle-container">
        <span>Simulation status: <strong id="status-text">stopped</strong></span>
        <label class="switch">
            <input type="checkbox" id="simulationToggle" onchange="toggleSimulationStatus()">
            <span class="slider"></span>
        </label>
    </div>
</div>

<script>
    function toggleSimulationStatus() {
        const toggle = document.getElementById('simulationToggle');
        const status = document.getElementById('status-text');
        status.innerText = toggle.checked ? 'running' : 'stopped';
    }
</script>
@endsection