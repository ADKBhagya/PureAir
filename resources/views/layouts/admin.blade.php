<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | PureAir Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: #f8f9fa;
        }

        .sidebar {
            width: 220px;
            background-color: #22577A;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar .logo {
            padding: 20px;
            text-align: center;
        }

        .sidebar .nav-link {
            color: #fff;
            padding: 14px 20px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 5px solid transparent;
            margin-bottom: 8px; /* ✅ space between nav links */
        }

        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background-color: #FF7700;
            border-left: 5px solid #fff;
        }

        .sidebar .logout-btn {
            text-align: center;
            padding: 20px;
            border-top: 2px solid rgb(255, 255, 255); /* ✅ white line above logout */
        }

        .sidebar .logout-btn button {
            background-color: #fff;
            color: #22577A;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
        }

        .main-content {
            margin-left: 220px;
            padding: 30px;
        }

        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header-bar h3 {
            font-weight: 600;
            color: #22577A;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div>
        <div class="logo">
            <img src="{{ asset('assets/logo3.png') }}" alt="Logo" style="height: 55px;">
        </div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>
        <a href="{{ route('admin.user.management') }}" class="nav-link {{ request()->routeIs('admin.user.management') ? 'active' : '' }}">
    <i class="bi bi-person"></i> Admin User Management
</a>


<a href="{{ route('admin.sensor.management') }}" 
   class="nav-link {{ request()->routeIs('admin.sensor.management') ? 'active' : '' }}">
    <i class="bi bi-broadcast-pin"></i> Sensor Management
</a>

<a href="{{ route('admin.data.management') }}" class="nav-link {{ request()->routeIs('admin.data.management') ? 'active' : '' }}">
    <i class="bi bi-bar-chart-line"></i> Data Management
</a>

<a href="{{ route('admin.alert') }}" 
   class="nav-link {{ request()->routeIs('admin.alert') ? 'active' : '' }}">
   <i class="bi bi-info-circle"></i> Alert Configuration
</a>

    </div>
    <div class="logout-btn">
        <button>Log Out</button>
    </div>
</div>

<div class="main-content">
    @yield('content')
</div>

</body>
</html>
