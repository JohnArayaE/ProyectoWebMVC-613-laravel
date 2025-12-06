<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AVENTONES - Find Your Ride</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>

<body class="index-page">

    {{-- HEADER --}}
    <header class="index-topbar">
        <div class="index-brand">
            <img class="index-logo" src="{{ asset('img/logo.png') }}" alt="Aventones logo">
            <h1>AVENTONES</h1>
        </div>

        <nav class="main-nav">
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="active">Home</a></li>
                <li><a href="#">Bookings</a></li>
            </ul>
        </nav>

        <div class="right-box">
            @if(session('user_id'))
                @if(session('user_rol') === 'CHOFER')
                    <a href="#" class="btn neon">New Ride</a>
                @endif

                <div class="profile-menu">
                    <img src="{{ asset(session('user_foto', 'img/logo.png')) }}"
                         alt="User Icon" class="avatar" id="avatarBtn"
                         onerror="this.src='{{ asset('img/logo.png') }}'">

                    <ul class="dropdown" id="profileDropdown">
                        <li><a href="{{ route('home') }}" class="dropdown-link">Home</a></li>
                        <li><a href="#" class="dropdown-link">Bookings</a></li>
                        <li><a href="#" class="dropdown-link">Configuration</a></li>
                        <li><a href="#" class="logout-btn">Logout</a></li>
                    </ul>
                </div>
            @else
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="btn outline">Login</a>
                    <a href="{{ route('registration') }}" class="btn neon">Sign Up</a>
                </div>
            @endif
        </div>
    </header>

    {{-- HERO SECTION --}}
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h2 class="hero-title">Find Your Perfect Ride</h2>
                <p class="hero-subtitle">Travel safe, economical and with style</p>

                {{-- SEARCH FORM --}}
                <form method="GET" action="{{ route('home') }}" class="search-form">
                    <div class="search-grid">
                        <div class="form-group">
                            <label for="origen" class="form-label">From</label>
                            <input type="text" id="origen" name="origen"
                                   value="{{ request('origen') }}"
                                   class="form-input" placeholder="Where are you leaving from?">
                        </div>

                        <div class="form-group">
                            <label for="destino" class="form-label">To</label>
                            <input type="text" id="destino" name="destino"
                                   value="{{ request('destino') }}"
                                   class="form-input" placeholder="Where are you going?">
                        </div>

                        <div class="form-group form-group-button">
                            <button type="submit" class="btn neon btn-search">
                                <i class="fas fa-search btn-icon"></i> Search Rides
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <main class="index-content">
        <div class="container">

            {{-- Filters Section --}}
            <div class="filters-section">
                <h3 class="section-title">
                    {{ (empty(request('origen')) && empty(request('destino')))
                        ? 'Available Rides'
                        : 'Search Results' }}
                    <span class="rides-count">({{ count($rides) }})</span>
                </h3>

                <div class="sort-container">
                    <span class="sort-label">Sort by:</span>
                    <select id="ordenSelect" class="sort-select" onchange="sortRides(this.value)">
                        <option value="dia_asc">Day (Mon → Sun)</option>
                        <option value="dia_desc">Day (Sun → Mon)</option>
                        <option value="origen_asc">Origin (A-Z)</option>
                        <option value="origen_desc">Origin (Z-A)</option>
                        <option value="destino_asc">Destination (A-Z)</option>
                        <option value="destino_desc">Destination (Z-A)</option>
                        <option value="precio_asc">Price (Low → High)</option>
                        <option value="precio_desc">Price (High → Low)</option>
                    </select>
                </div>
            </div>

            {{-- RIDES GRID --}}
            <div class="rides-grid">
                @forelse($rides as $ride)

                    <article class="ride-card" data-id="{{ $ride->id }}">
                        <span class="ride-accent"></span>

                        <div class="ride-header">
                            <div class="ride-info-left">
                                <span class="ride-time">{{ substr($ride->hora, 0, 5) }}</span>
                                <h4 class="ride-price">₡{{ number_format($ride->costo, 2) }}</h4>
                            </div>

                            @if(session('user_rol') === 'PASAJERO')
                                <button onclick="reservarRide({{ $ride->id }})"
                                        class="btn success btn-reserve">
                                    <i class="fas fa-calendar-plus"></i> Book
                                </button>
                            @endif
                        </div>

                        <div class="ride-route">
                            <h5 class="ride-route-title">
                                {{ $ride->lugar_salida }} →
                                {{ $ride->lugar_llegada }}
                            </h5>
                        </div>

                        <div class="ride-details">
                            <div class="vehicle-info">
                                <i class="fas fa-car detail-icon"></i>
                                {{ $ride->marca }} {{ $ride->modelo }} · {{ $ride->anio }}
                            </div>

                            <div class="seats-info">
                                <i class="fas fa-users detail-icon"></i>
                                {{ $ride->espacios_disponibles }} seats available
                            </div>
                        </div>

                        <div class="ride-day">
                            <span class="day-badge">
                                <i class="fas fa-calendar day-icon"></i>
                                {{ $ride->dia_semana }}
                            </span>
                        </div>
                    </article>

                @empty

                    <div class="no-rides">
                        <div class="no-rides-illu"></div>
                        <h4 class="no-rides-title">No rides found</h4>
                        <p class="no-rides-text">Try different search criteria</p>
                    </div>

                @endforelse
            </div>
        </div>
    </main>

    {{-- JS --}}
    <script src="{{ asset('js/index.js') }}"></script>
</body>
</html>
