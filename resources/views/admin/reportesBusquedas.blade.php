<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Búsquedas</title>
    <link rel="stylesheet" href="{{ asset('css/adminReportes.css') }}">
</head>

<body>

<div class="container">

    {{-- BOTÓN VOLVER --}}
    <div class="report-button-container">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">
            ← Volver al Dashboard
        </a>
    </div>

    {{-- CARD PRINCIPAL --}}
    <div class="report-card">

        <h1 class="report-title">Reporte de Búsquedas</h1>

        {{-- FORMULARIO --}}
        <form class="report-form" 
              action="{{ route('admin.reportes.busquedas.filtrar') }}" 
              method="POST">
            @csrf

            <div>
                <label>Desde:</label>
                <input type="date" name="desde" value="{{ $desde ?? '' }}" required>
            </div>

            <div>
                <label>Hasta:</label>
                <input type="date" name="hasta" value="{{ $hasta ?? '' }}" required>
            </div>

            <button class="btn btn-primary" type="submit">Filtrar</button>
        </form>

        {{-- RESULTADOS --}}
        @if(isset($busquedas))

            @if($busquedas->isEmpty())
                <div class="no-results">
                    <div class="no-results-icon">🔍</div>
                    No se encontraron búsquedas en ese rango.
                </div>

            @else
                <div class="report-table-container mt-2">

                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Salida</th>
                                <th>Llegada</th>
                                <th># Resultados</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($busquedas as $b)
                                <tr>
                                    <td>{{ $b->fecha_busqueda }}</td>
                                    <td>{{ $b->usuario->nombre }} {{ $b->usuario->apellido }}</td>
                                    <td>{{ $b->lugar_salida }}</td>
                                    <td>{{ $b->lugar_llegada }}</td>
                                    <td>{{ $b->cantidad_resultados }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            @endif
        @endif

    </div>

</div>

</body>
</html>