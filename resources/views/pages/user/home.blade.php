@extends('layouts.user')

@section('title', 'Home')

@section('content')
<style>
    .primary-blue { color: #22577A; }
    .accent-orange { color: #FF7700; }

    .bg-card {
        background-color: rgba(228, 228, 228, 0.45);
        border-radius: 10px;
    }

    .health-card {
        border-radius: 20px;
    }

    .health-icon {
        background: linear-gradient(180deg, #ffffff 0%, #22577A 49%);
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 15px;
        margin-left: 130px;
    }

    .health-icon img {
        width: 30px;
        height: 30px;
    }

    .health-text {
        font-size: 20px;
        color: #22577A;
        font-weight: 400;
    }

    .pollution-img {
        width: 240px;
        height: 200px;
        border-radius: 8px;
        object-fit: cover;
        opacity: 0;
        transform: scale(0.95);
        animation: fadeZoomIn 0.8s ease forwards;
        transition: transform 0.4s ease;
    }

    .wide-img {
        width: 488px;
    }

    .pollution-img:hover {
        transform: scale(1.03);
    }

    @keyframes fadeZoomIn {
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    #leafletMap {
        height: 374px;
        width: 100%;
        border-radius: 10px;
        border: 1px solid #ccc;
        z-index: 1;
    }
</style>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h3 class="fw-bold primary-blue" style="margin-bottom:20px; padding-top:40px;">
            Real-Time Air Quality Monitoring 🌍✨
        </h3>
        <p class="text-secondary" style="font-size: 17.6px; line-height: 33px;">
        Stay informed with real-time air quality updates, monitor AQI levels with precision,  
and gain valuable insights into pollution trends.<br> Make informed decisions to safeguard your health,  
adapt to changing air conditions, and create a cleaner, healthier environment every day.<br>  
Empower communities, embrace sustainability, reduce exposure, foster awareness, and breathe confidently.
        </p>
        <img src="{{ asset('assets/image.png') }}" alt="Icons" style="height: 250px; width: 630px; margin-bottom:20px;" class="img-fluid mt-4" />
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-5">
            <h5 class="primary-blue fw-semibold mb-3" style="font-size:30px; padding-left:60px;">What’s Polluting Our Air?</h5>
            <div class="bg-card p-3">
                <div class="d-flex gap-3 mb-3">
                    <img src="{{ asset('assets/1.jpg') }}" class="pollution-img" alt="pollution" />
                    <img src="{{ asset('assets/2.jpg') }}" class="pollution-img" alt="pollution" />
                </div>
                <div class="mb-3">
                    <img src="{{ asset('assets/3.jpg') }}" class="pollution-img wide-img" alt="pollution" />
                </div>
                <div class="d-flex gap-3">
                    <img src="{{ asset('assets/4.jpg') }}" class="pollution-img" alt="pollution" />
                    <img src="{{ asset('assets/5.jpg') }}" class="pollution-img" alt="pollution" />
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-7">
            <div class="bg-card px-4 py-4 mb-4 health-card">
                <h5 class="primary-blue fw-semibold mb-3" style="font-size:26px; margin-left:240px;">Health Tips</h5>

                <div class="d-flex align-items-center mb-3">
                    <div class="health-icon">
                        <img src="{{ asset('assets/6.png') }}" alt="icon">
                    </div>
                    <p class="health-text mb-0">Avoid outdoor exercises</p>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <div class="health-icon">
                        <img src="{{ asset('assets/7.png') }}" alt="icon">
                    </div>
                    <p class="health-text mb-0">Close windows for avoid dirty air</p>
                </div>

                <div class="d-flex align-items-center">
                    <div class="health-icon">
                        <img src="{{ asset('assets/8.png') }}" alt="icon">
                    </div>
                    <p class="health-text mb-0">Wear a mask outdoor</p>
                </div>
            </div>

            <div class="text-center">
                <p class="text-muted mb-3">Click to view AQI in Colombo cities</p>
                <div id="leafletMap"></div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const map = L.map('leafletMap').setView([6.9271, 79.8612], 11); // Colombo

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([6.9271, 79.8612]).addTo(map)
            .bindPopup('<b>Colombo Central</b><br>AQI: 42 - Good')
            .openPopup();

         L.marker([6.8449, 79.9020]).addTo(map)
            .bindPopup('<b>Moratuwa</b><br>AQI: 78 - Moderate')
            .openPopup();

            L.marker([6.8441, 79.9655]).addTo(map)
            .bindPopup('<b>Homagama</b><br>AQI: 32 - Good')
            .openPopup();
    });
</script>
@endsection