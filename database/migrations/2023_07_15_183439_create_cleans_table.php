<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCleansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cleans', function (Blueprint $table) {
            $table->id();
            $table->string('office', 300)->nullable();
            $table->string('cars', 300)->nullable();
            $table->string('employees', 300)->nullable();
            $table->integer('note')->nullable();
            $table->bigInteger('id_personnel')->unsigned()->nullable();
            $table->integer('responsable')->nullable();
            $table->integer('id_campagne')->nullable();
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
        Schema::dropIfExists('cleans');
    }
}
