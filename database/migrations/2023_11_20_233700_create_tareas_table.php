<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->date('FechaDeAsignacion');
            $table->string('Estado');
            $table->unsignedBigInteger('Lote_id');
            $table->foreign('Lote_id')->references('id')->on('lotes');
            $table->unsignedBigInteger('Labor_id');
            $table->foreign('Labor_id')->references('id')->on('labors');
            $table->unsignedBigInteger('User_id');
            $table->foreign('User_id')->references('id')->on('users');
            $table->bigInteger('Empleado_id');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tareas');
    }
};
