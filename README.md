🚗 Aventones – Plataforma de Rides Compartidos
Aventones es una plataforma web desarrollada en Laravel que conecta a conductores que ofrecen rides con pasajeros que desean transportarse en rutas similares.
El sistema gestiona vehículos, rides, reservas, notificaciones automáticas y paneles específicos para distintos roles.

📋 Descripción General del Proyecto
El sistema ofrece una experiencia completa para:
Para Conductores
Registrar y administrar vehículos
Publicar rides para múltiples días de la semana
Gestionar disponibilidad de asientos
Editar o cancelar rides
Aceptar o rechazar reservas de pasajeros
Recibir correos de notificación por reservas pendientes
Para Pasajeros
Buscar rides por ruta, día y horario
Reservar asientos disponibles
Ver el estado de sus reservas (Pendiente, Aceptada, Rechazada, Cancelada)
Recibir confirmaciones desde los conductores

🛠️ Tecnologías Utilizadas
Frontend
HTML5
CSS3 (diseño moderno, responsive, variables CSS)
JavaScript vanilla
Blade Templates (Laravel)
Backend
Laravel 10 (framework principal)
PHP 8+
MySQL 
Apache 
Composer (dependencias PHP)
NPM 

💾 Base de Datos
El sistema utiliza múltiples tablas como:
usuarios
vehiculos
rides
reservas ...y más.

Cada módulo está conectado a través de claves foráneas y controlado desde controladores Laravel.

🎯 Funcionalidades Detalladas
🚘 Gestión de Vehículos
Crear vehículos con foto
Editar información
Eliminar (lógico)
Validación de placa única
Capacidad máxima controlada

🚌 Gestión de Rides
Crear rides con selección múltiple de días
Evitar horarios duplicados por vehículo
Calcular disponibilidad automáticamente
Estados:
ACTIVO
CANCELADO
COMPLETADO

📅 Reservas de Pasajeros
Estados:
PENDIENTE
ACEPTADA
RECHAZADA
CANCELADA
Validación de disponibilidad

🧩 Roles del Sistema
👨‍💼 Administrador
Gestiona usuarios y configuraciones
Acceso a reportes del sistema
Activación/desactivación de conductores

🚗 Conductor
Crear/editar/eliminar vehículos
Crear/editar/cancelar rides
Aceptar/rechazar reservas
Recibir notificaciones automáticas por retrasos

🧍 Pasajero
Búsqueda de rides disponibles
Realizar reservas
Ver estados de sus viajes
Gestionar su perfil


🔔 Notificaciones Automáticas por Reservas Pendientes
Laravel ejecuta un comando especial que revisa periódicamente si existen reservas con estado PENDIENTE por más de X minutos.
✔ Archivo Command:
app/Console/Commands/NotificarReservasPendientes.php

Plantilla del correo:
resources/views/emails/reservaPendiente.blade.php

Envío del correo:
app/Mail/ReservaPendienteMail.php

🧪 Ejecutar notificación manualmente:
php artisan reservas:notificar 5


dependencias PHP:
composer install
npm install

Ejecutar migraciones:
php artisan migrate

Levantar el servidor
php artisan serve
Y accedemos con:
http://127.0.0.1:8000
O dominio configurado

