<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Driver Panel')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/vehicles.css') }}">
    <script src="{{ asset('js/vehicles.js') }}" defer></script>

    @yield('css')
</head>

<body class="veh-page">

    <!-- ========= HEADER ========= -->
    <header class="veh-topbar">
        <div class="veh-brand">
            <img class="veh-logo" src="{{ asset('img/logo.png') }}" alt="Aventones logo">
            <h1>@yield('header-title', 'My Vehicles')</h1>
        </div>

        <nav class="main-nav">
            <ul class="nav-links">
                <li><a href="{{ route('driver.vehicles') }}" class="@yield('nav-home')">Home</a></li>
                <li><a href="{{ route('driver.rides') }}" class="@yield('nav-rides')">Rides</a></li>
                <li><a href="{{ route('bookings') }}" class="@yield('nav-bookings')">Bookings</a></li>
            </ul>
        </nav>

        <div class="right-box">

            {{-- BOTÓN DINÁMICO (Vehicle o Ride) --}}
            @yield('top-action')

            {{-- PROFILE MENU --}}
            <div class="profile-menu">

                <img src="{{ asset(session('user_foto', 'img/logo.png')) }}"
                    alt="User Icon"
                    class="avatar"
                    id="avatarBtn"
                    onerror="this.src='{{ asset('img/logo.png') }}'">

                <ul class="dropdown" id="profileDropdown">
                    <li><a href="{{ route('driver.vehicles') }}" class="dropdown-link">My Vehicles</a></li>
                    <li><a href="{{ route('configurations') }}" class="dropdown-link">Configuration</a></li>
                    <li><a href="{{ route('logout') }}" class="logout-btn">Logout</a></li>
                </ul>

            </div>
        </div>
    </header>

    <!-- CONTENIDO -->
    @yield('content')

    <!-- FOOTER -->
    <footer class="veh-footer">
        <div class="footer-links">
            <a href="{{ route('driver.vehicles') }}">Home</a> |
            <a href="{{ route('driver.rides') }}">Rides</a> |
            <a href="{{ route('bookings') }}">Bookings</a>
        </div>
        <p>&copy; Aventones.com</p>
    </footer>

</body>
</html>
