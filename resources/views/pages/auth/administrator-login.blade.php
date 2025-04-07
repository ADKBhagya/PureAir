<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administrator Login | PureAir</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #22577A;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            color: #22577A;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .login-container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        .login-btn {
            background-color: #FF7700;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 10px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
        }

        .login-btn:hover {
            background-color: #e56700;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Administrator Login</h2>
        <form action="{{ route('administrator.login.submit') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit" class="login-btn">Log In</button>
        </form>
    </div>
</body>
</html>
