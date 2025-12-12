@extends('layouts.driver')

@section('title', 'Add Vehicle')
@section('header-title', 'Add New Vehicle')

@section('css')
<link rel="stylesheet" href="{{ asset('css/createVehicle.css') }}">
@endsection

@section('content')
<main class="create-vehicle-main">

    <div class="form-hero simple">
        <div class="hero-content">
            <h1>Register Your Vehicle</h1>
            <p>Add your vehicle details to start offering rides and earning with Aventones</p>
        </div>
    </div>

    <div class="form-container">

        {{-- Mensaje dinámico --}}
        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        <form id="vehicleForm"
              method="POST"
              action="{{ route('driver.vehicles.store') }}"
              enctype="multipart/form-data"
              class="vehicle-form">

            @csrf

            <!-- Sección 1: Información Básica -->
            <div class="form-section card">
                <div class="section-header">
                    <div class="section-number">1</div>
                    <h3>Basic Information</h3>
                </div>

                <div class="form-grid">

                    <div class="field-group">
                        <div class="field">
                            <label for="placa" class="field-label">
                                <span class="label-text">License Plate *</span>
                            </label>
                            <input type="text"
                                   id="placa"
                                   name="placa"
                                   placeholder="ABC-123"
                                   required
                                   maxlength="20">
                        </div>

                        <div class="field">
                            <label for="color" class="field-label">
                                <span class="label-text">Color *</span>
                            </label>
                            <input type="text"
                                   id="color"
                                   name="color"
                                   placeholder="White"
                                   required
                                   maxlength="50">
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field">
                            <label for="marca" class="field-label">Brand *</label>
                            <input type="text"
                                   id="marca"
                                   name="marca"
                                   placeholder="Toyota"
                                   required
                                   maxlength="60">
                        </div>

                        <div class="field">
                            <label for="modelo" class="field-label">Model *</label>
                            <input type="text"
                                   id="modelo"
                                   name="modelo"
                                   placeholder="Corolla"
                                   required
                                   maxlength="60">
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field">
                            <label for="anio" class="field-label">Manufacturing Year *</label>
                            <input type="number"
                                   id="anio"
                                   name="anio"
                                   min="1980"
                                   max="{{ date('Y') + 1 }}"
                                   placeholder="2020"
                                   required>
                        </div>

                        <div class="field">
                            <label for="capacidad" class="field-label">Seating Capacity *</label>
                            <input type="number"
                                   id="capacidad"
                                   name="capacidad"
                                   min="1"
                                   max="9"
                                   placeholder="4"
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
                        <div class="upload-area" id="uploadArea">
                            <div class="upload-icon">📷</div>
                            <div class="upload-content">
                                <h4>Upload Vehicle Photo</h4>
                                <p>Click to browse or drag & drop</p>
                                <span class="file-types">PNG, JPG, JPEG up to 5MB</span>
                            </div>

                            <input type="file"
                                   id="foto"
                                   name="foto"
                                   accept="image/*"
                                   class="file-input">
                        </div>

                        {{-- Preview --}}
                        <div class="preview-container" id="previewContainer">
                            <div class="preview-card">
                                <img id="previewImage" alt="Vehicle preview">
                                <div class="preview-overlay">
                                    <button type="button" class="btn remove-btn" id="removePhoto">
                                        <span>✕</span>
                                    </button>
                                </div>
                            </div>
                            <p class="preview-text">Photo preview</p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Acciones del Formulario -->
            <div class="form-actions">
                <a href="{{ route('driver.vehicles') }}" class="btn outline large">
                    ← Cancel
                </a>

                <button type="submit" class="btn neon large" id="submitBtn">
                    Create Vehicle
                </button>
            </div>

        </form>

    </div>

</main>

{{-- JS específico --}}
<script src="{{ asset('js/createVehicle.js') }}" defer></script>

@endsection
