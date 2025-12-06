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
    Schema::create('vehiculos', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('id_chofer');
        $table->string('placa', 20);
        $table->string('color', 40);
        $table->string('marca', 60);
        $table->string('modelo', 60);
        $table->year('anio');
        $table->integer('capacidad');
        $table->string('foto_vehiculo', 255)->nullable();

        $table->foreign('id_chofer', 'fk_vehiculo_chofer')
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
        Schema::dropIfExists('vehiculos');
    }
};
