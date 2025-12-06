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
    Schema::create('busquedas', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('usuario_id');
        $table->string('lugar_salida', 255);
        $table->string('lugar_llegada', 255);
        $table->integer('cantidad_resultados');
        $table->dateTime('fecha_busqueda');

        // Index para acelerar reportes por fecha
        $table->index('fecha_busqueda', 'idx_fecha_busqueda');

        // FK → usuarios
        $table->foreign('usuario_id', 'fk_busqueda_usuario')
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
        Schema::dropIfExists('busquedas');
    }
};
