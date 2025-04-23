@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    body {
        background: #ffffff;
        font-family: 'Poppins', sans-serif;
    }

    .summary-card {
        background-color: rgba(228, 228, 228, 0.45);
        border-radius: 14px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-3px);
    }

    .summary-icon {
        font-size: 22px;
        background: #22577A;
        color: white;
        padding: 12px;
        border-radius: 10px;
    }

    .summary-value {
        font-size: 18px;
        font-weight: 600;
        color: #22577A;
    }

    .summary-label {
        margin-top: 2px;
        font-size: 12px;
        color: #3d4b52;
    }

    .header-bar h3 {
        font-weight: 600;
        color: #22577A;
        font-size: 18px;
    }

    .text-primary {
        color: #22577A !important;
    }

    .status-box {
        background-color: rgba(228, 228, 228, 0.45);
        padding: 20px;
        border-radius: 14px;
    }

    .badge {
        border-radius: 8px;
    }
</style>

<div class="header-bar d-flex justify-content-between align-items-center mb-3">
    <h3>Dashboard</h3>
    <div class="text-muted" style="font-size: 13px;">Hello, User! <i class="bi bi-person-circle ms-2"></i></div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="summary-card">
            <div>
                <div class="summary-value">12</div>
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
                <div class="summary-value">5</div>
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
                <div class="summary-value">Running</div>
                <div class="summary-label">Simulation Status</div>
            </div>
            <div class="summary-icon bg-success text-white">
                <i class="bi bi-activity"></i>
            </div>
        </div>
    </div>
</div>

{{-- AQI Status Section --}}
<div class="d-flex justify-content-between align-items-center mb-3 mt-4">
    <h5 class="text-primary fw-bold mb-0" style="font-size: 16px;">Status</h5>
    <a href="{{ route('admin.aqi.full') }}" 
       class="btn btn-sm text-white d-flex align-items-center gap-1"
       style="background-color: #22577A; padding: 6px 16px; border-radius: 8px;"
       title="View full AQI breakdown">
       See more <i class="bi bi-arrow-right text-white fs-5"></i>
    </a>
</div>

<div style="background-color: rgba(228, 228, 228, 0.45); border-radius: 14px; padding: 22px;">
    <div class="row text-primary fw-semibold" style="font-size: 15px; margin-bottom: 15px;">
        <div class="col-md-4">City</div>
        <div class="col-md-4">Condition</div>
        <div class="col-md-4">AQI</div>
    </div>
    <div class="row align-items-center mb-3" style="color: #22577A; font-size: 14px;">
        <div class="col-md-4">Homagama</div>
        <div class="col-md-4">
            <span class="d-inline-block text-white text-center px-3 py-1 rounded" style="background-color: #137f1f; font-weight: 500; min-width: 110px;">Good</span>
        </div>
        <div class="col-md-4">37</div>
    </div>
    <div class="row align-items-center" style="color: #22577A; font-size: 14px;">
        <div class="col-md-4">Moratuwa</div>
        <div class="col-md-4">
            <span class="d-inline-block text-dark text-center px-3 py-1 rounded" style="background-color: #FFD400; font-weight: 500; min-width: 110px;">Moderate</span>
        </div>
        <div class="col-md-4">62</div>
    </div>
</div>
@endsection
