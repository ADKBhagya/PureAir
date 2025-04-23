@extends('layouts.admin')

@section('title', 'Sensor Management')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<style>
    body {
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
        top: 8px;
        right: 12px;
        font-size: 18px;
        color: #FF7700;
        cursor: pointer;
    }
</style>

<div class="sensor-header">
    <h3>Sensor Management</h3>
    <div class="text-end">
        <div class="text-muted mb-2" style="font-size: 13px;">Hello, User! <i class="bi bi-person-circle ms-1"></i></div>
        <button class="add-sensor-btn" onclick="showSensorModal()"><i class="bi bi-broadcast-pin me-1"></i>Add Sensor</button>
    </div>
</div>

<div class="sensor-layout">
    <div class="sensor-left" id="sensorContainer"></div>
    <div class="sensor-right">
        <div id="map"></div>
        <div class="legend-labels-container">
            <div class="legend-label"><div class="legend-color" style="background:#0A6304;"></div>(0–50)</div>
            <div class="legend-label"><div class="legend-color" style="background:#FFD70E;"></div>(51–100)</div>
            <div class="legend-label"><div class="legend-color" style="background:#FF7700;"></div>(101–150)</div>
            <div class="legend-label"><div class="legend-color" style="background:#980000;"></div>(151–200)</div>
            <div class="legend-label"><div class="legend-color" style="background:#681E83;"></div>(201–300)</div>
            <div class="legend-label"><div class="legend-color" style="background:#551515;"></div>(301–500)</div>
        </div>
    </div>
</div>

<!-- Modals -->
<div class="modal-overlay" id="sensorModal">
    <div class="modal-box">
        <div class="modal-close" onclick="hideSensorModal()">&times;</div>
        <h5 style="font-weight: 600; color: #22577A; font-size: 18px; ">Add Sensor</h5>
        <form onsubmit="addSensor(event)">
            <label class="form-label">Sensor ID</label>
            <input type="text" id="newSensorID" placeholder="eg: Sensor #001" required>
            <label class="form-label">City / Location</label>
            <input type="text" id="newLocation" placeholder="Location" required>
            <label class="form-label">AQI Level</label>
            <input type="number" id="newAQI" placeholder="AQI" required>
            <label class="form-label">Status</label>
            <select id="newStatus">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <button type="submit" class="btn-submit">Add</button>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editSensorModal">
    <div class="modal-box">
        <div class="modal-close" onclick="hideEditSensorModal()">&times;</div>
        <h5 style="font-weight: 600; color: #22577A; font-size: 18px; ">Edit Sensor</h5>
        <form>
            <label class="form-label">Sensor ID</label>
            <input type="text" id="editSensorID" required>
            <label class="form-label">City / Location</label>
            <input type="text" id="editLocation" required>
            <label class="form-label">AQI Level</label>
            <input type="number" id="editAQI" required>
            <label class="form-label">Status</label>
            <select id="editStatus">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <button type="submit" class="btn-submit">Update</button>
        </form>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<!-- Inside your existing <script> tag -->
