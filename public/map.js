// Initialize the AQI map for Colombo FECT Station
document.addEventListener('DOMContentLoaded', function() {
    // Colombo FECT Station coordinates
    const colomboFectCoords = [6.914, 79.8778]; // Coordinates for FECT station in Colombo

    // Initialize the Leaflet map with Colombo FECT as center
    const map = L.map('aqi-map').setView(colomboFectCoords, 12); // Zoomed in to show the station better

    // Add OpenStreetMap base layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add AQI Legend to the map
    const legend = L.control({position: 'bottomright'});
    legend.onAdd = function(map) {
        const div = L.DomUtil.create('div', 'info legend');
        div.innerHTML = `
            <div style="background: white; padding: 10px; border-radius: 5px;">
                <h4 style="margin: 0 0 5px 0;">Air Quality Index</h4>
                <div><span style="color: #00e400;">■</span> Good (0-50)</div>
                <div><span style="color: #ffff00;">■</span> Moderate (51-100)</div>
                <div><span style="color: #ff7e00;">■</span> Unhealthy for Sensitive Groups (101-150)</div>
                <div><span style="color: #ff0000;">■</span> Unhealthy (151-200)</div>
                <div><span style="color: #99004c;">■</span> Very Unhealthy (201-300)</div>
                <div><span style="color: #7e0023;">■</span> Hazardous (300+)</div>
            </div>
        `;
        return div;
    };
    legend.addTo(map);

    // Hardcoded data from the Colombo FECT station (can be updated with real API if you have access)
    const fectStationData = {
        aqi: 58, // This value will need updating regularly in a production environment
        station: "Colombo FECT, Faculty of Engineering - University of Colombo",
        lat: 6.914,
        lng: 79.8778,
        lastUpdated: "2025-04-24 09:00:00" // This should be dynamic in production
    };

    // Determine AQI class for styling
    let aqiClass = 'aqi-good';
    let aqiColor = '#00e400';

    if (fectStationData.aqi > 300) {
        aqiClass = 'aqi-hazardous';
        aqiColor = '#7e0023';
    } else if (fectStationData.aqi > 200) {
        aqiClass = 'aqi-very-unhealthy';
        aqiColor = '#99004c';
    } else if (fectStationData.aqi > 150) {
        aqiClass = 'aqi-unhealthy';
        aqiColor = '#ff0000';
    } else if (fectStationData.aqi > 100) {
        aqiClass = 'aqi-unhealthy-sensitive';
        aqiColor = '#ff7e00';
    } else if (fectStationData.aqi > 50) {
        aqiClass = 'aqi-moderate';
        aqiColor = '#ffff00';
    }

    // Create a custom icon for the AQI marker
    const aqiIcon = L.divIcon({
        className: 'aqi-marker ' + aqiClass,
        html: `<div style="background-color: ${aqiColor}; color: white; padding: 5px; border-radius: 50%; width: 30px; height: 30px; text-align: center; line-height: 30px; font-weight: bold;">${fectStationData.aqi}</div>`,
        iconSize: [40, 40],
        iconAnchor: [20, 20]
    });

    // Add marker for Colombo FECT AQI station
    const marker = L.marker([fectStationData.lat, fectStationData.lng], {icon: aqiIcon}).addTo(map);

    // Add popup with station information
    marker.bindPopup(`
        <strong>${fectStationData.station}</strong><br>
        AQI: <span class="${aqiClass}" style="font-weight: bold; color: ${aqiColor};">${fectStationData.aqi}</span><br>
        Last Updated: ${fectStationData.lastUpdated}
    `).openPopup();

    // Also add a circle around the station to indicate the area of measurement
    L.circle([fectStationData.lat, fectStationData.lng], {
        color: aqiColor,
        fillColor: aqiColor,
        fillOpacity: 0.2,
        radius: 1000 // 1km radius
    }).addTo(map);

    // Initialize array to track sensor markers
    window.sensorMarkers = [];

    // Make the addSensorsToMap function globally accessible
    window.addSensorsToMap = function(sensors) {
        // Clear existing markers
        if (window.sensorMarkers && window.sensorMarkers.length > 0) {
            window.sensorMarkers.forEach(marker => marker.remove());
        }
        window.sensorMarkers = [];

        sensors.forEach(sensor => {
            if (sensor.lat && sensor.lng) {
                // Create a marker for each sensor
                const sensorIcon = L.divIcon({
                    className: 'sensor-marker',
                    html: `<div style="background-color: ${sensor.status === 'Active' ? '#4CAF50' : '#e74c3c'}; color: white; padding: 5px; border-radius: 50%; width: 25px; height: 25px; text-align: center; line-height: 25px;"><i class="fa fa-rss"></i></div>`,
                    iconSize: [35, 35],
                    iconAnchor: [17.5, 17.5]
                });

                const marker = L.marker([sensor.lat, sensor.lng], {icon: sensorIcon}).addTo(map);
                marker.bindPopup(`
                    <strong>${sensor.city}</strong><br>
                    Status: <span style="color: ${sensor.status === 'Active' ? '#4CAF50' : '#e74c3c'};">${sensor.status}</span>
                `);

                // Add to our markers array for later reference
                window.sensorMarkers.push(marker);
            }
        });
    };

    // Fetch sensors and add them to the map
    fetch('fetch_data.php?action=fetch')
        .then(res => res.json())
        .then(data => {
            // Add sensors to the map
            window.addSensorsToMap(data);
        })
        .catch(error => {
            console.error('Error fetching sensors:', error);
        });
});
