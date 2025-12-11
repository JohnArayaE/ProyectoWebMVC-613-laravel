<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurations - AVENTONES</title>

    <link rel="stylesheet" href="{{ asset('css/configurations.css') }}">
    <script src="{{ asset('js/configurations.js') }}" defer></script>
</head>

<body>
@php
    $userRol = session('user_rol');
    $homeRoute = match($userRol) {
        'ADMIN'  => route('admin.dashboard'),
        'CHOFER' => route('driver.vehicles'),
        default  => route('home'),
    };

    $foto = $usuario->foto_ruta ? asset($usuario->foto_ruta) : asset('img/logo.png');
@endphp

<header class="header">
    <div class="container">
        <div class="header-content">

            <div class="logo">
                <i class="fas fa-car logo-icon"></i>
                <h1 class="logo-text">AVENTONES</h1>
            </div>

            <nav class="nav-menu">
                <a href="{{ $homeRoute }}" class="nav-link">Home</a>
            </nav>

            <div class="user-menu">
                <div class="profile-menu">
                    <img src="{{ $foto }}"
                         alt="User Icon"
                         class="avatar"
                         id="avatarBtn"
                         onerror="this.src='{{ asset('img/logo.png') }}'">

                    <ul class="dropdown" id="profileDropdown">
                        <li><a href="{{ $homeRoute }}" class="dropdown-link">Home</a></li>
                        <li><a href="{{ route('logout') }}" class="dropdown-link logout-btn">Logout</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</header>


<main class="main-content">
    <div class="container">
        <div class="config-container">
            <div class="config-card">

                <div class="config-header">
                    <h1 class="config-title">CONFIGURATIONS</h1>
                    <p class="config-subtitle">Update your personal information</p>
                </div>

                {{-- mensajes --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error">
                        @foreach($errors->all() as $e)
                            <p>{{ $e }}</p>
                        @endforeach
                    </div>
                @endif


                <form action="{{ route('configurations.update') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="form">

                    @csrf

                    <div class="form-grid">

                        <div class="field">
                            <label>First Name</label>
                            <input type="text" name="nombre"
                                   value="{{ $usuario->nombre }}" class="form-input" required>
                        </div>

                        <div class="field">
                            <label>Last Name</label>
                            <input type="text" name="apellido"
                                   value="{{ $usuario->apellido }}" class="form-input" required>
                        </div>

                        <div class="field">
                            <label>Birth</label>
                            <input type="date" name="fecha_nacimiento"
                                   value="{{ $usuario->fecha_nacimiento }}" class="form-input" required>
                        </div>

                        <div class="field">
                            <label>ID Number</label>
                            <input type="text" name="cedula"
                                   value="{{ $usuario->cedula }}" class="form-input" required>
                        </div>

                        <div class="field span-2">
                            <label>Email</label>
                            <input type="email" name="correo"
                                   value="{{ $usuario->correo }}" class="form-input" required>
                        </div>

                        <div class="field span-2">
                            <label>Phone Number</label>
                            <input type="tel" name="telefono"
                                   value="{{ $usuario->telefono }}" class="form-input">
                        </div>


                        {{-- FOTO --}}
                        <div class="photo-section span-2">
                            <label>Profile Picture</label>

                            <div class="photo-container">
                                <div class="photo-preview">
                                    <img src="{{ $foto }}" id="photoPreview">
                                </div>

                                <input type="file" id="foto" name="foto" accept="image/*" class="file-input">

                                <label for="foto" class="btn-file">
                                    <i class="fas fa-upload"></i>
                                    Choose Image
                                </label>
                            </div>

                            <p class="photo-hint">JPG, PNG or GIF. Max 5MB.</p>
                        </div>


                        <div class="actions span-2">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</main>
<footer class="footer">
    <div class="container">
        <p>&copy; 2025 AVENTONES. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
