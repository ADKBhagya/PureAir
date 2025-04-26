@extends('layouts.user')

@section('title', 'Home')

@section('content')
<style>
    .primary-blue { color: #22577A; }
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
    }
    .health-icon img {
        width: 30px;
        height: 30px;
    }
    .health-text {
        font-size: 20px;
        font-weight: 400;
        color: #2F3E46;
    }
</style>

<div class="container mt-5 mb-2">

<div class="text-center mb-2" >
    <h3 class="fw-bold primary-blue" style="margin-bottom: 15px; font-size: 22px;">
        Real-Time Air Quality Monitoring 🌍✨
    </h3>

    <p class="text-secondary" style=" line-height: 28px; font-size: 16px; ">
        Stay informed with real-time air quality updates, monitor AQI levels with precision,<br>
        Make informed decisions to safeguard your health, adapt to changing air conditions,<br>
        Empower communities, embrace sustainability, reduce exposure, foster awareness, and breathe confidently.
    </p>
    <img src="{{ asset('assets/image.png') }}" alt="Icons" style="height: 220px; width: 600px; padding-bottom:5px " class="img-fluid mt-4" />
</div>


    <div class="row" style="margin-top:50px;">
        <!-- Left Column -->
        <div class="col-md-5 mb-4">
            <h5 class="primary-blue fw-semibold mb-4 text-center" style="font-size: 22px;">Main Sources of Air Pollution</h5>
            <div class="bg-card p-4 d-flex flex-column justify-content-between" style="border-radius: 16px; min-height: 705px;">
             @php
                $pollutionSources = [
                    ['icon' => 'factory.png', 'title' => 'Industrial Emissions', 'desc' => 'Factories release pollutants like sulfur dioxide and nitrogen oxides.'],
                    ['icon' => 'car.png', 'title' => 'Vehicle Exhaust', 'desc' => 'Fumes from vehicles contribute heavily to urban air pollution.'],
                    ['icon' => 'burning.png', 'title' => 'Open Burning', 'desc' => 'Burning of waste and biomass releases dangerous particles.'],
                    ['icon' => 'dust.png', 'title' => 'Construction Dust', 'desc' => 'Unregulated construction causes dust and particulate spread.'],
                    ['icon' => 'garbage.png', 'title' => 'Garbage Decomposition', 'desc' => 'Improper waste dumping releases methane and harmful gases, worsening air quality.'],

                ];
            @endphp


                @foreach ($pollutionSources as $source)
                    <div class="d-flex align-items-start mb-4" style="gap: 20px;">
                        <div style="flex-shrink: 0;">
                            <img src="{{ asset('assets/' . $source['icon']) }}"
                                 alt="{{ $source['title'] }}"
                                 style="height: 80px; width: 80px; object-fit: cover; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                        </div>
                        <div>
                            <h6 class="primary-blue fw-semibold mb-2" style="font-size: 18px;">{{ $source['title'] }}</h6>
                            <p class="text-muted mb-0" style="font-size: 15px; line-height: 1.6;">{{ $source['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-7">
            <!-- Infographic Section -->
            <div class=" px-4 py-4 mb-4">
                <h5 class="primary-blue fw-semibold mb-4 text-center" style="font-size: 22px;">Why Air Quality Matters</h5>
                <div class="row text-center">
                    @php
                        $infographics = [
                            ['img' => 'eco.png', 'text' => 'Supports Breathing'],
                            ['img' => 'tree.png', 'text' => 'Protects Environment'],
                            ['img' => 'health.png', 'text' => 'Boosts Overall Health'],
                        ];
                    @endphp
                    @foreach($infographics as $info)
                        <div class="col-md-4">
                            <div style="background-color: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); font-size: 15px;">
                                <img src="{{ asset('assets/' . $info['img']) }}" alt="Icon" style="height: 60px; border-radius: 16px;">
                                <p class="text-muted mt-3">{{ $info['text'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Health Tips Section -->
            <div class="bg-card px-4 py-4 mb-4 health-card">
                <h5 class="primary-blue fw-semibold mb-4 text-center" style="font-size: 22px;">Health Tips</h5>
                <div class="row">
                    @php
                        $tips = [
                            ['icon' => '6.png', 'text' => 'Avoid outdoor exercises'],
                            ['icon' => '7.png', 'text' => 'Close windows to avoid dirty air'],
                            ['icon' => '8.png', 'text' => 'Wear a mask outdoors'],
                            ['icon' => '9.png', 'text' => 'Use air purifiers at home'],
                            ['icon' => '10.png', 'text' => 'Stay hydrated regularly'],
                            ['icon' => '11.png', 'text' => 'Keep your home ventilated'],
                        ];
                    @endphp
                    @foreach(array_chunk($tips, 3) as $tipGroup)
                        <div class="col-md-6">
                            @foreach($tipGroup as $tip)
                                <div class="d-flex align-items-center mb-4">
                                    <div class="health-icon">
                                        <img src="{{ asset('assets/' . $tip['icon']) }}" alt="icon">
                                    </div>
                                    <p class="text-muted mb-0">{{ $tip['text'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Facts Slider -->
            <div class="bg-card p-3 mb-5" style="border-radius: 14px;">
                <h5 class="primary-blue fw-semibold mb-3 text-center" style="font-size: 20px;">📌 Did You Know?</h5>
                <div id="factSlider" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner text-center mt-2">
                        <div class="carousel-item active">
                            <p class="text-muted">Air pollution causes 1 in 8 deaths globally.</p>
                        </div>
                        <div class="carousel-item">
                            <p class="text-muted">Children are more vulnerable to air pollution.</p>
                        </div>
                        <div class="carousel-item">
                            <p class="text-muted">Trees can reduce urban air pollution by up to 24%.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const factSlider = document.querySelector('#factSlider');
    if (factSlider) {
        new bootstrap.Carousel(factSlider, {
            interval: 2000,
            ride: 'carousel'
        });
    }
</script>
@endsection
