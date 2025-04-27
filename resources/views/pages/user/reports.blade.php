@extends('layouts.user')

@section('title', 'Historical Data')

@section('content')
<style>
    body {
        background: #ffffff;
        font-family: 'Poppins', sans-serif;
    }
    .section-title {
        color: #22577A;
        font-weight: 800;
        font-size: 26px;
        margin-bottom: 10px;
    }
    .section-subtitle {
        color: #22577A;
        font-weight: 500;
        font-size: 16px;
        margin-top: -40px;
    }
    .content-card {
        background: rgba(228, 228, 228, 0.45);
        border-radius: 16px;
        padding: 25px;
        margin-top: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        text-align: center;
    }
    .content-title {
        font-size: 20px;
        font-weight: 600;
        color: #22577A;
        margin-bottom: 10px;
    }
    .content-text {
        font-size: 15px;
        color: #2F3E46;
        line-height: 1.7;
    }
    .what-we-offer {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    .offer-item {
        background: white;
        border: 1px solid #ccc;
        border-radius: 12px;
        padding: 15px 20px;
        font-size: 14px;
        font-weight: 500;
        color: #22577A;
        min-width: 180px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .team-section {
        margin-top: 40px;
        text-align: center;
    }
    .team-members {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    .member {
        background: white;
        padding: 15px;
        border-radius: 16px;
        width: 150px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .member img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #22577A;
        margin-bottom: 8px;
    }
    .member div {
        font-weight: 600;
        font-size: 13px;
        color: #2F3E46;
    }
    .member small {
        color: #6c757d;
        font-size: 12px;
    }
</style>

<div class="container mt-5 mb-5">
    <div class="text-center">
        <p class="section-subtitle">Cleaner Air, Healthier Lives 🌍</p>
    </div>

    <div class="content-card">
        <div class="content-title">Our Mission</div>
        <p class="content-text">
            To empower communities with real-time air quality data and drive awareness towards healthier, sustainable environments.
        </p>
    </div>

    <div class="content-card">
        <div class="content-title">Our Vision</div>
        <p class="content-text">
            A future where everyone can breathe freely, supported by technology and collective action.
        </p>
    </div>

    <div class="content-card">
        <div class="content-title">What We Offer</div>
        <div class="what-we-offer">
            <div class="offer-item">🌱 Live Air Quality Maps</div>
            <div class="offer-item">🌱 Historical AQI Reports</div>
            <div class="offer-item">🌱 Health Tips Based on AQI</div>
            <div class="offer-item">🌱 Dynamic Pollution Alerts</div>
        </div>
    </div>

    <div class="team-section">
        <h4 class="section-title mt-5">Meet Our Team</h4>
        <div class="team-members">
            <div class="member">
                <img src="{{ asset('assets/profile1.jpg') }}" alt="Team Member">
                <div>Kaushani Bhagya</div>
                <small>Project Lead</small>
            </div>
            <div class="member">
                <img src="{{ asset('assets/profile2.jpg') }}" alt="Team Member">
                <div>Pramith Charuka</div>
                
            </div>
            <div class="member">
                <img src="{{ asset('assets/profile2.jpg') }}" alt="Team Member">
                <div>Malith Adikaram</div>
               
            </div>
            <div class="member">
                <img src="{{ asset('assets/profile1.jpg') }}" alt="Team Member">
                <div>Chamodya De Silva</div>
                
            </div>
            <div class="member">
                <img src="{{ asset('assets/profile1.jpg') }}" alt="Team Member">
                <div>Yenuli Ahasna</div>
               
            </div>
            <div class="member">
                <img src="{{ asset('assets/profile1.jpg') }}" alt="Team Member">
                <div>Amandi Jayasinghe</div>
                
            </div>
        </div>
    </div>
</div>
@endsection