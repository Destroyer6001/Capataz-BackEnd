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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->string('Nombre');
            $table->integer('Tamano');
            $table->unsignedBigInteger('User_id');
            $table->foreign('User_id')->references('id')->on('users');
            $table->unsignedBigInteger('Cultivo_id');
            $table->foreign('Cultivo_id')->references('id')->on('cultivos');
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
        Schema::dropIfExists('lotes');
    }
};
