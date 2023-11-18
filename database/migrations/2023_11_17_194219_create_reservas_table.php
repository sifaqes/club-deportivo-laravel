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
            $table->id();

            $table->unsignedBigInteger('socio_id');
            $table->unsignedBigInteger('pista_id');

            $table->string('nombre_r')->references('id')->on('socios');
            $table->string('pista_r')->references('id')->on('pistas');
            $table->string('deporte_r')->references('id')->on('deportes');
            $table->time('hora_reserva');

            $table->foreign('socio_id')->references('id')->on('socios');
            $table->foreign('pista_id')->references('id')->on('pistas');

            $table->timestamps();

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
