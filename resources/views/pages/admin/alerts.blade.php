@extends('layouts.admin')

@section('title', 'Alert Configuration')

@section('content')
<style>
    body { background: #ffffff; font-family: 'Poppins', sans-serif; }

    .alert-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 24px; color: #22577A;
    }

    .add-rule-btn {
        background-color: #22577A; color: white; padding: 6px 16px;
        border: none; border-radius: 6px; font-weight: 500; font-size: 14px;
    }

    .rule-table {
        background-color: rgba(228, 228, 228, 0.45);
        padding: 24px;
        border-radius: 14px;
        color: #22577A;
        font-size: 14px;
    }

    .card {
        border: none;
        border-radius: 14px;
        background-color: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        transition: 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 8px 18px rgba(0,0,0,0.08);
    }

    .toast-message {
        position: fixed; bottom: 20px; right: 20px;
        background: #198754; color: white;
        padding: 12px 20px; border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-size: 14px; display: none; z-index: 2000;
    }

    .modal-overlay {
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.4);
        display: none; justify-content: center; align-items: center;
        z-index: 1000;
    }

    .modal-box {
        background: #fff; padding: 24px;
        border-radius: 12px; width: 360px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.2);
        position: relative;
    }

    .modal-close {
        position: absolute; top: 10px; right: 14px;
        font-size: 20px; color: #22577A; font-weight: bold;
        cursor: pointer;
    }

    .form-label { font-size: 13px; font-weight: 500; color: #22577A; }
    .form-control, .form-select {
        font-size: 13px; padding: 8px;
        border-radius: 6px; border: 2px solid #ccc;
        margin-bottom: 12px; color: #000;
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

    .icon-btn {
        background-color: transparent;
        border: none;
        padding: 4px;
    }
    .icon-btn:hover {
        background-color: transparent;
        border: none;
    }
</style>

<div class="alert-header">
    <h3 style="font-size: 18px; font-weight:600;" >Alert Configuration</h3>
    <div class="text-end">
        <div class="text-muted mb-2" style="font-size: 13px;">
            Hello, {{ auth()->user()->full_name }}! <i class="bi bi-person-circle ms-1"></i>
        </div>
        <button class="add-rule-btn" onclick="showRuleModal()">
            <i class="bi bi-plus-circle me-1"></i> Add Rule
        </button>
    </div>
</div>



@if($alertRules->isEmpty())
    <div class="rule-table">
        <h5 class="mb-3 fw-semibold">Configured Alert Thresholds</h5>
        <p class="text-muted">⚠ No alert rules configured yet.</p>
    </div>
@else
@php
    function getAQIColor($value) {
        if ($value <= 50) return '#0A6304';
        if ($value <= 100) return '#FFD70E';
        if ($value <= 150) return '#FF7700';
        if ($value <= 200) return '#980000';
        if ($value <= 300) return '#681E83';
        return '#551515';
    }

    function getAQILevel($value) {
        if ($value <= 50) return 'Good';
        if ($value <= 100) return 'Moderate';
        if ($value <= 150) return 'Unhealthy (SG)';
        if ($value <= 200) return 'Unhealthy';
        if ($value <= 300) return 'Very Unhealthy';
        return 'Hazardous';
    }
@endphp

<div class="rule-table" style="background-color:#ffffff;">
    <h5 class="mb-4 fw-" style="font-size:16px; font-weight:600;">Configured Alert Thresholds:</h5>
    <div class="row">
        @foreach($alertRules as $rule)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card p-3 h-100 border-0 shadow-sm" style="background-color: #f9f9f9; border-radius: 14px;">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="badge px-3 py-1 mb-2" style="background-color: {{ getAQIColor($rule->threshold) }}; font-size: 13px; font-weight: 300; border-radius: 6px;">
                        {{ getAQILevel($rule->threshold) }}
                    </span>
                    <div class="d-flex gap-2">
                        <button onclick="editRule({{ $rule->id }})" class="btn btn-sm icon-btn" style="color: #22577A;" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button onclick="openDeleteModal({{ $rule->id }})" class="btn btn-sm icon-btn" style="color: #dc3545;" title="Delete">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                </div>

                <h6 class="fw-semibold mt-2" style="color: #22577A;">AQI</h6>
                <p class="mb-1" style="color: #22577A;"><strong>Threshold:</strong> {{ $rule->threshold }} µg/m³</p>
                <p class="mb-1" style="color: #22577A;"><strong>Frequency:</strong> Every {{ $rule->check_frequency }}</p>
                <p class="mb-3" style="color: #22577A;"><strong>Type:</strong> {{ ucfirst($rule->alert_type) }}</p>


                <div style="background: #fff3f3; border: 2px dashed #dc3545; border-radius: 12px; padding: 10px 14px; font-size: 13px;">
                    <div style="color: #dc3545; display: flex; align-items: center;">
                        <i class="bi bi-info-circle-fill me-2" style="font-size: 16px;"></i>
                        <span>
                            This rule triggers an <strong>{{ getAQILevel($rule->threshold) }}</strong> alert every {{ $rule->check_frequency }} if AQI exceeds <strong>{{ $rule->threshold }} µg/m³</strong>.
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Add/Edit Modal -->
<div id="ruleModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-close" onclick="hideRuleModal()">&times;</div>
        <h5 id="modalTitle" class="text-center mb-3" style="font-weight: 600; color: #22577A;">New Alert Rule</h5>
        <form id="ruleForm">
            <input type="hidden" id="editRuleId">

            <label class="form-label">Pollutant Type</label>
            <select class="form-select" id="pollutant_type" required>
                <option value="AQI">AQI</option>
                <option value="PM2.5">PM2.5</option>
                <option value="PM10">PM10</option>
                <option value="CO2">CO2</option>
                <option value="O3">O3</option>
            </select>

            <label class="form-label">Threshold</label>
            <input type="number" class="form-control" id="threshold" required>

            <label class="form-label">Check Frequency</label>
            <select class="form-select" id="check_frequency" required>
                <option value="15min">15min</option>
                <option value="30min">30min</option>
                <option value="1hr">1hr</option>
            </select>

            <label class="form-label">Alert Type</label>
            <select class="form-select mb-3" id="alert_type" required>
                <option value="dashboard">Dashboard Only</option>
                <option value="email">Email + Dashboard</option>
            </select>

            <button type="submit" class="btn-submit" id="submitButton">Save</button>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal-overlay" id="deleteConfirmModal" style="display: none;">
    <div class="modal-box" style="max-width: 360px; text-align: center;">
        <div class="modal-close" onclick="hideDeleteModal()">&times;</div>
        <h5 style="color: #22577A; font-weight:bold; font-size:16px;">Confirm Deletion</h5>
        <p style="color:#737577;">Are you sure you want to delete this rule?</p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-submit" style="background-color: #FF7700; margin-bottom:5px;">Yes, Delete</button>
            <button type="button" onclick="hideDeleteModal()" class="btn-submit" style="background-color: #E4E4E4; color: #22577A;">Cancel</button>
        </form>
    </div>
</div>

@if($triggeredAlerts->isNotEmpty())
<div class="rule-table" style="margin-top: 30px;">
    <h5 class="mb-3" style="color: #22577A; font-size:16px;  font-weight:600;">Triggered Alerts 📢</h5>
    <div class="row">
        @foreach($triggeredAlerts as $alert)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card p-3" style="background-color: #fff3f3; border: 2px dashed #dc3545; ">
                <h6 style="font-size: 13px; color:#22577A;">Sensor: {{ $alert->sensor_id }}</h6>

                    <p style="font-size: 13px; margin-bottom: 4px; color:#22577A;"><strong>Pollutant:</strong> {{ $alert->pollutant_type }}</p>
                    <p style="font-size: 13px; margin-bottom: 4px; color:#22577A;"><strong>Threshold:</strong> {{ $alert->threshold }}</p>
                    <p style="font-size: 13px; color:#22577A;"><strong>Triggered AQI:</strong> {{ $alert->aqi_value }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif


<!-- Toast -->
<div class="toast-message" id="toast">✅ Rule Updated</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    let deleteRuleId = null;

    function showRuleModal(rule = null) {
        document.getElementById('ruleModal').style.display = 'flex';
        const modalTitle = document.getElementById('modalTitle');
        const submitButton = document.getElementById('submitButton');

        if (rule) {
            document.getElementById('editRuleId').value = rule.id;
            document.getElementById('pollutant_type').value = rule.pollutant_type;
            document.getElementById('threshold').value = rule.threshold;
            document.getElementById('check_frequency').value = rule.check_frequency;
            document.getElementById('alert_type').value = rule.alert_type;

            modalTitle.innerText = 'Update Alert Rule';
            submitButton.innerText = 'Update';
        } else {
            document.getElementById('editRuleId').value = '';
            document.getElementById('ruleForm').reset();

            modalTitle.innerText = 'New Alert Rule';
            submitButton.innerText = 'Save';
        }
    }

    function hideRuleModal() {
        document.getElementById('ruleModal').style.display = 'none';
    }

    function editRule(id) {
        axios.get(`/admin/alerts/${id}`)
            .then(response => {
                const rule = response.data;
                showRuleModal(rule);
            })
            .catch(error => {
                console.error(error);
                showToast('❌ Failed to fetch rule');
            });
    }

    document.getElementById('ruleForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const ruleId = document.getElementById('editRuleId').value;
        const data = {
            pollutant_type: document.getElementById('pollutant_type').value,
            threshold: document.getElementById('threshold').value,
            check_frequency: document.getElementById('check_frequency').value,
            alert_type: document.getElementById('alert_type').value,
        };

        if (ruleId) {
            axios.put(`/admin/alerts/${ruleId}`, data)
                .then(response => {
                    showToast('✏️ Rule Updated');
                    hideRuleModal();
                    setTimeout(() => location.reload(true), 1000); // force hard reload
                })
                .catch(error => {
                    console.error(error);
                    showToast('❌ Failed to update rule');
                });
        } else {
            axios.post('{{ route("alerts.store") }}', data)
                .then(response => {
                    showToast('✅ Rule Added');
                    hideRuleModal();
                    setTimeout(() => location.reload(true), 1000);
                })
                .catch(error => {
                    console.error(error);
                    showToast('❌ Failed to add rule');
                });
        }
    });

    function openDeleteModal(id) {
        deleteRuleId = id;
        document.getElementById('deleteConfirmModal').style.display = 'flex';
    }

    function hideDeleteModal() {
        deleteRuleId = null;
        document.getElementById('deleteConfirmModal').style.display = 'none';
    }

    document.getElementById('deleteForm').addEventListener('submit', function (e) {
        e.preventDefault();

        if (deleteRuleId) {
            axios.delete(`/admin/alerts/${deleteRuleId}`)
                .then(response => {
                    showToast('🗑 Rule Deleted');
                    hideDeleteModal();
                    setTimeout(() => location.reload(true), 1000);
                })
                .catch(error => {
                    console.error(error);
                    showToast('❌ Failed to delete rule');
                    hideDeleteModal();
                });
        }
    });

    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.innerText = message;
        toast.style.display = 'block';
        setTimeout(() => toast.style.display = 'none', 3000);
    }
</script>
@endsection
