<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top" 
     style="font-family: 'Poppins', sans-serif; height: 80px; width: 1450px; border-radius: 20px; 
            margin: auto; z-index: 9999; left: 0; right: 0; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
    <div class="container-fluid px-4">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('assets/logo.png') }}" alt="PureAir Logo" style="height: 80px;">
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Links & User Greeting -->
        <div class="collapse navbar-collapse justify-content-between" style=" gap:20px;" id="navbarContent">
            <!-- Center Links -->
            <ul class="navbar-nav mx-auto gap-3" style="font-size:18px;">
    <li class="nav-item">
        <a class="nav-link fw-semibold nav-link-custom {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-semibold nav-link-custom {{ request()->is('air-quality') ? 'active' : '' }}" href="{{ url('/air-quality') }}">Air Quality</a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-semibold nav-link-custom {{ request()->is('reports') ? 'active' : '' }}" href="{{ url('/reports') }}">Reports</a>
    </li>
</ul>

            <!-- Hello User -->
            <span class="fw-semibold primary-blue" style="font-size:20px;">Hello User!</span>
        </div>
    </div>
</nav>

<style>
    .primary-blue {
        color: #22577A !important;
    }

    .accent-orange {
        color: #FF7700;
    }

    .nav-link-custom {
        color: #22577A;
        transition: color 0.2s ease-in-out;
    }

    .nav-link-custom:hover {
        color: #FF7700;
    }

    .nav-link-custom.active {
        color: #FF7700 !important;
    }

    body {
        padding-top: 100px; /* Space for fixed navbar */
    }
</style>
