@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    body {
        background: #ffffff;
        font-family: 'Poppins', sans-serif;
    }

    .header-bar h3 {
        font-size: 19px;
        font-weight: 600;
        color: #22577A;
    }

    .summary-card {
        background: rgba(228, 228, 228, 0.45);
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
    }

    .summary-icon {
        font-size: 20px;
        background: #22577A;
        color: #fff;
        padding: 10px;
        border-radius: 8px;
    }

    .summary-value {
        font-size: 16px;
        font-weight: 600;
        color: #22577A;
    }

    .summary-label {
        font-size: 12px;
        color: #3d4b52;
    }

    .text-primary {
        color: #22577A !important;
    }

    .status-section {
        background: rgba(228, 228, 228, 0.45);
        padding: 20px;
        border-radius: 12px;
        font-size: 14px;
    }

    .status-heading {
        font-size: 15px;
        font-weight: 600;
        color: #22577A;
        margin-bottom: 14px;
    }

    .status-row {
        color: #22577A;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .badge-box {
        min-width: 100px;
        font-weight: 500;
        font-size: 13px;
        padding: 6px 12px;
        border-radius: 6px;
        display: inline-block;
        text-align: center;
    }

    .btn-see-more {
        background-color: #22577A;
        padding: 5px 14px;
        font-size: 13px;
        border-radius: 8px;
        color: white;
    }

    .btn-see-more i {
        font-size: 15px;
    }
</style>

<div class="header-bar d-flex justify-content-between align-items-center mb-3">
    <h3>Dashboard</h3>
    <div class="text-muted mb-2" style="font-size: 13px;">
        Hello, {{ auth()->user()->full_name }} <i class="bi bi-person-circle ms-1"></i>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="summary-card">
            <div>
            <div class="summary-value">{{ $activeSensorsCount }}</div>
                <div class="summary-label">Active Sensors</div>
            </div>
            <div class="summary-icon">
                <i class="bi bi-broadcast-pin"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary-card">
            <div>
            <div class="summary-value">{{ $unreadCount }}</div>
            <div class="summary-label">Pending Alerts</div>
        </div>
            <div class="summary-icon bg-warning text-dark">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
            <div class="summary-card">
            <div>
                <div class="summary-value">{{ $simulationStatus }}</div>
                <div class="summary-label">Simulation Status</div>
            </div>
            <div class="summary-icon 
                {{ $simulationStatus === 'Running' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                <i class="bi bi-activity"></i>
            </div>
        </div>

    </div>
</div>





{{-- Live Alerts Section --}}
<div class="summary-card p-4" style="flex-direction: column; align-items: start;">
    <div class="d-flex justify-content-between align-items-center w-100 mb-3" style="border-bottom: 2px solid #22577A; padding-bottom: 8px;">
        <h5 class="text-primary fw-bold mb-0" style="font-size: 17px;">Live Alerts 🚨</h5>
        <small class="text-muted" style="font-size: 12px;">Last updated: {{ now()->format('h:i A') }}</small>
    </div>

    @if($alerts->isEmpty())
        <div class="w-100 text-center mt-3">
            <div class="summary-value">No Alerts</div>
            <div class="summary-label">All sensors normal ✅</div>
        </div>
    @else
        @foreach($alerts as $alert)
            <div class="d-flex justify-content-between align-items-center w-100 py-2 px-1 mb-1 alert-hover" style="border-bottom: 1px solid rgba(0,0,0,0.1);">
                <div>
                    <div class="summary-value" style="font-size: 14px;">{{ $alert->sensor_id }}</div>
                    <div class="summary-label" style="font-size: 12px;">{{ $alert->pollutant_type }} — {{ $alert->aqi_value }}</div>
                </div>
                <div class="summary-icon bg-danger text-white" style="font-size: 16px;">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        @endforeach
    @endif
</div>

{{-- Today's Air Quality Summary --}}
<div class="summary-card p-4 mt-4" style="flex-direction: column; align-items: start;">
    <div class="d-flex justify-content-between align-items-center w-100 mb-3" style="border-bottom: 2px solid #22577A; padding-bottom: 8px;">
        <h5 class="text-primary fw-bold mb-0" style="font-size: 17px;">Today's Air Quality Summary 🌿</h5>
    </div>

    <div class="w-100">
        <div class="d-flex justify-content-between mb-3">
            <div class="summary-label">Average AQI Today</div>
            <div class="summary-value">{{ $averageAQI }}</div>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <div class="summary-label">Overall Condition</div>
            <div class="summary-value 
                {{ $averageAQI <= 50 ? 'text-success' : ($averageAQI <= 100 ? 'text-warning' : 'text-danger') }}">
                {{ getAQICondition($averageAQI) }}
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <div class="summary-label">Recommendation</div>
            <div class="summary-value" style="font-size: 12px;">
                {{ getAQIAdvice($averageAQI) }}
            </div>
        </div>
    </div>
</div>
@php
function getAQICondition($aqi) {
    if ($aqi <= 50) return 'Good';
    if ($aqi <= 100) return 'Moderate';
    if ($aqi <= 150) return 'Unhealthy for Sensitive Groups';
    if ($aqi <= 200) return 'Unhealthy';
    if ($aqi <= 300) return 'Very Unhealthy';
    return 'Hazardous';
}

function getAQIAdvice($aqi) {
    if ($aqi <= 50) return 'Air quality is excellent 🌿';
    if ($aqi <= 100) return 'Acceptable, but sensitive groups should limit outdoors';
    if ($aqi <= 150) return 'Reduce prolonged outdoor exertion';
    if ($aqi <= 200) return 'Avoid outdoor activities if possible';
    if ($aqi <= 300) return 'Health warnings of emergency conditions';
    return 'Everyone should avoid outdoor activities!';
}
@endphp





@endsection
