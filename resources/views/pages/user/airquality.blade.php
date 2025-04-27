@extends('layouts.user')

@section('title', 'Air Quality')

@section('content')
<style>
    body { background: #ffffff; font-family: 'Poppins', sans-serif; }
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
        padding-right:70px;
    }

    .legend-label {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-size: 14px;
    }



    .legend-color {
        width: 20px;
        height: 20px;
        margin-right: 10px;
        border-radius: 3px;
    }

    .map-container {
    border: 1px solid #ccc;
    border-radius: 12px;
    padding: 0;
    margin-bottom: 30px;
    position: relative;
    width: 1000px;
    margin-left: auto;
    margin-right: auto; /* 🔥 This centers the map container */
}


    #colomboMap {
    width: 100%;
    height: 450px; /* Must have height */
    border-radius: 8px;
}


    .map-btn-location {
        position: absolute;
        top: 15px;
        right: 15px;
        background-color: #FF7700;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size:12px;
        font-weight: 500;
        z-index: 999;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .input-group {
        margin-top: -40px;
    }

    .input-group-text {
        background-color: #22577A;
        border: none;
        color: white;
        font-size:14px;
    }

    .form-select {
        font-family: 'Poppins', sans-serif;
        color: #22577A;
        font-size:12px;
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

    .aqi-summary-card {
        background-color: #F3F4F6;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        font-family: 'Poppins', sans-serif;
    }

    .aqi-summary-card h6 {
        font-weight: 600;
        color: #22577A;
        margin-bottom: 15px;
    }

    .summary-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .summary-item span {
        font-size: 18px;
        margin-right: 10px;
    }

.leaflet-popup-close-button {
    color: #22577A !important; /* Theme Blue */
    font-weight: bold !important; /* Bold */
    font-size: 18px !important; /* Slightly bigger for better UX */
    top: 5px !important;
    right: 8px !important;
}

</style>
<div class="container mt-5 mb-4">
    <!-- Dropdown Filter -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-text" for="locationSelect">Select Location</label>
                <select id="locationSelect" class="form-select">
                    <option value="">-- Choose a city --</option>
                    @foreach($sensors as $sensor)
                        <option value="{{ $sensor->id }}">{{ $sensor->location }}</option> <!-- ✅ FIX HERE -->
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="d-flex justify-content-center">
        <div class="map-container">
            <div id="colomboMap"></div>
            <button id="userLocationButton" class="map-btn-location">📍 Detect My Location</button>
        </div>
    </div>


 <!-- AQI Legend and Summary -->
<div class="row justify-content-between align-items-start mt-4">
    <div class="col-md-6">
        <div class="aqi-legend-box">
            <h5 class="primary-blue fw-semibold mb-3 text-center" style="font-size: 16px;">AQI Color Levels</h5>
         <div class="legend-labels-container" style="padding-left: 90px; font-size: 14px;">
            <div class="legend-label"><div class="legend-color" style="background-color: #0A6304;"></div> -- Good (0–50)</div>
            <div class="legend-label"><div class="legend-color" style="background-color: #FFD70E;"></div> -- Moderate (51–100)</div>
            <div class="legend-label"><div class="legend-color" style="background-color: #FF7700;"></div> -- Unhealthy Sensitive Groups (101–150)</div>
            <div class="legend-label"><div class="legend-color" style="background-color: #980000;"></div> -- Unhealthy (151–200)</div>
            <div class="legend-label"><div class="legend-color" style="background-color: #681E83;"></div> -- Very Unhealthy (201–300)</div>
            <div class="legend-label"><div class="legend-color" style="background-color: #551515;"></div> -- Hazardous (301–500)</div>
        
        </div>

        </div>
    </div>

    <!-- Quote Container -->
    <div class="col-md-6">
        <div class="aqi-legend-box" style="height: 100%;">
            <h5 class="primary-blue fw-semibold mb-3 text-center" style="font-size: 16px;">🌱 Thought for the Air</h5>
            <div style="display: flex; align-items: flex-start;">
            <div style="font-size: 14px; color: #2F3E46; font-style: italic; margin-left:40px; padding-bottom:32px;">
                “Breathe clean. Live green. The quality of the air reflects the quality of our care.”
            </div>
            </div>

            <div class="mb-3">
            <h6 class="primary-blue fw-semibold mb-3 text-center" style="font-size:16px">🌬️ Air Tip of the Day</h6>
            <p style="font-size: 14px; color: #2F3E46; font-style: italic; margin-left:40px; padding-bottom:20px;">
                Keep indoor plants like <strong>peace lily</strong> or <strong>snake plant</strong> – they help purify the air naturally.
            </p>
        </div>
        </div>
    </div>


    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

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

    function getAQIEmoji(aqi) {
        if (aqi <= 50) return '😊';
        if (aqi <= 100) return '😐';
        if (aqi <= 150) return '😷';
        if (aqi <= 200) return '🤢';
        if (aqi <= 300) return '🤮';
        return '☠️';
    }

    function getAQIStatus(aqi) {
        if (aqi <= 50) return 'Good';
        if (aqi <= 100) return 'Moderate';
        if (aqi <= 150) return 'Unhealthy for Sensitive Groups';
        if (aqi <= 200) return 'Unhealthy';
        if (aqi <= 300) return 'Very Unhealthy';
        return 'Hazardous';
    }

    function createAQIMarkerIcon(aqi) {
        const color = getAQIColor(aqi);
        return L.divIcon({
            html: `<div style="background-color: ${color}; width: 18px; height: 18px; border: 2px solid white; border-radius: 50%; box-shadow: 0 0 4px rgba(0,0,0,0.4);"></div>`,
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
                        labels: ['4h ago', '3h ago', '2h ago', '1h ago', 'Now'],
                        datasets: [{ data: data.reverse(), backgroundColor: colors }]
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

    function createPopup(id, location, emoji, color, value, status, updatedAgo, fullDate, chartData) {
        const latestAQI = chartData.length ? chartData[chartData.length - 1] : value;
        return `
            <div style="font-family: 'Poppins', sans-serif; background-color: #F9FAFB; padding: 16px; width: 240px; border-radius: 16px; border: 1px solid #ccc; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                <div style="text-align: center; font-weight: 600; color: #22577A; font-size: 16px;">${location}</div>
                <hr style="margin: 8px 0; border-color: #477390;">
                <div style="background-color: ${color}; color: white; border-radius: 20px; padding: 8px 10px; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <span style="font-size: 22px;">${emoji}</span>
                    <span style="font-size: 13px;">${latestAQI} - ${status}</span>
                </div>
                <hr style="margin: 12px 0; border-color: #477390;">
                <p style="text-align: center; color: #22577A; font-size: 13px; font-weight: 500;">${updatedAgo}</p>
                <p style="text-align: center; color: #2F3E46; font-size: 12px;">${fullDate}</p>
                <div style="position: relative; margin-top: 8px;">
                    <canvas id="${id}" width="220" height="80"></canvas>
                    <span style="position: absolute; top: 4px; right: 6px; font-size: 10px; color: #666;">AQI US</span>
                </div>
                <button style="margin-top: 12px; background-color: #22577A; color: white; font-size: 12px; font-weight: 500; border: none; padding: 8px; width: 100%; border-radius: 10px; height: 36px;">
                    More Info
                </button>
            </div>
        `;
    }

    const markers = {};

    @foreach($sensors as $sensor)
        const marker_{{ $sensor->id }} = L.marker([{{ $sensor->lat }}, {{ $sensor->lng }}], {
            icon: createAQIMarkerIcon({{ $sensor->aqi }})
        }).addTo(colomboMap);

        marker_{{ $sensor->id }}.on('click', function () {
            marker_{{ $sensor->id }}.bindPopup('<div style="padding:20px; color:#22577A; font-weight:600;">Loading AQI data...</div>').openPopup();

            axios.get('/air-quality/data/{{ $sensor->id }}')
                .then(response => {
                    const data = response.data;
                    const popupContent = createPopup(
                        'chart_{{ $sensor->id }}',
                        data.location,
                        getAQIEmoji(data.aqi),
                        getAQIColor(data.aqi),
                        data.aqi,
                        data.category,
                        data.updatedAgo,
                        data.fullDate,
                        data.last_readings || [30, 45, 50, 35, 40]
                    );
                    marker_{{ $sensor->id }}.setPopupContent(popupContent);
                    drawChart('chart_{{ $sensor->id }}', data.last_readings || [30, 45, 50, 35, 40]);
                })
                .catch(error => {
                    console.error('Failed to fetch AQI data', error);
                    marker_{{ $sensor->id }}.setPopupContent('<div style="padding:20px; color:red;">Failed to load data</div>');
                });
        });

        markers['{{ $sensor->id }}'] = marker_{{ $sensor->id }};
    @endforeach

    document.getElementById('locationSelect').addEventListener('change', function () {
        const selected = this.value;
        if (selected && markers[selected]) {
            colomboMap.closePopup();
            markers[selected].openPopup();
            colomboMap.flyTo(markers[selected].getLatLng(), 13, {
                animate: true,
                duration: 1.5
            });
            setTimeout(() => {
                document.getElementById('locationSelect').selectedIndex = 0;
            }, 1000);
        }
    });

    document.getElementById('userLocationButton').addEventListener('click', function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                const userLatLng = [position.coords.latitude, position.coords.longitude];
                const liveAQI = Math.floor(Math.random() * 100) + 1;
                const data = [
                    liveAQI - 15,
                    liveAQI - 10,
                    liveAQI - 5,
                    liveAQI,
                    liveAQI + 5
                ];

                const popupHtml = createPopup(
                    'userLocationChart',
                    'Your Location 🌍',
                    getAQIEmoji(liveAQI),
                    getAQIColor(liveAQI),
                    liveAQI,
                    getAQIStatus(liveAQI),
                    'Just now',
                    new Date().toLocaleString(),
                    data
                );

                const userMarker = L.marker(userLatLng, {
                    icon: createAQIMarkerIcon(liveAQI)
                }).addTo(colomboMap);

                userMarker.bindPopup(popupHtml)
                    .on('popupopen', () => drawChart('userLocationChart', data))
                    .openPopup();

                colomboMap.flyTo(userLatLng, 14, {
                    animate: true,
                    duration: 1.5
                });

            }, function (error) {
                console.error(error);
                alert("Unable to retrieve your location.");
            });
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    });
});
</script>
@endsection


