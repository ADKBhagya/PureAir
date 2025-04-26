// Fetch data from backend
fetch('fetch_data.php')
  .then(response => response.json())
  .then(data => {
    const tableBody = document.getElementById('status-table-body');
    tableBody.innerHTML = '';

    data.forEach(row => {
      const tr = document.createElement('tr');

      const city = document.createElement('td');
      city.textContent = row.city;

      const condition = document.createElement('td');
      const conditionSpan = document.createElement('span');
      conditionSpan.textContent = row.condition;
      conditionSpan.className = row.condition === 'Good' ? 'condition-good' : 'condition-moderate';
      condition.appendChild(conditionSpan);

      const aqi = document.createElement('td');
      aqi.textContent = row.aqi;

      tr.appendChild(city);
      tr.appendChild(condition);
      tr.appendChild(aqi);

      tableBody.appendChild(tr);
    });
  });

  // Toggle the visibility of the detailed report section
document.querySelector('.see-more').addEventListener('click', function() {
    const detailedReport = document.getElementById('detailedReport');
    // Toggle the display property of the report
    if (detailedReport.style.display === 'none') {
      detailedReport.style.display = 'block';  // Show the report
      this.textContent = 'See less';           // Change button text
    } else {
      detailedReport.style.display = 'none';   // Hide the report
      this.textContent = 'See more';           // Change button text back
    }
  });
  