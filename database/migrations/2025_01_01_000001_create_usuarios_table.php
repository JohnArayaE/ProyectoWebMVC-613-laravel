<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('usuarios', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('nombre', 80);
        $table->string('apellido', 80);
        $table->string('cedula', 32);
        $table->date('fecha_nacimiento');
        $table->string('correo', 191);
        $table->string('telefono', 32)->nullable();
        $table->string('foto_ruta', 255)->nullable();
        $table->string('contrasena_hash', 255);
        $table->enum('rol', ['PASAJERO','CHOFER','ADMIN'])->default('PASAJERO');
        $table->enum('estado', ['PENDIENTE','ACTIVO','INACTIVO'])->default('PENDIENTE');

        $table->unique('correo', 'uk_usuarios_correo');
        $table->unique('cedula', 'uk_usuarios_cedula');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
