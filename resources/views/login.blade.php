<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    {{-- CSS del login, debe estar en public/css/login.css --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    {{-- Mensajes flash de éxito y error (los veremos luego desde el controlador) --}}
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                {{-- Logo, debe estar en public/img/logo.png --}}
                <img src="{{ asset('img/logo.png') }}" alt="logo_principal">
            </div>

            <h1 class="login-title">AVENTONES</h1>

            {{-- De momento solo queremos ver la página, así que el action va temporalmente a "#" --}}
            {{-- Más adelante lo cambiaremos a route('login.start') cuando creemos la ruta y el controlador --}}
            <form action="#" method="POST">
                @csrf

                <label for="correo">EMAIL</label>
                <input type="email" id="correo" name="correo" required>

                <label for="contrasena">PASSWORD</label>
                <input type="password" id="contrasena" name="contrasena" required>

                <p class="register-link">
                    Not a user?
                    <a href="#">Register now</a>
                    {{-- Luego cambiaremos este href a la ruta correcta, por ejemplo route('register') --}}
                </p>

                <p class="register-link">
                    Are you a driver?
                    <a href="#">Register as driver</a>
                    {{-- Luego cambiaremos este href a la ruta correcta, por ejemplo route('driver.register') --}}
                </p>

                <button type="submit">LOGIN</button>
            </form>
        </div>
    </div>

</body>
</html>
