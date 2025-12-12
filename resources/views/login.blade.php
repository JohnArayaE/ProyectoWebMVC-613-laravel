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

            {{-- FORMULARIO LOGIN NORMAL --}}
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


            {{-- ENLACE PARA LOGIN SIN CONTRASEÑA --}}
            <p class="register-link" style="margin-top: 20px;">
                Prefer not to use your password?  
                <a href="#" onclick="showMagicForm(); return false;">Send me a login link</a>
            </p>

            {{-- FORMULARIO MAGIC LINK OCULTO --}}
            <form id="magicForm" action="{{ route('login.magic.send') }}" 
                  method="POST" 
                  style="display:none; margin-top:15px;">
                @csrf

                <label for="correo_magic">EMAIL</label>
                <input type="email" id="correo_magic" name="correo" required>

                <button type="submit">Send Magic Link</button>
            </form>

        </div>
    </div>

    <script>
        function showMagicForm() {
            document.getElementById('magicForm').style.display = 'block';
        }
    </script>

</body>
</html>