<script>
    let map = L.map('map').setView([6.9271, 79.8612], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    const sensorMarkers = {};

    function getAQIColor(aqi) {
        if (aqi <= 50) return '#0A6304';
        if (aqi <= 100) return '#FFD70E';
        if (aqi <= 150) return '#FF7700';
        if (aqi <= 200) return '#980000';
        if (aqi <= 300) return '#681E83';
        return '#551515';
    }

    function randomizeCoordinates() {
        const lat = 6.9271 + (Math.random() - 0.5) * 0.2;
        const lng = 79.8612 + (Math.random() - 0.5) * 0.2;
        return [lat, lng];
    }

    function showSensorModal() {
        document.getElementById('sensorModal').style.display = 'flex';
    }

    function hideSensorModal() {
        document.getElementById('sensorModal').style.display = 'none';
    }

    function hideEditSensorModal() {
        document.getElementById('editSensorModal').style.display = 'none';
    }

    function addSensor(event) {
        event.preventDefault();

        const idInput = document.getElementById('newSensorID');
        const locationInput = document.getElementById('newLocation');
        const aqiInput = document.getElementById('newAQI');
        const status = document.getElementById('newStatus').value;

        const id = idInput.value.trim();
        const location = locationInput.value.trim();
        const aqi = aqiInput.value.trim();

        let isValid = true;
        idInput.style.borderColor = "#ccc";
        aqiInput.style.borderColor = "#ccc";

        const idPattern = /^Sensor\s?#\d+$/i;
        if (!idPattern.test(id)) {
            idInput.style.borderColor = "red";
            isValid = false;
        }

        if (!/^\d+$/.test(aqi)) {
            aqiInput.style.borderColor = "red";
            isValid = false;
        }

        if (!isValid) return;

        const aqiNum = parseInt(aqi);
        const statusClass = status === 'active' ? 'active-status' : 'inactive-status';

        const sensorHTML = `
            <div class="sensor-card" id="${id}">
                <div class="sensor-info">
                    <h6>${id}</h6>
                    <small>${location}</small><br>
                    <small>AQI: ${aqiNum}</small>
                </div>
                <div><span class="sensor-status ${statusClass}">${capitalize(status)}</span></div>
                <div class="sensor-actions">
                    <button onclick="editSensor('${id}', '${location}', ${aqiNum}, '${status}')"><i class="bi bi-pencil-square"></i></button>
                    <button onclick="deleteSensor('${id}')"><i class="bi bi-trash-fill delete-icon"></i></button>
                </div>
            </div>
        `;

        document.getElementById('sensorContainer').insertAdjacentHTML('beforeend', sensorHTML);

        const coords = randomizeCoordinates();
        const marker = L.circleMarker(coords, {
            radius: 8,
            fillColor: getAQIColor(aqiNum),
            color: '#fff',
            weight: 1,
            opacity: 1,
            fillOpacity: 0.8
        }).addTo(map).bindPopup(`<strong>${id}</strong><br>AQI Level: ${aqiNum}`);

        sensorMarkers[id] = marker;
        hideSensorModal();
    }

    function editSensor(id, location, aqi, status) {
        document.getElementById('editSensorID').value = id;
        document.getElementById('editLocation').value = location;
        document.getElementById('editAQI').value = aqi;
        document.getElementById('editStatus').value = status;

        document.getElementById('editSensorModal').onsubmit = function (e) {
            e.preventDefault();
            const updatedId = document.getElementById('editSensorID').value;
            const updatedLocation = document.getElementById('editLocation').value;
            const updatedAQI = parseInt(document.getElementById('editAQI').value);
            const updatedStatus = document.getElementById('editStatus').value;
            const updatedStatusClass = updatedStatus === 'active' ? 'active-status' : 'inactive-status';

            const card = document.getElementById(updatedId);
            if (card) {
                card.querySelector('.sensor-info h6').textContent = updatedId;
                card.querySelector('.sensor-info small').textContent = updatedLocation;
                card.querySelectorAll('.sensor-info small')[1].textContent = `AQI: ${updatedAQI}`;
                const statusSpan = card.querySelector('.sensor-status');
                statusSpan.className = `sensor-status ${updatedStatusClass}`;
                statusSpan.textContent = capitalize(updatedStatus);

                if (sensorMarkers[updatedId]) {
                    sensorMarkers[updatedId].remove();
                }

                const coords = randomizeCoordinates();
                const newMarker = L.circleMarker(coords, {
                    radius: 8,
                    fillColor: getAQIColor(updatedAQI),
                    color: '#fff',
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.8
                }).addTo(map).bindPopup(`<strong>${updatedId}</strong><br>AQI Level: ${updatedAQI}`);

                sensorMarkers[updatedId] = newMarker;
            }

            hideEditSensorModal();
        };

        document.getElementById('editSensorModal').style.display = 'flex';
    }

    function deleteSensor(id) {
        const card = document.getElementById(id);
        if (card) card.remove();
        if (sensorMarkers[id]) {
            map.removeLayer(sensorMarkers[id]);
            delete sensorMarkers[id];
        }
    }

    function capitalize(text) {
        return text.charAt(0).toUpperCase() + text.slice(1);
    }
</script>

@endsection
