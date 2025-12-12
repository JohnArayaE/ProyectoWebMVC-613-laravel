@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<div class="container">

    {{-- Mensajes de éxito / error --}}
    @if (session('mensaje'))
        <div class="alert-success">{{ session('mensaje') }}</div>
    @endif

    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    {{-- === BOTÓN PARA REPORTE DE BÚSQUEDAS === --}}
    <div class="report-button-container">
        <a href="{{ route('admin.reportes.busquedas') }}" class="btn btn-primary">
            <i class="fas fa-chart-bar"></i> Ver Reporte de Búsquedas
        </a>
    </div>

    {{-- === Estadísticas === --}}
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <h3 class="stat-number">{{ $usuarios->count() }}</h3>
                <p class="stat-label">Total Usuarios</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user-check"></i></div>
            <div class="stat-info">
                <h3 class="stat-number">{{ $usuarios->where('estado', 'ACTIVO')->count() }}</h3>
                <p class="stat-label">Usuarios Activos</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user-clock"></i></div>
            <div class="stat-info">
                <h3 class="stat-number">{{ $usuarios->where('estado', 'PENDIENTE')->count() }}</h3>
                <p class="stat-label">Pendientes</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user-slash"></i></div>
            <div class="stat-info">
                <h3 class="stat-number">{{ $usuarios->where('estado', 'INACTIVO')->count() }}</h3>
                <p class="stat-label">Inactivos</p>
            </div>
        </div>

    </div>

    {{-- === Tabla de usuarios === --}}
    <div class="users-section">
        <h2 class="section-title">Gestión de Usuarios</h2>

        <div class="users-table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Cédula</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($usuarios as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->nombre }} {{ $u->apellido }}</td>
                            <td>{{ $u->cedula }}</td>
                            <td>{{ $u->correo }}</td>
                            <td>{{ $u->telefono ?? 'N/A' }}</td>

                            <td>
                                <span class="role-badge role-{{ strtolower($u->rol) }}">
                                    {{ $u->rol }}
                                </span>
                            </td>

                            <td>
                                <span class="status-badge status-{{ strtolower($u->estado) }}">
                                    {{ $u->estado }}
                                </span>
                            </td>

                            <td>
                                <form action="{{ route('admin.status') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="usuario_id" value="{{ $u->id }}">

                                    @if ($u->estado === 'ACTIVO' || $u->estado === 'PENDIENTE')
                                        <input type="hidden" name="nuevo_estado" value="INACTIVO">
                                        <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Desactivar usuario?')">
                                            Desactivar
                                        </button>
                                    @else
                                        <input type="hidden" name="nuevo_estado" value="ACTIVO">
                                        <button class="btn btn-success btn-sm"
                                                onclick="return confirm('¿Activar usuario?')">
                                            Activar
                                        </button>
                                    @endif
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr><td colspan="8">No hay usuarios registrados</td></tr>
                    @endforelse

                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection