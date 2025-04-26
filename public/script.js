window.onload = function() {
    fetchSensors();
  };

  function fetchSensors() {
    fetch('fetch_data.php?action=fetch')
      .then(res => res.json())
      .then(data => {
        const sensorList = document.getElementById('sensor-list');
        sensorList.innerHTML = '';

        data.forEach(sensor => {
          const row = document.createElement('div');
          row.className = 'sensor-row';

          // Button text changes based on status
          const buttonText = sensor.status === 'Active' ? 'Deactivate' : 'Activate';

          row.innerHTML = `
            <span class="city-name">${sensor.city}</span>
            <div>
              <span class="status ${sensor.status}">${sensor.status}</span>
              <button onclick="toggleStatus(${sensor.id})">${buttonText}</button>
              <button onclick="deleteSensor(${sensor.id})">Delete</button>
            </div>
          `;
          sensorList.appendChild(row);
        });
      })
      .catch(error => {
        console.error('Error fetching sensors:', error);
      });
  }

  function toggleStatus(id) {
    fetch(`fetch_data.php?action=toggle_status&id=${id}`, { method: 'POST' })
      .then(response => {
        if (response.ok) {
          fetchSensors(); // Refresh the sensor list
          // If the map has been initialized, refresh the map markers too
          if (typeof addSensorsToMap === 'function') {
            fetch('fetch_data.php?action=fetch')
              .then(res => res.json())
              .then(data => {
                // Clear existing markers and add updated ones
                if (window.sensorMarkers) {
                  window.sensorMarkers.forEach(marker => marker.remove());
                }
                window.sensorMarkers = [];
                addSensorsToMap(data);
              });
          }
        } else {
          throw new Error('Failed to toggle sensor status');
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  function deleteSensor(id) {
    if (confirm('Are you sure you want to delete this sensor?')) {
      fetch(`fetch_data.php?action=delete&id=${id}`, { method: 'POST' })
        .then(response => {
          if (response.ok) {
            fetchSensors(); // Refresh the sensor list
            // If the map has been initialized, refresh the map markers too
            if (typeof addSensorsToMap === 'function') {
              fetch('fetch_data.php?action=fetch')
                .then(res => res.json())
                .then(data => {
                  // Clear existing markers and add updated ones
                  if (window.sensorMarkers) {
                    window.sensorMarkers.forEach(marker => marker.remove());
                  }
                  window.sensorMarkers = [];
                  addSensorsToMap(data);
                });
            }
          } else {
            throw new Error('Failed to delete sensor');
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }
  }

  // Modal functionality
  const modal = document.getElementById('sensorModal');
  const addSensorBtn = document.getElementById('addSensorBtn');
  const closeBtn = document.getElementsByClassName('close')[0];
  const addSensorForm = document.getElementById('addSensorForm');

  // Open modal when "Add Sensor" button is clicked
  addSensorBtn.addEventListener('click', function() {
    modal.style.display = 'block';
  });

  // Close modal when close button is clicked
  closeBtn.addEventListener('click', function() {
    modal.style.display = 'none';
  });

  // Close modal when clicking outside of it
  window.addEventListener('click', function(event) {
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  });

  // Handle form submission
  addSensorForm.addEventListener('submit', function(event) {
    event.preventDefault();

    const city = document.getElementById('city').value;
    const aqiValue = document.getElementById('aqiValue').value;

    // Send data to the server
    fetch('add_sensor.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        city: city,
        aqiValue: aqiValue
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Reset form and close modal
        addSensorForm.reset();
        modal.style.display = 'none';

        // Add the new sensor to the list without refreshing the page
        const sensorList = document.getElementById('sensor-list');
        const row = document.createElement('div');
        row.className = 'sensor-row';

        row.innerHTML = `
          <span class="city-name">${data.sensor.city}</span>
          <div>
            <span class="status Active">Active</span>
            <button onclick="toggleStatus(${data.sensor.id})">Deactivate</button>
            <button onclick="deleteSensor(${data.sensor.id})">Delete</button>
          </div>
        `;
        sensorList.appendChild(row);

        // Refresh the map to show the new sensor
        fetch('fetch_data.php?action=fetch')
          .then(res => res.json())
          .then(data => {
            // Call the addSensorsToMap function from map.js
            if (typeof addSensorsToMap === 'function') {
              addSensorsToMap(data);
            }
          });

        // Show success message
        alert(`New sensor added for ${city} with AQI value: ${aqiValue}`);
      } else {
        // Show error message
        alert('Error adding sensor: ' + (data.error || 'Unknown error'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error adding sensor. Please try again.');
    });
  });
