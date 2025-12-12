@component('mail::message')
# ¡Hola {{ $reserva->nombre_chofer }}!

Tienes una solicitud de reserva pendiente por más de **{{ $minutos }} minutos**.

## Detalles de la reserva:
- **Viaje:** {{ $reserva->nombre_ride }}
- **Ruta:** {{ $reserva->lugar_salida }} → {{ $reserva->lugar_llegada }}
- **Horario:** {{ $reserva->dia_semana }} a las {{ date('g:i A', strtotime($reserva->hora)) }}
- **Espacios solicitados:** {{ $reserva->cantidad_espacios }}
- **Tiempo pendiente:** {{ $reserva->minutos_pendiente }} minutos

@component('mail::button', ['url' => route('bookings')])
Gestionar Reserva
@endcomponent

Gracias,<br>
Aventones.com
@endcomponent