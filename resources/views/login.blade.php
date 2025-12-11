<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="login-container">
        <div class="login-card">

            <div class="login-logo">
                <img src="{{ asset('img/logo.png') }}" alt="logo_principal">
            </div>

            <h1 class="login-title">AVENTONES</h1>

            <form action="{{ route('login.start') }}" method="POST">
                @csrf

                <label for="correo">EMAIL</label>
                <input type="email" id="correo" name="correo" required>

                <label for="contrasena">PASSWORD</label>
                <input type="password" id="contrasena" name="contrasena" required>

                <p class="register-link">
                    Not a user?
                    <a href="{{ route('registration') }}">Register now</a>
                </p>

                <p class="register-link">
                    Are you a driver?
                    <a href="{{ route('registerDriver.create') }}">Register as driver</a>
                </p>

                <button type="submit">LOGIN</button>
            </form>
        </div>
    </div>

</body>
</html>
