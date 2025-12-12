@extends('layouts.driver')

@section('title', 'My Vehicles')
@section('header-title', 'My Vehicles')
@section('nav-home', 'active')

@section('top-action')
    <a href="{{ route('driver.vehicles.create') }}" class="btn neon">New Vehicle</a>
@endsection

@section('content')
<main class="veh-content">

    {{-- Si no hay vehículos --}}
    @if ($vehiculos->isEmpty())
        <article class="veh-empty" id="emptyState">
            <div class="veh-illu" aria-hidden="true"></div>
            <h3>No vehicles yet</h3>
            <p>Add your first vehicle to start offering rides.</p>

            <a href="{{ route('driver.vehicles.create') }}" class="btn neon">New Vehicle</a>
        </article>

    @else
        <section class="veh-grid" id="vehGrid">

            @foreach ($vehiculos as $vehiculo)
                <article class="veh-card" data-id="{{ $vehiculo->id }}">
                    <span class="veh-accent"></span>

                    <div class="veh-photo">
                        <img src="{{ asset('storage/' . $vehiculo->foto_vehiculo) }}" 
                            alt="Vehicle photo">
                    </div>

                    <header class="veh-head">
                        <span class="veh-plate">{{ $vehiculo->placa }}</span>
                        <span class="veh-seats">{{ $vehiculo->capacidad }} seats</span>
                    </header>

                    <ul class="veh-meta">
                        <li><strong>Brand/Model:</strong> {{ $vehiculo->marca }} {{ $vehiculo->modelo }}</li>
                        <li><strong>Year:</strong> {{ $vehiculo->anio }}</li>
                        <li><strong>Color:</strong> {{ $vehiculo->color }}</li>
                    </ul>

                    <footer class="veh-actions">
                        <button class="btn outline" data-edit="{{ $vehiculo->id }}">
                            Edit
                        </button>

                        <button class="btn danger" data-delete="{{ $vehiculo->id }}">
                            Delete
                        </button>
                    </footer>

                </article>
            @endforeach

        </section>
    @endif

</main>
@endsection
