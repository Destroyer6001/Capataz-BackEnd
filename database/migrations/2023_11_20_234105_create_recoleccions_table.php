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
        Schema::create('recoleccions', function (Blueprint $table) {
            $table->id();
            $table->date('Fecha');
            $table->integer('Precio');
            $table->integer('Cantidad');
            $table->integer('Total');
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
        Schema::dropIfExists('recoleccions');
    }
};
