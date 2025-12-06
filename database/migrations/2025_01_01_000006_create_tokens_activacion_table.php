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
    Schema::create('tokens_activacion', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('id_usuario');
        $table->char('token', 64);  
        $table->dateTime('expira_en');
        $table->dateTime('usado_en')->nullable();

        $table->unique('token', 'uk_token');

        // FK → usuarios
        $table->foreign('id_usuario', 'fk_token_usuario')
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
        Schema::dropIfExists('tokens_activacion');
    }
};
