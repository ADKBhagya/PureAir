@extends('layouts.admin')

@section('title', 'AQI Status')

@section('content')
<style>
    .header-bar h3 {
        font-weight: 300;
        color: #22577A;
        font-size: 21px;
    }

    .back-btn {
        color: #22577A !important;
        border-color: #22577A !important;
    }

    .back-btn:hover {
        background-color: #22577A !important;
        color: #fff !important;
    }

    .aqi-header-row {
        font-size: 18px;
        margin-bottom: 20px;
        font-weight: 600;
        color: #22577A;
    }
</style>

<div class="header-bar d-flex justify-content-between align-items-center mb-4">
    <h3>AQI Status - All Locations</h3>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-sm back-btn">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div style="background-color: rgba(228, 228, 228, 0.45); border-radius: 16px; padding: 30px;">
    <div class="row aqi-header-row">
        <div class="col-md-3">City</div>
        <div class="col-md-3">Condition</div>
        <div class="col-md-3">AQI</div>
        <div class="col-md-3">Last Updated</div>
    </div>

    <div class="row align-items-center mb-4" style="color: #22577A; font-size: 17px;">
        <div class="col-md-3">Homagama</div>
        <div class="col-md-3">
            <span class="d-inline-block text-white text-center px-4 py-2 rounded" style="background-color: #137f1f; font-weight: 500; min-width: 130px;">Good</span>
        </div>
        <div class="col-md-3">37</div>
        <div class="col-md-3">2 mins ago</div>
    </div>

    <div class="row align-items-center" style="color: #22577A; font-size: 17px;">
        <div class="col-md-3">Moratuwa</div>
        <div class="col-md-3">
            <span class="d-inline-block text-dark text-center px-4 py-2 rounded" style="background-color: #FFD400; font-weight: 500; min-width: 130px;">Moderate</span>
        </div>
        <div class="col-md-3">62</div>
        <div class="col-md-3">1 mins ago</div>
    </div>
</div>
@endsection
