@extends('layouts.admin')

@section('title', 'AQI Status')

@section('content')
<style>
    .header-bar h3 {
        font-weight: 500;
        color: #22577A;
        font-size: 18px;
    }

    .back-btn {
        color: #22577A !important;
        border-color: #22577A !important;
        font-size: 13px;
        padding: 6px 12px;
        border-radius: 8px;
    }

    .back-btn:hover {
        background-color: #22577A !important;
        color: #fff !important;
    }

    .aqi-header-row {
        font-size: 15px;
        margin-bottom: 16px;
        font-weight: 600;
        color: #22577A;
    }

    .aqi-data-row {
        font-size: 14px;
        color: #22577A;
        margin-bottom: 16px;
    }

    .aqi-box {
        font-weight: 500;
        padding: 6px 14px;
        border-radius: 10px;
        display: inline-block;
        min-width: 110px;
        text-align: center;
    }

    .card-wrapper {
        background-color: rgba(228, 228, 228, 0.45);
        border-radius: 14px;
        padding: 24px;
    }
</style>

<div class="header-bar d-flex justify-content-between align-items-center mb-3">
    <h3>AQI Status - All Locations</h3>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-sm back-btn">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="card-wrapper">
    <div class="row aqi-header-row">
        <div class="col-md-3">City</div>
        <div class="col-md-3">Condition</div>
        <div class="col-md-3">AQI</div>
        <div class="col-md-3">Last Updated</div>
    </div>

    <div class="row align-items-center aqi-data-row">
        <div class="col-md-3">Homagama</div>
        <div class="col-md-3">
            <span class="aqi-box text-white" style="background-color: #137f1f;">Good</span>
        </div>
        <div class="col-md-3">37</div>
        <div class="col-md-3">2 mins ago</div>
    </div>

    <div class="row align-items-center aqi-data-row">
        <div class="col-md-3">Moratuwa</div>
        <div class="col-md-3">
            <span class="aqi-box text-dark" style="background-color: #FFD400;">Moderate</span>
        </div>
        <div class="col-md-3">62</div>
        <div class="col-md-3">1 min ago</div>
    </div>
</div>
@endsection
