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

            $table->foreignId('socio_id')->constrained();
            $table->foreignId('pista_id')->constrained();

            $table->foreignId('user_id')->constrained('users');

            $table->string('socio')->references('id')->on('socios');
            $table->string('pista')->references('id')->on('pistas');
            $table->string('deporte')->references('id')->on('deportes');

            $table->date('fecha');

            $table->time('hora_inicio');
            $table->time('horaFin');

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
