@extends('layouts.clean')

@section('title', 'Web Master Login')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #22577A 0%, #1d4f6d 100%);
        font-family: 'Poppins', sans-serif;
    }

    .login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 10px;
    }

    .login-card {
        background-color: #fff;
        padding: 30px 25px;
        border-radius: 16px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        max-width: 360px;
        width: 100%;
    }

    .login-card h3 {
        color: #22577A;
        margin-bottom: 18px;
        font-size: 18px;
        text-align: center;
    }

    .form-control {
        border-radius: 8px;
        font-size: 13px;
        padding: 8px 10px;
    }

    .btn-login {
        background-color: #FF7700;
        color: white;
        font-weight: 500;
        font-size: 14px;
        border: none;
        border-radius: 8px;
        padding: 9px;
        width: 100%;
        margin-top: 12px;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        background-color: #e56700;
        box-shadow: 0 0 8px rgba(255, 119, 0, 0.4);
    }

    label {
        font-size: 13px;
        color: #2F3E46;
        margin-bottom: 4px;
    }

    .form-check-input {
        border: 1.5px solid #22577A;
        border-radius: 4px;
    }

    .form-check-label {
        color: #22577A;
        font-size: 13px;
    }

    .forgot-password {
        font-size: 12px;
        color: #999;
        margin-top: 6px;
        display: block;
        text-align: right;
        text-decoration: none;
    }

    .forgot-password:hover {
        text-decoration: underline;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">
        <h3>Web Master Login</h3>
        <form action="{{ route('webmaster.login.submit') }}" method="POST">
            @csrf
            <div class="mb-2">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required>
            </div>
            <div class="mb-2">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <div class="form-check text-start mb-2">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <a class="forgot-password" href="#">Forgot Password?</a>
            <button type="submit" class="btn btn-login">Log In</button>
        </form>
    </div>
</div>
@endsection
