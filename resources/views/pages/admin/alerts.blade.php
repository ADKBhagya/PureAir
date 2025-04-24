@extends('layouts.admin')

@section('title', 'Alert Configuration')

@section('content')
<style>
    .alert-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        color: #22577A;
    }

    .alert-header h3 {
        font-weight: 600;
        font-size: 18px;
    }

    .add-rule-btn {
        background-color: #22577A;
        color: white;
        padding: 6px 16px;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        font-size: 14px;
    }

    .add-rule-btn:hover {
        background-color: #1d4f6d;
    }

    .rule-table {
        background-color: rgba(228, 228, 228, 0.45);
        padding: 24px;
        border-radius: 14px;
        color: #22577A;
        font-size: 14px;
    }

    .rule-table h5 {
        font-size: 16px;
    }

    .modal-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.4);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-box {
        background: #fff;
        padding: 24px;
        border-radius: 12px;
        width: 360px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.2);
        position: relative;
    }

    .modal-close {
        position: absolute;
        top: 10px;
        right: 14px;
        font-size: 20px;
        color: #FF7700;
        font-weight: bold;
        cursor: pointer;
    }

    

    .form-label {
        font-size: 13px;
        font-weight: 500;
        color: #22577A;
    }

    .form-select,
    .form-control {
        font-size: 13px;
        padding: 8px;
        border-radius: 6px;
        border: 2px solid #ccc;
        margin-bottom: 12px;
        color: #000; /* ✅ Black text for filled input */
    }

    .form-select::placeholder,
    .form-control::placeholder {
        color: rgba(0, 0, 0, 0.45); /* ✅ Soft gray for placeholders */
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .btn-submit {
        background-color: #22577A;
        color: white;
        padding: 9px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        width: 100%;
    }

    .preview-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 10px;
        font-size: 12px;
        margin-top: 5px;
        color: white;
    }

    .toast-message {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #198754;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-size: 14px;
        display: none;
        z-index: 2000;
    }
</style>

<div class="alert-header">
    <h3>Alert Configuration</h3>
    <div class="text-end">
        <div class="text-muted mb-2" style="font-size: 13px;">
            Hello, {{ auth()->user()->full_name }}! <i class="bi bi-person-circle ms-1"></i>
        </div>
        <button class="add-rule-btn" onclick="showRuleModal()">
            <i class="bi bi-plus-circle me-1"></i> Add Rule
        </button>
    </div>
</div>

<!-- Alert Rules Table -->
<div class="rule-table">
    <h5 class="mb-3 fw-semibold">Configured Alert Rules</h5>
    <p class="text-muted">⚠  No alert rules configured yet.</p>
</div>

<!-- Modal -->
<div id="ruleModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-close" onclick="hideRuleModal()">&times;</div>
        <h5 class="text-center mb-3" style="font-weight: 600; color: #22577A; font-size: 18px; ">New Alert Rule</h5>
        <form onsubmit="submitRule(event)">
            <label class="form-label">Pollutant Type</label>
            <select class="form-select" required>
                <option value="AQI">AQI</option>
                <option value="PM2.5">PM2.5</option>
                <option value="PM10">PM10</option>
                <option value="CO2">CO2</option>
                <option value="O3">O3</option>
            </select>

            <label class="form-label">Threshold (µg/m³)</label>
            <input type="number" class="form-control" id="thresholdInput" placeholder="e.g., 100" required />
            <span id="previewBadge" class="preview-badge" style="display:none;">Good</span>

            <label class="form-label">Check Frequency</label>
            <select class="form-select" required>
                <option value="15min">Every 15 min</option>
                <option value="30min">Every 30 min</option>
                <option value="1hr">Hourly</option>
            </select>

            <label class="form-label">Alert Type</label>
            <select class="form-select mb-3" required>
                <option value="dashboard">Dashboard Only</option>
                <option value="email">Email + Dashboard</option>
            </select>

            <button type="submit" class="btn-submit">Save</button>
        </form>
    </div>
</div>

<!-- Toast -->
<div class="toast-message" id="toast">✅ Rule Added Successfully</div>

<script>
    function showRuleModal() {
        document.getElementById('ruleModal').style.display = 'flex';
    }

    function hideRuleModal() {
        document.getElementById('ruleModal').style.display = 'none';
    }

    function submitRule(e) {
        e.preventDefault();
        hideRuleModal();

        // Show toast
        const toast = document.getElementById('toast');
        toast.style.display = 'block';
        setTimeout(() => toast.style.display = 'none', 3000);
    }

    // AQI Color Preview
    document.getElementById('thresholdInput').addEventListener('input', function () {
        const value = parseInt(this.value);
        const badge = document.getElementById('previewBadge');
        badge.style.display = 'inline-block';

        if (value <= 50) badge.style.background = '#0A6304', badge.innerText = 'Good';
        else if (value <= 100) badge.style.background = '#FFD70E', badge.innerText = 'Moderate';
        else if (value <= 150) badge.style.background = '#FF7700', badge.innerText = 'Unhealthy (SG)';
        else if (value <= 200) badge.style.background = '#980000', badge.innerText = 'Unhealthy';
        else if (value <= 300) badge.style.background = '#681E83', badge.innerText = 'Very Unhealthy';
        else badge.style.background = '#551515', badge.innerText = 'Hazardous';
    });
</script>
@endsection
