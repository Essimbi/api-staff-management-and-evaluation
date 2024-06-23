<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->string('theme_stage', 250)->nullable();
            $table->string('domaine', 250)->nullable();
            $table->string('institut', 250)->nullable();
            $table->integer('nbre_jour')->nullable();
            $table->string('localisation', 250)->nullable();
            $table->bigInteger('id_personnel')->unsigned()->nullable();
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
        Schema::dropIfExists('stages');
    }
}
