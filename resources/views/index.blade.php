<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AVENTONES - Find Your Ride</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>

<body class="index-page">

    {{-- HEADER COMPLETO --}}
    <header class="index-topbar">
        <div class="index-brand">
            <img class="index-logo" src="{{ asset('img/logo.png') }}" alt="Aventones logo">
            <h1>AVENTONES</h1>
        </div>

        <nav class="main-nav">
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="active">Home</a></li>
                <li><a href="{{ route('bookings') }}">Bookings</a></li>
            </ul>
        </nav>

        <div class="right-box">
            @if(session('user_id'))

                

                {{-- PROFILE MENU --}}
                <div class="profile-menu">
                    <img src="{{ asset(session('user_foto', 'img/logo.png')) }}"
                         alt="User Icon"
                         class="avatar"
                         id="avatarBtn"
                         onerror="this.src='{{ asset('img/logo.png') }}'">

                    <ul class="dropdown" id="profileDropdown">
                        <li><a href="{{ route('home') }}" class="dropdown-link">Home</a></li>
                        <li><a href="{{ route('bookings') }}" class="dropdown-link">Bookings</a></li>
                        <li><a href="{{ route('configurations') }}" class="dropdown-link">Configuration</a></li>
                        <li><a href="{{ route('logout') }}" class="logout-btn">Logout</a></li>
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
                    {{ (empty(request('origen')) && empty(request('destino'))) ? 'Available Rides' : 'Search Results' }}
                    <span class="rides-count">({{ count($rides) }})</span>
                </h3>

                <div class="sort-container">
                    <span class="sort-label">Sort by:</span>
                    <select id="ordenSelect" class="sort-select">
                        <option value="dia_asc">Day (Monday to Sunday)</option>
                        <option value="dia_desc">Day (Sunday to Monday)</option>
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
                                {{ $ride->lugar_salida }} → {{ $ride->lugar_llegada }}
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

    {{-- ========== RESERVATION MODAL (NECESARIO PARA FUNCIONAR) ========== --}}
    <dialog id="reservaModal" class="ride-modal">
        <div class="modal-content">

            <div class="modal-header">
                <h3>Book Ride</h3>
                <span class="close">&times;</span>
            </div>

            <div class="modal-body">
                <div id="modalRideInfo"></div>

                <div class="form-group">
                    <label for="cantidadEspacios" class="form-label">Number of spaces:</label>
                    <input type="number" id="cantidadEspacios" min="1" value="1" class="form-input">
                    <small id="maxEspaciosInfo" class="form-help"></small>
                </div>

                <div class="price-summary">
                    <p>Cost per space: <span id="costoPorEspacio">₡0.00</span></p>
                    <p class="total-price">Total: <span id="costoTotal">₡0.00</span></p>
                </div>
            </div>

            <div class="modal-actions">
                <button class="btn outline" id="cancelarReserva">Cancel</button>
                <button class="btn neon" id="confirmarReserva">Confirm Booking</button>
            </div>

        </div>
    </dialog>

    {{-- JS --}}
    <script src="{{ asset('js/index.js') }}"></script>

    {{-- SUBMENU SCRIPT --}}
    <script>
        const avatarBtn = document.getElementById('avatarBtn');
        const profileDropdown = document.getElementById('profileDropdown');

        if (avatarBtn && profileDropdown) {
            avatarBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');
            });

            document.addEventListener('click', function() {
                profileDropdown.classList.remove('show');
            });

            profileDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    </script>

</body>
</html>
