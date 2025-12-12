<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register Admin — Aventones</title>

  <link rel="stylesheet" href="{{ asset('css/registration.css') }}">
  {{-- JS --}}
  <script src="{{ asset('js/registration.js') }}" defer></script>

</head>

<body>
  <div class="login-container">
    <div class="login-card">
      
      <div class="login-logo">
        <img src="{{ asset('img/logo.png') }}" alt="logo_principal">
      </div>

      <h1 class="login-title">CREATE ADMIN</h1>

      @if ($errors->any())
      <div style="background:red; color:white; padding:10px; margin-bottom:20px;">
          <strong>Errors:</strong>
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
      @endif

      {{-- FORMULARIO --}}
      <form action="{{ route('admin.register.store') }}" 
            method="POST" 
            enctype="multipart/form-data" 
            autocomplete="off"
            class="form">

        @csrf

        <div class="form-grid">

          <div class="field">
            <label for="nombre">First Name</label>
            <input type="text" id="nombre" name="nombre" required>
          </div>

          <div class="field">
            <label for="apellido">Last Name</label>
            <input type="text" id="apellido" name="apellido" required>
          </div>

          <div class="field">
            <label for="fecha_nacimiento">Birth</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
          </div>

          <div class="field">
            <label for="cedula">ID Number</label>
            <input type="text" id="cedula" name="cedula" required>
          </div>

          <div class="field span-2">
            <label for="correo">Email</label>
            <input type="email" id="correo" name="correo" required>
          </div>

          <div class="field">
            <label for="contrasena">Password</label>
            <input type="password" id="contrasena" name="contrasena" required>
          </div>

          <div class="field">
            <label for="contrasena_repetir">Repeat Password</label>
            <input type="password" id="contrasena_repetir" name="contrasena_repetir" required>
          </div>

          <div class="field span-2">
            <label for="telefono">Phone Number</label>
            <input type="tel" id="telefono" name="telefono">
          </div>

          {{-- FOTO --}}
          <div class="photo-section span-2">
            <label for="photo">Profile Picture</label>
            <input type="file" id="photo" name="photo" accept="image/*">
            <div class="preview">
              <img id="photoPreview" src="" alt="Preview">
            </div>
          </div>

          {{-- ROL FIJO = ADMIN --}}
          <input type="hidden" name="rol" value="ADMIN">

          <div class="actions span-2">
            <button type="submit">Create Admin</button>
          </div>

          <div class="links-row span-2">
            <p class="login-link">
              Back to panel:
              <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
            </p>
          </div>

        </div>
      </form>
    </div>
  </div>
</body>
</html>