@extends('layouts.driver')

@section('title', 'Edit Ride')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/CreateRide.css') }}">
@endsection

@section('header-title', 'Edit Ride')
@section('nav-rides', 'active')

@section('content')

<main class="create-ride-main">

    <!-- HERO -->
    <div class="form-hero simple">
        <div class="hero-content">
            <h1>Edit Your Ride</h1>
            <p>Update your ride details and schedule</p>
        </div>
    </div>

    <div class="form-container">

        <!-- Mensajes del cliente por JS -->
        <div id="clientMessage"></div>

        <form id="rideForm"
              method="POST"
              action="{{ route('driver.rides.update', $ride->id) }}"
              class="ride-form">

            @csrf
            @method('PUT')

            <!-- SECTION 1 — BASIC INFO -->
            <div class="form-section card">
                <div class="section-header">
                    <div class="section-number">1</div>
                    <h3>Basic Information</h3>
                </div>

                <div class="form-grid">

                    <div class="field-group">

                        <!-- Ride Name -->
                        <div class="field">
                            <label class="field-label">
                                Ride Name <span class="required">*</span>
                            </label>
                            <input type="text"
                                   id="nombre_ride"
                                   name="nombre_ride"
                                   value="{{ $ride->nombre_ride }}"
                                   required>

                            <span class="field-help">Give your ride a descriptive name</span>
                        </div>

                        <!-- Vehicle -->
                        <div class="field">
                            <label class="field-label">
                                Vehicle <span class="required">*</span>
                            </label>

                            <select id="id_vehiculo" name="id_vehiculo" required>
                                <option value="">Select your vehicle</option>

                                @foreach ($vehiculos as $v)
                                    <option value="{{ $v->id }}"
                                            data-capacity="{{ $v->capacidad }}"
                                            @selected($v->id == $ride->id_vehiculo)>
                                        {{ $v->marca }} {{ $v->modelo }} - {{ $v->placa }} ({{ $v->capacidad }} seats)
                                    </option>
                                @endforeach
                            </select>

                            <span class="field-help">Choose the vehicle for this ride</span>
                        </div>

                    </div>


                    <div class="field-group">

                        <!-- Departure -->
                        <div class="field">
                            <label class="field-label">Departure Location *</label>
                            <input type="text"
                                   id="lugar_salida"
                                   name="lugar_salida"
                                   value="{{ $ride->lugar_salida }}"
                                   required>

                            <span class="field-help">Where the ride starts</span>
                        </div>

                        <!-- Arrival -->
                        <div class="field">
                            <label class="field-label">Arrival Location *</label>
                            <input type="text"
                                   id="lugar_llegada"
                                   name="lugar_llegada"
                                   value="{{ $ride->lugar_llegada }}"
                                   required>

                            <span class="field-help">Where the ride ends</span>
                        </div>

                    </div>
                </div>
            </div>


            <!-- SECTION 2 — SCHEDULE & PRICE -->
            <div class="form-section card">
                <div class="section-header">
                    <div class="section-number">2</div>
                    <h3>Schedule & Pricing</h3>
                </div>

                <div class="form-grid">

                    <div class="field-group">

                        <!-- Time -->
                        <div class="field">
                            <label class="field-label">Departure Time *</label>
                            <input type="time"
                                   id="hora"
                                   name="hora"
                                   value="{{ $ride->hora }}"
                                   required>

                            <span class="field-help">Time when the ride departs</span>
                        </div>

                        <!-- Day of Week -->
                        <div class="field days-full-width">
                            <label class="field-label">Day of Week *</label>

                            <div class="days-selector">
                                <div class="days-grid">

                                    @php
                                        $dias = [
                                            'LUNES' => 'Mon',
                                            'MARTES' => 'Tue',
                                            'MIERCOLES' => 'Wed',
                                            'JUEVES' => 'Thu',
                                            'VIERNES' => 'Fri',
                                            'SABADO' => 'Sat',
                                            'DOMINGO' => 'Sun',
                                        ];
                                    @endphp

                                    @foreach ($dias as $dia => $nombre)
                                        <label class="day-item">
                                            <input type="radio"
                                                   class="day-input"
                                                   name="dia_semana"
                                                   value="{{ $dia }}"
                                                   @checked($ride->dia_semana === $dia)>

                                            <span class="day-box">
                                                <span class="day-name">{{ $nombre }}</span>
                                            </span>
                                        </label>
                                    @endforeach

                                </div>
                            </div>

                            <span class="field-help">Select the day for your ride</span>
                        </div>

                    </div>

                    <!-- Seats + Cost -->
                    <div class="field-group-inline">

                        <div class="field">
                            <label class="field-label">Available Seats *</label>
                            <input type="number"
                                   id="espacios_totales"
                                   name="espacios_totales"
                                   value="{{ $ride->espacios_totales }}"
                                   min="1"
                                   required>

                            <span class="field-help">Passenger seats (driver not included)</span>
                        </div>

                        <div class="field">
                            <label class="field-label">Cost per Seat (USD) *</label>
                            <input type="number"
                                   id="costo"
                                   name="costo"
                                   step="0.01"
                                   min="0"
                                   value="{{ number_format($ride->costo, 2, '.', '') }}"
                                   required>

                            <span class="field-help">Price per passenger seat</span>
                        </div>

                    </div>

                </div>
            </div>

            <!-- BUTTONS -->
            <div class="form-actions">
                <a href="{{ route('driver.rides') }}" class="btn outline large">
                    ← Cancel
                </a>

                <button type="submit" class="btn neon large" id="submitBtn">
                    Update Ride
                </button>
            </div>

        </form>
    </div>
</main>

@endsection

@section('scripts')
<script src="{{ asset('js/editRide.js') }}"></script>
@endsection
