@extends('layouts.driver')

@section('title', 'My Vehicles')
@section('header-title', 'My Vehicles')
@section('nav-home', 'active')

@section('content')
    <main class="veh-content">

        {{-- Si no hay vehículos --}}
        @if ($vehiculos->isEmpty())
            <article class="veh-empty" id="emptyState">
                <div class="veh-illu" aria-hidden="true"></div>
                <h3>No vehicles yet</h3>
                <p>Add your first vehicle to start offering rides.</p>
                <a href="#" class="btn neon ghost">Add vehicle</a>
            </article>
        @else
            <section class="veh-grid" id="vehGrid">

                @foreach ($vehiculos as $vehiculo)
                    <article class="veh-card" data-id="{{ $vehiculo->id }}">
                        <span class="veh-accent"></span>

                        <div class="veh-photo">
                            <img src="{{ $vehiculo->foto_vehiculo ? asset($vehiculo->foto_vehiculo) : asset('img/example.jpg') }}"
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
                            <a href="#" class="btn outline">Edit</a>

                            <form action="#" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this vehicle?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn danger">Delete</button>
                            </form>
                        </footer>

                    </article>
                @endforeach

            </section>
        @endif

    </main>
@endsection
