@component('mail::message')

# ¡Hola {{ $nombre }}! 👋

Gracias por registrarte en **Aventones**.

Para activar tu cuenta, haz clic en el botón:

@component('mail::button', ['url' => route('registration.activate', ['token' => $token])])
Activar mi cuenta
@endcomponent

Este enlace expira en **24 horas**.

Si tú no te registraste, ignora este mensaje.

Gracias,  
**Aventones**

@endcomponent
