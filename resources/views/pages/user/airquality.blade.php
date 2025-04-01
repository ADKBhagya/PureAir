@extends('layouts.user')

@section('title', 'Air Quality')

@section('content')
<style>
    .primary-blue { color: #22577A; }
    .bg-card {
        background-color: rgba(228, 228, 228, 0.45);
        border-radius: 10px;
    }

    .aqi-legend-box {
        border-radius: 12px;
        padding: 20px;
        margin-top: 30px;
        background-color: rgba(228, 228, 228, 0.45);
        font-family: 'Poppins', sans-serif;
    }

    .legend-label {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .legend-color {
        width: 20px;
        height: 20px;
        margin-left: 10px;
        border-radius: 3px;
    }

    .map-container {
        border: 1px solid #ccc;
        border-radius: 12px;
        padding: 0;
        margin-bottom: 30px;
    }

    #colomboMap {
        width: 100%;
        height: 480px;
        border-radius: 8px;
        z-index: 0;
    }

    .legend-label {
        font-size: 20px;
        color: #6E767D;
        margin-left: 100px;
    }

    .input-group-text {
        background-color: #22577A;
        border: none;
        color: white;
    }

    .form-select {
        font-family: 'Poppins', sans-serif;
        color: #22577A;
    }

    .leaflet-popup-content-wrapper {
        background: transparent !important;
        box-shadow: none !important;
        padding: 0 !important;
        border-radius: 0 !important;
    }

    .leaflet-popup-tip-container {
        display: none !important;
    }

    .leaflet-popup-content {
        margin: 0 !important;
    }
    
</style>

<div class="container mt-5 mb-4">
    <div class="text-center mb-4">
        <h3 class="fw-bold primary-blue">Air Quality - Colombo District</h3>
    </div>

    <!-- Dropdown Filter -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-text" for="locationSelect">Select Location</label>
                <select id="locationSelect" class="form-select">
                    <option value="">-- Choose a city --</option>
                    <option value="homagama">Homagama</option>
                    <option value="moratuwa">Moratuwa</option>
                    <option value="colombo">Colombo Central</option>
                </select>
            </div>
        </div>
    </div>

    <div class="map-container">
        <div id="colomboMap"></div>
    </div>

    <div class="aqi-legend-box">
        <h5 class="primary-blue fw-semibold mb-3 text-center">AQI Color Levels</h5>
        <div class="row justify-content-center">
            <div class="col-md-6 mx-auto">
                <div class="legend-label"><div class="legend-color" style="background-color: #0A6304;"></div>- Good (0–50)</div>
                <div class="legend-label"><div class="legend-color" style="background-color: #FFD70E;"></div>- Moderate (51–100)</div>
                <div class="legend-label"><div class="legend-color" style="background-color: #FF7700;"></div>- Unhealthy for Sensitive Groups (101–150)</div>
                <div class="legend-label"><div class="legend-color" style="background-color: #980000;"></div>- Unhealthy (151–200)</div>
                <div class="legend-label"><div class="legend-color" style="background-color: #681E83;"></div>- Very Unhealthy (201–300)</div>
                <div class="legend-label"><div class="legend-color" style="background-color: #551515;"></div>- Hazardous (301–500)</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const colomboMap = L.map('colomboMap').setView([6.9271, 79.8612], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(colomboMap);

    function getAQIColor(aqi) {
        if (aqi <= 50) return '#0A6304';
        if (aqi <= 100) return '#FFD70E';
        if (aqi <= 150) return '#FF7700';
        if (aqi <= 200) return '#980000';
        if (aqi <= 300) return '#681E83';
        return '#551515';
    }

    function createAQIMarkerIcon(aqi) {
        const color = getAQIColor(aqi);
        const iconHtml = `
            <div style="
                background-color: ${color};
                width: 18px;
                height: 18px;
                border: 2px solid white;
                border-radius: 50%;
                box-shadow: 0 0 4px rgba(0,0,0,0.4);
            "></div>
        `;
        return L.divIcon({
            html: iconHtml,
            className: '',
            iconSize: [18, 18],
            iconAnchor: [9, 9],
            popupAnchor: [0, -9]
        });
    }

    function drawChart(canvasId, data) {
        setTimeout(() => {
            const ctx = document.getElementById(canvasId);
            if (ctx) {
                const colors = data.map(val => getAQIColor(val));
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['19.30', '01.30', '01.30', '07.30', '13.30'],
                        datasets: [{ data: data, backgroundColor: colors }]
                    },
                    options: {
                        responsive: false,
                        scales: {
                            y: { beginAtZero: true, max: 500 },
                            x: { grid: { display: false } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            }
        }, 300);
    }

    function createPopup(id, location, emoji, color, value, status, time, date, chartData) {
        return `
            <div style="font-family: 'Poppins'; background-color: #F3F4F6; padding: 20px; width: 280px; border-radius: 20px; border: 1px solid #ccc;">
                <div style="display: flex; justify-content: space-between; font-size: 20px; font-weight: 500; color: #22577A;">
                    <span>${location}</span>
                </div>
                <hr style="margin: 10px 0; border-color: #477390;">
                <div style="background-color: ${color}; color: white; border-radius: 20px; padding: 12px 16px; font-size: 20px; display: flex; justify-content: center; align-items: center; gap: 10px;">
                    <span style="font-size: 22px;">${emoji}</span>
                    <span>${value} - ${status}</span>
                </div>
                <hr style="margin: 16px 0; border-color: #477390;">
                <p style="color: #22577A; font-weight: bold;">Updated ${time}</p>
                <p style="color: #2F3E46; font-size: 15px;">${date}</p>
                <div style="position: relative;">
                    <canvas id="${id}" width="250" height="100"></canvas>
                    <span style="position: absolute; top: 5px; right: 0; font-size: 11px; color: #666;">AQI US</span>
                </div>
                <button style="margin-top: 18px; background-color: #22577A; color: white; font-size: 16px; border: none; padding: 10px; width: 100%; border-radius: 12px;">
                    Click For More Information
                </button>
            </div>
        `;
    }

    const homagamaData = [30, 25, 60, 33, 38];
    const homagamaPopup = createPopup('homagamaChart', 'Homagama,SriLanka', '😊', getAQIColor(40), 40, 'Good', '3 Hours ago', 'March 25, 2025 2:00 PM', homagamaData);
    const moratuwaData = [45, 60, 78, 55, 50];
    const moratuwaPopup = createPopup('moratuwaChart', 'Moratuwa,SriLanka', '😐', getAQIColor(78), 78, 'Moderate', '2 Hours ago', 'March 25, 2025 3:00 PM', moratuwaData);
    const colomboData = [100, 120, 150, 162, 140];
    const colomboPopup = createPopup('colomboChart', 'Colombo Central,SriLanka', '😷', getAQIColor(162), 162, 'Unhealthy', '1 Hour ago', 'March 25, 2025 4:00 PM', colomboData);

    const markers = {
        homagama: L.marker([6.8441, 79.9655], { icon: createAQIMarkerIcon(40) }).addTo(colomboMap).bindPopup(homagamaPopup).on('popupopen', () => drawChart('homagamaChart', homagamaData)),
        moratuwa: L.marker([6.8449, 79.9020], { icon: createAQIMarkerIcon(78) }).addTo(colomboMap).bindPopup(moratuwaPopup).on('popupopen', () => drawChart('moratuwaChart', moratuwaData)),
        colombo: L.marker([6.9271, 79.8612], { icon: createAQIMarkerIcon(162) }).addTo(colomboMap).bindPopup(colomboPopup).on('popupopen', () => drawChart('colomboChart', colomboData))
    };

    document.getElementById('locationSelect').addEventListener('change', function () {
        const selected = this.value;
        if (selected && markers[selected]) {
            const marker = markers[selected];
            colomboMap.setView(marker.getLatLng(), 13);
            marker.openPopup();
        }
    });
});
</script>
@endsection
