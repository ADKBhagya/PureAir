@extends('layouts.admin')

@section('content')
<style>
  body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    background-color: #f5f6fa;
  }

  .container {
    display: flex;
    height: 100vh;
  }

  .sidebar {
    background-color: #22577A;
    width: 240px;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 20px 0;
    font-family: 'Poppins', sans-serif;
    
  }

  .sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 24px;
  }

  .nav-item {
    padding: 15px 30px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #ccc;
    cursor: pointer;
    text-decoration: none;
  }

  .nav-item.active {
    background-color: #ff8000;
    color: white;
  }

  .nav-item:hover {
    background-color: #ff8000;
    color: white;
  }

  .logout-btn {
    margin: 20px 30px;
    padding: 8px;
    background-color: white;
    color: #1e3d59;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
    font-family: 'Poppins', sans-serif;
  }

  .logout-btn:hover {
    background-color: #eee;
  }

  .main {
    flex: 1;
    padding: 40px 60px;
    color: #22577A;
    font-family: 'Poppins', sans-serif;
  }

  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: 'Poppins', sans-serif;
  }

  .form-box {
    background: #f0f2f5;
    border-radius: 16px;
    padding: 40px;
    max-width: 600px;
    margin-top: 50px;
  }

  .form-box h3 {
    font-size: 22px;
    margin-bottom: 25px;
    color: #22577A;
    font-weight: bold;
    font-family: 'Poppins', sans-serif;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-group label {
    display: block;
    font-weight: 600;
    color: #22577A;
    margin-bottom: 8px;
  }

  .form-group input,
  .form-group select {
    width: 100%;
    padding: 12px;
    border: 2px solid #22577A;
    border-radius: 8px;
    font-size: 16px;
    background-color: #fff;
  }

  .toggle-container {
    display: flex;
    align-items: center;
    margin-top: 20px;
    font-weight: 600;
    color: #22577A;
  }

  .switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 24px;
    margin-left: 12px;
  }

  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: 0.4s;
    border-radius: 24px;
    border: 2px solid #22577A;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: #22577A;
    transition: 0.4s;
    border-radius: 50%;
  }

  input:checked + .slider {
    background-color:rgb(142, 159, 170);
  }

  input:checked + .slider:before {
    transform: translateX(24px);
  }
</style>

<div class="container">

  <!-- Sidebar -->
  <div class="sidebar">
  <div>
  <div style="text-align: center; margin-bottom: 20px;">
  <img src="{{ asset('assets/logo2.png') }}" alt="Pureair Logo" style="width: 122px; height: 105px;">
</div>


    <a class="nav-item" href="#">
      <img src="{{ asset('assets/Grid.png') }}" alt="Dashboard" class="nav-icon"> Dashboard
    </a>

    <a class="nav-item" href="#">
      <img src="{{ asset('assets/User.png') }}" alt="Admin User Management" class="nav-icon"> Admin User Management
    </a>

    <a class="nav-item" href="#">
      <img src="{{ asset('assets/image 27.png') }}" alt="Sensor Management" class="nav-icon"> Sensor Management
    </a>

    <a class="nav-item active" href="#">
      <img src="{{ asset('assets/image 28.png') }}" alt="Data Management" class="nav-icon"> Data Management
    </a>

    <a class="nav-item" href="#">
      <img src="{{ asset('assets/Alert circle.png') }}" alt="Alert Configuration" class="nav-icon"> Alert Configuration
    </a>
  </div>

  <button class="logout-btn">Log Out</button>
  </div>




  <!-- Main Content -->
  <div class="main">
    <div class="header">
  <h2 style="color: #22577A;">Data Management</h2>
  <div style="display: flex; align-items: center; font-weight: 500;">
    <span style="margin-right: 8px; color: #22577A;">Hello, User!</span>
    <img src="{{ asset('assets/Group 402.png') }}" alt="User Icon" style="width: 40px; height: 40px;" />
  </div>
</div>


    <div class="form-box">
    <h3 style="text-align: center; color: #22577A;">Configure Simulations</h3>
      
    <form>
        <div class="form-group">
          <label for="frequency">Frequency of data generation:</label>
          <input type="text" id="frequency" placeholder="e.g., every 10 seconds" />
        </div>

        <div class="form-group">
          <label for="baseline">Baseline AQI Levels:</label>
          <input type="text" id="baseline" placeholder="e.g., 75" />
        </div>

        <div class="form-group">
          <label for="pattern">Variation Pattern:</label>
          <select id="pattern">
            <option value="">Select pattern</option>
            <option value="linear">Linear</option>
            <option value="random">Random</option>
            <option value="wave">Wave</option>
          </select>
        </div>

        <div class="toggle-container">
          Simulation status: <span id="status-label" style="margin-left: 5px;">stopped</span>
          <label class="switch">
            <input type="checkbox" id="status-toggle" />
            <span class="slider"></span>
          </label>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
  const toggle = document.getElementById('status-toggle');
  const statusLabel = document.getElementById('status-label');

  toggle.addEventListener('change', function () {
    statusLabel.textContent = this.checked ? 'running' : 'stopped';
  });
</script>
@endsection
