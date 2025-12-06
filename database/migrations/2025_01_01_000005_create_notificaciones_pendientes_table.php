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
    Schema::create('notificaciones_pendientes', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('id_reserva');
        $table->unsignedBigInteger('id_chofer');
        $table->dateTime('enviada_en')->nullable();

        // Indexes
        $table->index('id_reserva', 'idx_notif_reserva');
        $table->index('id_chofer', 'idx_notif_chofer');

        // FK a reservas
        $table->foreign('id_reserva', 'fk_notificacion_reserva')
            ->references('id')->on('reservas')
            ->onDelete('cascade')
            ->onUpdate('cascade');

        // FK a usuarios (chofer)
        $table->foreign('id_chofer', 'fk_notificacion_chofer')
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
        Schema::dropIfExists('notificaciones_pendientes');
    }
};
