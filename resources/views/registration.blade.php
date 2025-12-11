<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registro — Aventones</title>

  {{-- CSS --}}
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

      <h1 class="login-title">AVENTONES</h1>
      @if ($errors->any())
    <div style="background:red; color:white; padding:10px; margin-bottom:20px;">
        <strong>Hay errores en el formulario:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
      @endif
      {{-- FORMULARIO COMPLETO CORREGIDO --}}
      <form action="{{ route('registration.store') }}" 
            method="POST" 
            enctype="multipart/form-data" 
            autocomplete="off"
            class="form">

        @csrf

        <div class="form-grid">

          {{-- NOMBRE --}}
          <div class="field">
            <label for="nombre">First Name</label>
            <input type="text" id="nombre" name="nombre" required>
          </div>

          {{-- APELLIDO --}}
          <div class="field">
            <label for="apellido">Last Name</label>
            <input type="text" id="apellido" name="apellido" required>
          </div>

          {{-- FECHA --}}
          <div class="field">
            <label for="fecha_nacimiento">Birth</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
          </div>

          {{-- CÉDULA --}}
          <div class="field">
            <label for="cedula">ID Number</label>
            <input type="text" id="cedula" name="cedula" required>
          </div>

          {{-- CORREO --}}
          <div class="field span-2">
            <label for="correo">Email</label>
            <input type="email" id="correo" name="correo" required>
          </div>

          {{-- CONTRASEÑA --}}
          <div class="field">
            <label for="contrasena">Password</label>
            <input type="password" id="contrasena" name="contrasena" required>
          </div>

          {{-- REPETIR CONTRASEÑA --}}
          <div class="field">
            <label for="contrasena_repetir">Repeat Password</label>
            <input type="password" id="contrasena_repetir" name="contrasena_repetir" required>
          </div>

          {{-- TELÉFONO --}}
          <div class="field span-2">
            <label for="telefono">Phone Number</label>
            <input type="tel" id="telefono" name="telefono" required>
          </div>

          {{-- FOTO --}}
          <div class="photo-section span-2">
            <label for="photo">Profile Picture</label>
            <input type="file" id="photo" name="photo" accept="image/*">
            <div class="preview">
              <img id="photoPreview" src="" alt="Preview">
            </div>
          </div>

          {{-- ROL OCULTO --}}
          <input type="hidden" name="rol" value="PASAJERO">

          {{-- BOTÓN SUBMIT --}}
          <div class="actions span-2">
            <button type="submit">Sign Up</button>
          </div>

          {{-- LINKS --}}
          <div class="links-row span-2">
            <p class="login-link">
              Already a user?
              <a href="{{ route('login') }}">Login here</a>
            </p>

            <p class="login-link">
              Register as driver?
              <a href="{{ route('registerDriver.create') }}">Click here</a>
            </p>
          </div>

        </div> 
      </form>
    </div>
  </div>
</body>
</html>
