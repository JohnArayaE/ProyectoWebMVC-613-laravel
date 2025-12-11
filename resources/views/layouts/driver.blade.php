<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Driver Panel')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/vehicles.css') }}">
    <script src="{{ asset('js/vehicles.js') }}" defer></script>
</head>

<body class="veh-page">

    <!-- ========= HEADER COMPLETO ARREGLADO ========= -->
    <header class="veh-topbar">
        <div class="veh-brand">
            <img class="veh-logo" src="{{ asset('img/logo.png') }}" alt="Aventones logo">
            <h1>@yield('header-title', 'My Vehicles')</h1>
        </div>

        <nav class="main-nav">
            <ul class="nav-links">
                <li><a href="{{ route('driver.vehicles') }}" class="@yield('nav-home')">Home</a></li>
                <li><a href="#">Rides</a></li>
                <li><a href="#">Bookings</a></li>
            </ul>
        </nav>

        <div class="right-box">

            <a href="#" class="btn neon">New Vehicle</a>

            <div class="profile-menu">

                @if(Auth::check())
                    <img src="{{ Auth::user()->foto_ruta ? asset(Auth::user()->foto_ruta) : asset('img/logo.png') }}"
                        alt="User Icon"
                        class="avatar"
                        id="avatarBtn"
                        onerror="this.src='{{ asset('img/logo.png') }}'">

                    <ul class="dropdown" id="profileDropdown">
                        <li><a href="{{ route('driver.vehicles') }}">Home</a></li>
                        <li><a href="#">Rides</a></li>
                        <li><a href="#">Bookings</a></li>
                        <li><a href="#">Configuration</a></li>
                        <li><a href="{{ route('logout') }}" class="logout-btn">Logout</a></li>
                    </ul>

                @else
                    <img src="{{ asset('img/logo.png') }}" 
                        alt="Guest Icon" 
                        class="avatar">
                @endif

            </div>
        </div>
    </header>

    <!-- CONTENIDO -->
    @yield('content')

    <!-- FOOTER -->
    <footer class="veh-footer">
        <div class="footer-links">
            <a href="{{ route('driver.vehicles') }}">Home</a> |
            <a href="#">Rides</a> |
            <a href="#">Bookings</a>
        </div>
        <p>&copy; Aventones.com</p>
    </footer>

</body>
</html>
