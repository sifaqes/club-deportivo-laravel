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
            $table->foreign('socio_id')->references('id')->on('socios');

            $table->unsignedBigInteger('pista_id');
            $table->foreign('pista_id')->references('id')->on('pistas');

            $table->string('socio')->references('id')->on('socios');
            $table->string('pista')->references('id')->on('pistas');
            $table->string('deporte')->references('id')->on('deportes');


            $table->dateTime('fecha');
            $table->time('hora');



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
