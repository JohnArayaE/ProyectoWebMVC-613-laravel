<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magic_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_usuario');
            $table->char('token', 64)->unique();
            $table->dateTime('expira_en');
            $table->dateTime('usado_en')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magic_links');
    }
};