<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | PureAir Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f8fa;
            margin: 0;
        }

        .sidebar {
            width: 210px;
            background-color: #22577A;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar .logo {
            padding: 18px 0;
            text-align: center;
        }

        .sidebar .nav-link {
            color: #fff;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            font-size: 14px;
            gap: 10px;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #FF7700;
            border-left: 4px solid #fff;
        }

        .sidebar .logout-btn {
            text-align: center;
            padding: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
        }

        .sidebar .logout-btn button {
            background-color: #fff;
            color: #22577A;
            font-weight: 500;
            padding: 8px 16px;
            font-size: 14px;
            border: none;
            border-radius: 8px;
            transition: background-color 0.2s;
        }

        .sidebar .logout-btn button:hover {
            background-color: #e4e4e4;
        }

        .main-content {
            margin-left: 210px;
            padding: 28px;
        }

        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .header-bar h3 {
            color: #22577A;
            font-weight: 600;
            font-size: 20px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div>
        <div class="logo">
            <img src="{{ asset('assets/logo3.png') }}" alt="Logo" style="height: 48px;">
        </div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>
        <a href="{{ route('admin.user.management') }}" class="nav-link {{ request()->routeIs('admin.user.management') ? 'active' : '' }}">
            <i class="bi bi-person"></i> Admin User Management
        </a>
        <a href="{{ route('admin.sensor.management') }}" class="nav-link {{ request()->routeIs('admin.sensor.management') ? 'active' : '' }}">
            <i class="bi bi-broadcast-pin"></i> Sensor Management
        </a>
        <a href="{{ route('admin.data.management') }}" class="nav-link {{ request()->routeIs('admin.data.management') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Data Management
        </a>
        <a href="{{ route('admin.alert') }}" class="nav-link {{ request()->routeIs('admin.alert') ? 'active' : '' }}">
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
