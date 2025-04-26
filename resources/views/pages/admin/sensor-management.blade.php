@extends('layouts.admin')

@section('title', 'Sensor Management')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<style>
     body {
        background: #ffffff;
        font-family: 'Poppins', sans-serif;
    }
    .sensor-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 20px;
        color: #22577A;
    }
    .sensor-header h3 {
        font-weight: 600;
        font-size: 18px;
    }
    .add-sensor-btn {
        background-color: #22577A;
        color: white;
        padding: 6px 16px;
        font-size: 13px;
        border: none;
        border-radius: 6px;
    }
    .sensor-layout { display: flex; gap: 20px; }
    .sensor-left { flex: 1; }
    .sensor-right { flex: 1.5; }
    .sensor-card {
        background-color: rgba(228, 228, 228, 0.45);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .sensor-info h6 {
        font-size: 14px; font-weight: 600; color: #22577A; margin: 0;
    }
    .sensor-info small { font-size: 12px; color: #444; }
    .sensor-status {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
    }
    .active-status { background-color: #198754; color: white; }
    .inactive-status { background-color: #dc3545; color: white; }
    .sensor-actions button {
        background: none;
        border: none;
        font-size: 16px;
        margin-left: 8px;
        cursor: pointer;
        color: #22577A;
    }
    .delete-icon { color: #dc3545; }
    #map { height: 360px; border-radius: 12px; }
    .legend-labels-container {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 12px;
        font-size: 12px;
    }
    .legend-label { display: flex; align-items: center; gap: 6px; }
    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

    
    .modal-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    .modal-box {
        background: white;
        padding: 24px;
        border-radius: 12px;
        width: 380px;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
        position: relative;
    }
    .modal-box h5 {
        text-align: center;
        font-size: 16px;
        color: #22577A;
        margin-bottom: 16px;
    }
    .modal-box .form-label {
        font-size: 13px;
        margin-bottom: 4px;
        color: #22577A;
    }
    .modal-box input,
    .modal-box select {
        border-radius: 8px;
        border: 2px solid #ccc;
        width: 100%;
        padding: 8px 10px;
        margin-bottom: 12px;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .modal-box input:focus,
    .modal-box select:focus {
        border-color: #80bdff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }

    .modal-box input::placeholder {
        color: #999;
        font-size: 12.5px;
    }

    .btn-submit {
        background-color: #22577A;
        color: white;
        padding: 9px;
        width: 100%;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
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
</style>

<div class="sensor-header">
    <h3>Sensor Management</h3>
    <div class="text-end">
        <div class="text-muted mb-2" style="font-size: 13px;">
            Hello, {{ auth()->user()->full_name }}! <i class="bi bi-person-circle ms-1"></i>
        </div>
        <button class="add-sensor-btn" onclick="showSensorModal()"><i class="bi bi-broadcast-pin me-1"></i>Add Sensor</button>
    </div>

</div>

<!-- Add Sensor Modal -->
<div class="modal-overlay" id="sensorModal">
    <div class="modal-box">
        <div class="modal-close" onclick="hideSensorModal()">&times;</div>
        <h5>Add Sensor</h5>
        <form method="POST" action="{{ route('sensors.store') }}" style="z-index: 1001;">
            @csrf

            <label class="form-label">Sensor ID</label>
            <input type="text" name="sensor_id" placeholder="eg: Sensor #001" required>

            <label class="form-label">City / Location</label>
            <input type="text" name="location" placeholder="Location" required>

            <div style="display: flex; gap: 12px;">
                <div style="flex: 1;">
                    <label class="form-label">Latitude</label>
                    <input type="text" name="lat" placeholder="eg: 6.9271" required>
                </div>
                <div style="flex: 1;">
                    <label class="form-label">Longitude</label>
                    <input type="text" name="lng" placeholder="eg: 79.8612" required>
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <div style="flex: 1;">
                    <label class="form-label">AQI Level</label>
                    <input type="number" name="aqi" placeholder="AQI" required>
                </div>
                <div style="flex: 1;">
                    <label class="form-label">Status</label>
                    <select name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-submit" style="margin-top: 10px;">Add</button>
        </form>

    </div>
</div>

<!-- Sensor Layout -->
<div class="sensor-layout">
    <div class="sensor-left" id="sensorContainer">
        @foreach ($sensors as $sensor)
        <div class="sensor-card" id="{{ $sensor->sensor_id }}">
            <div class="sensor-info">
                <h6>{{ $sensor->sensor_id }}</h6>
                <small>{{ $sensor->location }}</small><br>
                <small>AQI: {{ $sensor->aqi }}</small>
            </div>
            <div>
                <span class="sensor-status {{ $sensor->status === 'active' ? 'active-status' : 'inactive-status' }}">
                    {{ ucfirst($sensor->status) }}
                </span>
            </div>
            <div class="sensor-actions d-flex align-items-center">
    <button onclick="showEditModal({{ $sensor }})"><i class="bi bi-pencil-square"></i></button>

    <form onsubmit="event.preventDefault(); confirmDelete('{{ route('sensors.destroy', $sensor->id) }}')">
        @csrf
        @method('DELETE')
        <button type="submit"><i class="bi bi-trash-fill delete-icon"></i></button>
    </form>
</div>

        </div>
        @endforeach
    </div>

    <div class="sensor-right">
        <div id="map"></div>
        <div class="legend-labels-container">
            <div class="legend-label"><div class="legend-color" style="background:#0A6304;"></div>-(0–50)</div>
            <div class="legend-label"><div class="legend-color" style="background:#FFD70E;"></div>-(51–100)</div>
            <div class="legend-label"><div class="legend-color" style="background:#FF7700;"></div>-(101–150)</div>
            <div class="legend-label"><div class="legend-color" style="background:#980000;"></div>-(151–200)</div>
            <div class="legend-label"><div class="legend-color" style="background:#681E83;"></div>-(201–300)</div>
            <div class="legend-label"><div class="legend-color" style="background:#551515;"></div>-(301–500)</div>
        </div>
    </div>
</div>

<!-- Edit Sensor Modal -->
<div class="modal-overlay" id="editSensorModal">
    <div class="modal-box">
        <div class="modal-close" onclick="hideEditModal()">&times;</div>
        <h5>Edit Sensor</h5>
        <form method="POST" id="editSensorForm">
            @csrf
            @method('PUT')

            <label class="form-label">Sensor ID</label>
            <input type="text" name="sensor_id" id="editSensorId" required>

            <label class="form-label">City / Location</label>
            <input type="text" name="location" id="editLocation" required>

            <div style="display: flex; gap: 12px;">
                <div style="flex: 1;">
                    <label class="form-label">Latitude</label>
                    <input type="text" name="lat" id="editLat" required>
                </div>
                <div style="flex: 1;">
                    <label class="form-label">Longitude</label>
                    <input type="text" name="lng" id="editLng" required>
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <div style="flex: 1;">
                    <label class="form-label">AQI Level</label>
                    <input type="number" name="aqi" id="editAqi" required>
                </div>
                <div style="flex: 1;">
                    <label class="form-label">Status</label>
                    <select name="status" id="editStatus" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-submit" style="margin-top: 10px;">Update</button>
        </form>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteConfirmModal">
    <div class="modal-box" style="max-width: 360px; text-align: center;">
        <div class="modal-close" onclick="hideDeleteModal()">&times;</div>
        <h5>Confirm Deletion</h5>
        <p>Are you sure you want to delete this sensor?</p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-submit" style="background-color: #FF7700; margin-bottom:5px;">Yes, Delete</button>
            <button type="button" onclick="hideDeleteModal()" class="btn-submit" style="background-color: #E4E4E4; color: #22577A;">Cancel</button>
        </form>
    </div>
</div>




<!-- Leaflet -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<!-- ✅ Inside <script> tag at the bottom -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    const map = L.map('map', { zoomControl: false }).setView([6.9271, 79.8612], 11);
    L.control.zoom({
    position: 'bottomright'
    }).addTo(map);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    function getAQIColor(aqi) {
        if (aqi <= 50) return '#0A6304';
        if (aqi <= 100) return '#FFD70E';
        if (aqi <= 150) return '#FF7700';
        if (aqi <= 200) return '#980000';
        if (aqi <= 300) return '#681E83';
        return '#551515';
    }

    // ✅ Render markers for each saved sensor in DB
    const sensorsFromDB = @json($sensors);
    sensorsFromDB.forEach(sensor => {
    if (sensor.lat && sensor.lng) {
        const marker = L.circleMarker([sensor.lat, sensor.lng], {
            radius: 8,
            fillColor: getAQIColor(sensor.aqi),
            color: '#fff',
            weight: 1,
            opacity: 1,
            fillOpacity: 0.8
        }).addTo(map).bindPopup(`
            <strong>${sensor.sensor_id}</strong><br>
            Location: ${sensor.location}<br>
            AQI: ${sensor.aqi}<br>
            Status: ${sensor.status}
        `);
    }
});



    function showSensorModal() {
        document.getElementById('sensorModal').style.display = 'flex';
    }

    function hideSensorModal() {
        document.getElementById('sensorModal').style.display = 'none';
    }

    function confirmDelete(actionUrl) {
        const form = document.getElementById('deleteForm');
        form.setAttribute('action', actionUrl);
        document.getElementById('deleteConfirmModal').style.display = 'flex';
    }

    function hideDeleteModal() {
        document.getElementById('deleteConfirmModal').style.display = 'none';
    }

    function showEditModal(sensor) {
    document.getElementById('editSensorForm').action = `/admin/sensors/${sensor.id}`;
    document.getElementById('editSensorId').value = sensor.sensor_id;
    document.getElementById('editLocation').value = sensor.location;
    document.getElementById('editLat').value = sensor.lat;
    document.getElementById('editLng').value = sensor.lng;
    document.getElementById('editAqi').value = sensor.aqi;
    document.getElementById('editStatus').value = sensor.status;
    document.getElementById('editSensorModal').style.display = 'flex';
}

function hideEditModal() {
    document.getElementById('editSensorModal').style.display = 'none';
}

</script>

@endsection
