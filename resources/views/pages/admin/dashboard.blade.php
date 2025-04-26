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
    <h5 class="text-primary fw-bold mb-0" style="font-size: 15px;">Status</h5>
    <a href="{{ route('admin.aqi.full') }}" class="btn btn-see-more d-flex align-items-center gap-1" title="View full AQI breakdown">
        See more <i class="bi bi-arrow-right"></i>
    </a>
</div>

<div class="status-section">
    <div class="row fw-semibold status-heading">
        <div class="col-md-4">City</div>
        <div class="col-md-4">Condition</div>
        <div class="col-md-4">AQI</div>
    </div>
    <div class="row align-items-center status-row">
        <div class="col-md-4">Homagama</div>
        <div class="col-md-4">
            <span class="badge-box text-white" style="background-color: #137f1f;">Good</span>
        </div>
        <div class="col-md-4">37</div>
    </div>
    <div class="row align-items-center status-row">
        <div class="col-md-4">Moratuwa</div>
        <div class="col-md-4">
            <span class="badge-box text-dark" style="background-color: #FFD400;">Moderate</span>
        </div>
        <div class="col-md-4">62</div>
    </div>
</div>
@endsection
