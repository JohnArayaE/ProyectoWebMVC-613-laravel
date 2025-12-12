@extends('layouts.driver')

@section('title', 'Create Ride')

@section('css')
<link rel="stylesheet" href="{{ asset('css/createRide.css') }}">
@endsection

@section('content')
<body class="create-ride-page">

    <!-- HERO -->
    <section class="form-hero simple">
        <div class="hero-content">
            <h1>Create a New Ride</h1>
            <p>Set your schedule, pick your vehicle and start offering rides instantly.</p>
        </div>
    </section>

    <!-- FORM WRAPPER -->
    <main class="form-container">

        <!-- SYSTEM MESSAGE -->
        <div id="clientMessage">
            @if(session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert error">{{ session('error') }}</div>
            @endif
        </div>

        <form id="rideForm" action="{{ route('driver.rides.store') }}" method="POST">
            @csrf

            <!-- ========== SECTION 1: Ride Information ========== -->
            <div class="form-section card">
                <div class="section-header">
                    <div class="section-number">1</div>
                    <h3>Ride Information</h3>
                </div>

                <div class="form-grid">

                    <!-- Ride Name -->
                    <div class="field">
                        <label class="field-label">Ride Name <span class="required">*</span></label>
                        <input type="text" id="nombre_ride" name="nombre_ride" placeholder="Morning Ride" required>
                    </div>

                    <!-- Vehicle -->
                    <div class="field">
                        <label class="field-label">Vehicle <span class="required">*</span></label>
                        <select id="id_vehiculo" name="id_vehiculo" required>
                            <option value="">Select a vehicle</option>
                            @foreach($vehiculos as $v)
                                <option value="{{ $v->id }}" data-capacity="{{ $v->capacidad }}">
                                    {{ $v->marca }} {{ $v->modelo }} - {{ $v->placa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Departure -->
                    <div class="field">
                        <label class="field-label">Departure Location <span class="required">*</span></label>
                        <input type="text" id="lugar_salida" name="lugar_salida" placeholder="Start point" required>
                    </div>

                    <!-- Arrival -->
                    <div class="field">
                        <label class="field-label">Arrival Location <span class="required">*</span></label>
                        <input type="text" id="lugar_llegada" name="lugar_llegada" placeholder="End point" required>
                    </div>

                </div>
            </div>

            <!-- ========== SECTION 2: Schedule ========== -->
            <div class="form-section card">
                <div class="section-header">
                    <div class="section-number">2</div>
                    <h3>Schedule</h3>
                </div>

                <div class="form-grid">

                    <!-- Time -->
                    <div class="field">
                        <label class="field-label">Time <span class="required">*</span></label>
                        <input type="time" id="hora" name="hora" required>
                    </div>

                    <!-- Seats & Cost -->
                    <div class="field-group-inline">
                        <div class="field">
                            <label class="field-label">Available Seats <span class="required">*</span></label>
                            <input type="number" id="espacios_totales" name="espacios_totales" min="1" placeholder="Seats" required>
                        </div>

                        <div class="field">
                           <label class="field-label">Cost (USD) <span class="required">*</span></label>
<input type="number" id="costo" name="costo" min="0" max="1000" placeholder="USD $" required>
                        </div>
                    </div>

                    <!-- Days -->
                    <div class="days-selector days-full-width">
                        <label class="field-label">Days of the Week <span class="required">*</span></label>

                        <div class="days-grid">
                            @foreach(['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO','DOMINGO'] as $dia)
                                <label class="day-item">
                                    <input type="checkbox" class="day-input" name="dias_semana[]" value="{{ $dia }}">
                                    <span class="day-box">{{ $dia }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            <!-- ========== ACTIONS ========== -->
            <div class="form-actions">
                <a href="{{ route('driver.rides') }}" class="btn outline">Cancel</a>
                <button id="submitBtn" type="submit" class="btn neon large">Create Ride</button>
            </div>

        </form>
    </main>

@endsection

@section('js')
<script src="{{ asset('js/createRide.js') }}"></script>
@endsection
