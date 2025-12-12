<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- 🔥 CSRF TOKEN (NECESARIO PARA QUE FUNCIONE CONFIRMAR / CANCELAR) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bookings</title>

    <link rel="stylesheet" href="{{ asset('css/bookings.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <script src="{{ asset('js/bookings.js') }}" defer></script>
</head>
<body class="bookings-page">

<header class="bookings-topbar">
    <div class="bookings-brand">
        <img class="bookings-logo" src="{{ asset('img/logo.png') }}">
        <h1>Bookings</h1>
    </div>

    <nav class="main-nav">
        <ul class="nav-links">
            @if($user->rol === 'CHOFER')
                <li><a href="{{ route('driver.vehicles') }}">Vehicles</a></li>
                <li><a href="{{ route('driver.rides') }}" class="active">Rides</a></li>
                <li><a href="{{ route('bookings') }}" class="active">Bookings</a></li>
            @else
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('bookings') }}" class="active">Bookings</a></li>
            @endif
        </ul>
    </nav>

    <div class="right-box">

        @if($user->rol === 'PASAJERO')
            <a href="{{ route('home') }}" class="btn neon">Find Rides</a>
        @else
            <a href="#" class="btn neon">New Ride</a>
        @endif

        <div class="profile-menu">
            <img src="{{ asset($user->foto_ruta ?? 'img/logo.png') }}"
                 id="avatarBtn"
                 class="avatar">

            <ul class="dropdown" id="profileDropdown">
                <li><a href="{{ route('configurations') }}">Configuration</a></li>
                <li><a href="{{ route('logout') }}" class="logout-btn">Logout</a></li>
            </ul>
        </div>
    </div>
</header>

<main class="bookings-content">

    @if($bookings->isEmpty())
        <article class="bookings-empty">
            <div class="bookings-illu"></div>
            <h3>No bookings yet</h3>
            <p>
                @if($user->rol === 'CHOFER')
                    When passengers book your rides, they will appear here.
                @else
                    When you book rides, they will appear here.
                @endif
            </p>

            @if($user->rol === 'PASAJERO')
                <a href="{{ route('home') }}" class="btn neon ghost">Find Rides</a>
            @else
                <a href="#" class="btn neon ghost">Create Ride</a>
            @endif
        </article>

    @else
        <section class="bookings-grid">

            @foreach($bookings as $booking)
                <article class="booking-card" data-id="{{ $booking->id }}">
                    <span class="booking-accent"></span>

                    <div class="booking-header">
                        <div class="booking-route">
                            <h3>{{ $booking->ride->lugar_salida }} → {{ $booking->ride->lugar_llegada }}</h3>

                            <div class="booking-time">
                                <i class="fas fa-clock"></i>
                                {{ substr($booking->ride->hora,0,5) }} • {{ $booking->ride->dia_semana }}
                            </div>
                        </div>

                        <div class="booking-price">
                            ₡{{ number_format($booking->ride->costo * $booking->cantidad_espacios, 2) }}
                        </div>
                    </div>

                    <div class="booking-status status-{{ strtolower($booking->estado) }}">
                        {{ $booking->estado }}
                    </div>

                    <ul class="booking-details">
                        @if($user->rol === 'CHOFER')
                            <li><strong>Passenger:</strong>
                                {{ $booking->pasajero->nombre }} {{ $booking->pasajero->apellido }}
                            </li>
                        @else
                            <li><strong>Driver:</strong>
                                {{ $booking->ride->chofer->nombre }} {{ $booking->ride->chofer->apellido }}
                            </li>
                        @endif

                        <li><strong>Vehicle:</strong>
                            {{ $booking->ride->vehiculo->marca }}
                            {{ $booking->ride->vehiculo->modelo }}
                            ({{ $booking->ride->vehiculo->placa }})
                        </li>

                        <li><strong>Spaces:</strong> {{ $booking->cantidad_espacios }}</li>
                    </ul>

                    <footer class="booking-actions">
                        @if($user->rol === 'CHOFER' && $booking->estado === 'PENDIENTE')
                            <button class="btn success" data-action="accept">Accept</button>
                            <button class="btn danger" data-action="reject">Reject</button>
                        @endif

                        @if(!in_array($booking->estado, ['CANCELADA','RECHAZADA','COMPLETADA']))
                            <button class="btn outline" data-action="cancel">Cancel</button>
                        @endif
                    </footer>
                </article>
            @endforeach

        </section>
    @endif

</main>

</body>
</html>