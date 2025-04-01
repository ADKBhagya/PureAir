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
        margin-left:10px;
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



    .map-container iframe {
        width: 100%;
        height: 480px;
        border: none;
        border-radius: 8px;
    }

    .legend-label {
    font-size: 20px;          
    color: #6E767D;   
    margin-left: 100px;        
}
</style>

<div class="container mt-5 mb-4">
    <div class="text-center mb-4">
        <h3 class="fw-bold primary-blue">Air Quality - Colombo District</h3>
    </div>

    <div class="map-container">
    <div id="colomboMap"></div>
</div>


    <div class="aqi-legend-box">
        <h5 class="primary-blue fw-semibold mb-3 text-center">AQI Color Levels</h5>
        <div class="row justify-content-center">
    <div class="col-md-6 mx-auto" >
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
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* Remove Leaflet's white popup background */
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const colomboMap = L.map('colomboMap').setView([6.9271, 79.8612], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(colomboMap);

    // AQI color logic
    function getAQIColor(aqi) {
        if (aqi <= 50) return '#0A6304';           // Good
        if (aqi <= 100) return '#FFD70E';          // Moderate
        if (aqi <= 150) return '#FF7700';          // Unhealthy SG
        if (aqi <= 200) return '#980000';          // Unhealthy
        if (aqi <= 300) return '#681E83';          // Very Unhealthy
        return '#551515';                          // Hazardous
    }

    // Create circular marker based on AQI
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

    // Chart.js rendering
    function drawChart(canvasId, data) {
        setTimeout(() => {
            const ctx = document.getElementById(canvasId);
            if (ctx) {
                const colors = data.map(val => getAQIColor(val));
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['19.30', '01.30', '01.30', '07.30', '13.30'],
                        datasets: [{
                            data: data,
                            backgroundColor: colors
                        }]
                    },
                    options: {
                        responsive: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 500,
                                ticks: { font: { size: 11 }, color: '#666' },
                                grid: { color: '#ccc' }
                            },
                            x: {
                                ticks: { font: { size: 11 }, color: '#666' },
                                grid: { display: false }
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }
        }, 300);
    }

    // Popup HTML generator
    function createPopup(id, location, emoji, color, value, status, time, date, chartData) {
        return `
            <div style="font-family: 'Poppins', sans-serif; background-color: #F3F4F6; padding: 20px; width: 280px; border-radius: 20px; border: 1px solid #ccc;">
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 20px; font-weight: 500; color: #22577A;">
                    <span>${location}</span>
                </div>
                <hr style="margin: 10px 0; border-color: #477390;">
                <div style="background-color: ${color}; color: white; border-radius: 20px; padding: 12px 16px; font-size: 20px; font-weight: 500; display: flex; justify-content: center; align-items: center; gap: 10px;">
                    <span style="font-size: 22px;">${emoji}</span>
                    <span>${value} - ${status}</span>
                </div>
                <hr style="margin: 16px 0; border-color: #477390;">
                <p style="color: #22577A; font-size: 16px; font-weight: bold; margin-bottom: 4px;">Updated ${time}</p>
                <p style="color: #2F3E46; font-size: 15px; margin-bottom: 10px;">${date}</p>
                <div style="position: relative;">
                    <canvas id="${id}" width="250" height="100"></canvas>
                    <span style="position: absolute; top: 5px; right: 0; font-size: 11px; color: #666;">AQI US</span>
                </div>
                <button style="margin-top: 18px; background-color: #22577A; color: white; font-size: 16px; font-weight: 500; border: none; padding: 10px; width: 100%; border-radius: 12px;">
                    Click For More Information
                </button>
            </div>
        `;
    }

    // === Homagama ===
    const homagamaData = [30, 25, 60, 33, 38];
    const homagamaPopup = createPopup(
        'homagamaChart', 'Homagama,SriLanka', '😊', getAQIColor(40), 40, 'Good',
        '3 Hours ago', 'March 25, 2025 2:00 PM', homagamaData
    );
    L.marker([6.8441, 79.9655], { icon: createAQIMarkerIcon(40) })
        .addTo(colomboMap)
        .bindPopup(homagamaPopup)
        .on('popupopen', () => drawChart('homagamaChart', homagamaData));

    // === Moratuwa ===
    const moratuwaData = [45, 60, 78, 55, 50];
    const moratuwaPopup = createPopup(
        'moratuwaChart', 'Moratuwa,SriLanka', '😐', getAQIColor(78), 78, 'Moderate',
        '2 Hours ago', 'March 25, 2025 3:00 PM', moratuwaData
    );
    L.marker([6.8449, 79.9020], { icon: createAQIMarkerIcon(78) })
        .addTo(colomboMap)
        .bindPopup(moratuwaPopup)
        .on('popupopen', () => drawChart('moratuwaChart', moratuwaData));

    // === Colombo Central ===
    const colomboData = [100, 120, 150, 162, 140];
    const colomboPopup = createPopup(
        'colomboChart', 'Colombo Central,SriLanka', '😷', getAQIColor(162), 162, 'Unhealthy',
        '1 Hour ago', 'March 25, 2025 4:00 PM', colomboData
    );
    L.marker([6.9271, 79.8612], { icon: createAQIMarkerIcon(162) })
        .addTo(colomboMap)
        .bindPopup(colomboPopup)
        .on('popupopen', () => drawChart('colomboChart', colomboData));
});
</script>
@endsection
