@component('mail::message')

# Hola {{ $user->nombre }} 👋

Aquí tienes tu acceso rápido a **Aventones**, sin necesidad de contraseña.

@component('mail::button', ['url' => $url])
Acceder ahora
@endcomponent

Este enlace expira en **10 minutos** y solo puede usarse **una vez**.

Si tú no solicitaste este ingreso, simplemente ignora este mensaje.

Gracias,  
**Aventones**

@endcomponent