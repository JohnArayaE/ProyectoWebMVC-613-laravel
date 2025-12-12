@extends('layouts.driver')

@section('title', 'Edit Vehicle')
@section('header-title', 'Edit Vehicle')

@section('css')
<link rel="stylesheet" href="{{ asset('css/createVehicle.css') }}">
@endsection

@section('content')
<main class="create-vehicle-main">

    <div class="form-hero simple">
        <div class="hero-content">
            <h1>Edit Your Vehicle</h1>
            <p>Update your vehicle details to keep your information current</p>
        </div>
    </div>

    <div class="form-container">

        {{-- Mensaje dinámico --}}
        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        <form id="vehicleForm"
              method="POST"
              action="{{ route('driver.vehicles.update', $vehiculo->id) }}"
              enctype="multipart/form-data"
              class="vehicle-form">

            @csrf
            @method('PUT')

            <!-- Sección 1: Información Básica -->
            <div class="form-section card">
                <div class="section-header">
                    <div class="section-number">1</div>
                    <h3>Basic Information</h3>
                </div>

                <div class="form-grid">

                    <div class="field-group">
                        <div class="field">
                            <label class="field-label">License Plate *</label>
                            <input type="text"
                                   id="placa"
                                   name="placa"
                                   value="{{ $vehiculo->placa }}"
                                   required>
                        </div>

                        <div class="field">
                            <label class="field-label">Color *</label>
                            <input type="text"
                                   id="color"
                                   name="color"
                                   value="{{ $vehiculo->color }}"
                                   required>
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field">
                            <label class="field-label">Brand *</label>
                            <input type="text"
                                   id="marca"
                                   name="marca"
                                   value="{{ $vehiculo->marca }}"
                                   required>
                        </div>

                        <div class="field">
                            <label class="field-label">Model *</label>
                            <input type="text"
                                   id="modelo"
                                   name="modelo"
                                   value="{{ $vehiculo->modelo }}"
                                   required>
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field">
                            <label class="field-label">Manufacturing Year *</label>
                            <input type="number"
                                   id="anio"
                                   name="anio"
                                   min="1980"
                                   max="{{ date('Y') + 1 }}"
                                   value="{{ $vehiculo->anio }}"
                                   required>
                        </div>

                        <div class="field">
                            <label class="field-label">Seating Capacity *</label>
                            <input type="number"
                                   id="capacidad"
                                   name="capacidad"
                                   min="1"
                                   max="9"
                                   value="{{ $vehiculo->capacidad }}"
                                   required>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Sección 2: Foto del Vehículo -->
            <div class="form-section card">
                <div class="section-header">
                    <div class="section-number">2</div>
                    <h3>Vehicle Photo</h3>
                </div>

                    <div class="photo-section">
                    <div class="photo-upload-card">

                        {{-- Área de subida --}}
                        <div class="upload-area" id="uploadArea" style="display: none;">
                            <div class="upload-icon">📷</div>
                            <div class="upload-content">
                                <h4>Upload New Photo</h4>
                                <p>Click to browse or drag & drop</p>
                                <span class="file-types">PNG, JPG, JPEG up to 5MB</span>
                            </div>

                            <input type="file"
                                   id="foto"
                                   name="foto"
                                   accept="image/*"
                                   class="file-input">
                        </div>

                        {{-- Preview actual o nuevo --}}
                        <div class="preview-container show" id="previewContainer">

                            <div class="preview-card">
                                <img id="previewImage"
                                    src="{{ $vehiculo->foto_vehiculo ? asset('storage/'.$vehiculo->foto_vehiculo) : '' }}"
                                    alt="Vehicle Preview">

                                <div class="preview-overlay">
                                    <button type="button" class="btn remove-btn" id="removePhoto">
                                        ✕
                                    </button>
                                </div>
                            </div>

                            <p class="preview-text">
                                {{ $vehiculo->foto_vehiculo ? 'Current photo' : 'Photo preview' }}
                            </p>

                        </div>

                    </div>
                </div>
            </div>

            <!-- Acciones del Formulario -->
            <div class="form-actions">
                <a href="{{ route('driver.vehicles') }}" class="btn outline large">← Cancel</a>

                <button type="submit" class="btn neon large" id="submitBtn">
                    Update Vehicle
                </button>
            </div>

        </form>

    </div>

</main>

{{-- JS específico --}}
<script src="{{ asset('js/editVehicle.js') }}" defer></script>

@endsection
