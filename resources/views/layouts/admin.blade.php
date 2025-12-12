<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

<header class="header">
    <div class="container header-content">
        <div class="logo">
            <i class="fas fa-car logo-icon"></i>
            <h1 class="logo-text">AVENTONES - Admin</h1>
        </div>

        <div class="admin-info">
            Bienvenido, {{ session('user_nombre') }}
        </div>

        <nav class="nav-menu">
            <a href="{{ route('admin.register.create') }}" class="btn btn-primary">Create Admin</a>
            <a href="{{ route('configurations') }}" class="btn btn-outline">Configuración</a>
            <a href="{{ route('logout') }}" class="btn btn-danger">Cerrar Sesión</a>
        </nav>
    </div>
</header>

@yield('content')

<footer class="footer">
    <div class="container">
        <p>&copy; 2025 AVENTONES. Panel de Administración</p>
    </div>
</footer>

</body>
</html>