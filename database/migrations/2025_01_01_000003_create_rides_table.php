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
    Schema::create('rides', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('id_chofer');
        $table->unsignedBigInteger('id_vehiculo');
        $table->string('nombre_ride', 120);
        $table->string('lugar_salida', 255);
        $table->string('lugar_llegada', 255);
        $table->time('hora');
        $table->enum('dia_semana', ['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO','DOMINGO']);
        $table->decimal('costo', 10, 2);
        $table->integer('espacios_totales');
        $table->integer('espacios_disponibles');
        $table->enum('estado', ['ACTIVO','CANCELADO','COMPLETADO'])->default('ACTIVO');

        // Indexes
        $table->index('hora', 'idx_fecha_hora');
        $table->index(['lugar_salida', 'lugar_llegada'], 'idx_lugares');
        $table->index('dia_semana', 'idx_dia_semana');

        // Foreign keys
        $table->foreign('id_chofer', 'fk_ride_chofer')
            ->references('id')->on('usuarios')
            ->onDelete('cascade')
            ->onUpdate('cascade');

        $table->foreign('id_vehiculo', 'fk_ride_vehiculo')
            ->references('id')->on('vehiculos')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
