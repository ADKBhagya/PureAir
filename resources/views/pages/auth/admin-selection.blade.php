@extends('layouts.clean')

@section('title', 'Admin Role Selection')

@section('content')
<style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #22577A 0%, #1d4f6d 100%);
    }

    .admin-select-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px 20px;
    }

    .admin-select-container h2 {
        color: #fff;
        font-size: 18px;
        margin-bottom: 45px;
        text-align: center;
    }

    .card-wrapper {
        display: flex;
        gap: 50px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .role-card {
        background-color: #fff;
        color: #22577A;
        width: 250px;
        height: 280px;
        border-radius: 20px;
        padding: 25px 20px;
        text-align: center;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .role-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.18);
    }

    .role-card h3 {
        font-size: 20px;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .icon {
        height: 70px;
        width: 70px;
        object-fit: contain;
        margin: 0 auto 20px;
    }

    .login-btn {
        background-color: #FF7700;
        color: #fff;
        border: none;
        width: 100%;

        border-radius: 12px;
        font-size: 17px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        height:40px;
    }

    .login-btn:hover {
        background-color: #e56700;
        box-shadow: 0 0 10px #ff7700b0;
    }

    .pureair-logo {
        margin-top:-10px;
        height: 110px;
        width: auto;
       
    }

    @media (max-width: 768px) {
        .admin-select-container h2 {
            font-size: 24px;
        }

        .role-card {
            width: 90%;
            max-width: 350px;
        }
    }
</style>

<div class="admin-select-container">
    <img src="{{ asset('assets/logo3.png') }}" alt="PureAir Logo" class="pureair-logo" style="width:180px;"/>
    <h2>Welcome to Admin Management</h2>

    <div class="card-wrapper">
        <div class="role-card">
            <h3>Web Master</h3>
            <img src="{{ asset('assets/user-icon.png.png') }}" class="icon" alt="Administrator">
            <a href="{{ route('webmaster.login') }}">
            <button class="login-btn">Log In</button>
            </a>
        </div>

        <div class="role-card">
            <h3>Monitoring Admin</h3>
            <img src="{{ asset('assets/user-icon.png.png') }}" class="icon" alt="Admin">
            <a href="{{ route('admin.login') }}">
                <button class="login-btn">Log In</button>
            </a>
        </div>
    </div>
</div>
@endsection
