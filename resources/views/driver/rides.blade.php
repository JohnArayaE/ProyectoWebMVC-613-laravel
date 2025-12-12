@extends('layouts.driver')

@section('title', 'My Rides')
@section('header-title', 'My Rides')
@section('nav-rides', 'active')

@section('top-action')
    <a href="{{ route('driver.rides.create') }}" class="btn neon">New Ride</a>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/rides.css') }}">
@endsection

@section('content')

<main class="rides-content">

    {{-- Mensajes dinámicos solo de error (quitamos success si quieres) --}}
    @if(session('error'))
        <div class="system-message error">
            {{ session('error') }}
        </div>
    @endif


    {{-- Vacío --}}
    @if ($rides->isEmpty())
        <article class="rides-empty" id="emptyState">
            <div class="rides-illu" aria-hidden="true"></div>
            <h3>No rides yet</h3>
            <p>Create your first ride to start offering transport services.</p>

            <a href="{{ route('driver.rides.create') }}" class="btn neon ghost">Create ride</a>
        </article>

    @else
        {{-- GRID --}}
        <section class="rides-grid" id="ridesGrid">
            @foreach ($rides as $ride)

                <article class="ride-card" data-id="{{ $ride->id }}">
                    <span class="ride-accent"></span>

                    {{-- HEADER --}}
                    <div class="ride-header">
                        <h3 class="ride-name">{{ $ride->nombre_ride }}</h3>
                        <span class="ride-cost">${{ number_format($ride->costo, 2) }}</span>
                    </div>

                    {{-- ROUTE --}}
                    <div class="ride-route">
                        <div class="route-item">
                            <span class="route-dot start"></span>
                            <span class="route-text">{{ $ride->lugar_salida }}</span>
                        </div>

                        <div class="route-item">
                            <span class="route-dot end"></span>
                            <span class="route-text">{{ $ride->lugar_llegada }}</span>
                        </div>
                    </div>

                    {{-- DETAILS --}}
                    <div class="ride-details">

                        <div class="detail-item">
                            <span class="detail-label">Day</span>
                            <span class="detail-value">{{ $ride->dia_semana }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Time</span>
                            <span class="detail-value">
                                {{ date('g:i A', strtotime($ride->hora)) }}
                            </span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Seats</span>
                            <span class="detail-value">
                                {{ $ride->espacios_disponibles }} / {{ $ride->espacios_totales }}
                            </span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Vehicle</span>
                            <span class="detail-value">{{ $ride->marca }} {{ $ride->modelo }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Status</span>
                            <span class="detail-value {{ strtolower($ride->estado) }}">
                                {{ $ride->estado }}
                            </span>
                        </div>

                    </div>

                    {{-- ACTIONS --}}
                    <footer class="ride-actions">

                        {{-- EDIT --}}
                        <a href="{{ route('driver.rides.edit', $ride->id) }}" class="btn outline">
                            Edit
                        </a>

                        {{-- DELETE (pasa de ACTIVO → CANCELADO) --}}
                        <form action="{{ route('driver.rides.destroy', $ride->id) }}"
                              method="POST"
                              style="display:inline;">
                            
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn danger"
                                    onclick="return confirm('Are you sure you want to delete this ride?');">
                                Delete
                            </button>
                        </form>

                    </footer>
                </article>

            @endforeach
        </section>

    @endif

</main>

@endsection


