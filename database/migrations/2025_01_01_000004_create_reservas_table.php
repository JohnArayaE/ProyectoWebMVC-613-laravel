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
    Schema::create('reservas', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('id_ride');
        $table->unsignedBigInteger('id_pasajero');
        $table->enum('estado', [
            'PENDIENTE',
            'ACEPTADA',
            'RECHAZADA',
            'CANCELADA',
            'COMPLETADA'
        ])->default('PENDIENTE');
        $table->integer('cantidad_espacios')->default(1);

        // Un pasajero no puede tener dos reservas para el mismo ride
        $table->unique(['id_ride', 'id_pasajero'], 'uk_reserva_unica');

        // Index por estado
        $table->index('estado', 'idx_estado');

        // FK a rides
        $table->foreign('id_ride', 'fk_reserva_ride')
            ->references('id')->on('rides')
            ->onDelete('cascade')
            ->onUpdate('cascade');

        // FK a usuarios (pasajero)
        $table->foreign('id_pasajero', 'fk_reserva_pasajero')
            ->references('id')->on('usuarios')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
