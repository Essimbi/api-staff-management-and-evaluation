<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiplomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diplomes', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->nullable();
            $table->string('date_obtention')->nullable();
            $table->string('domaine')->nullable();
            $table->string('etablissement')->nullable();
            $table->string('option')->nullable();
            $table->bigInteger('id_ville')->unsigned()->nullable();
            $table->string('id_pays')->nullable();
            $table->string('statut')->nullable();
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
        Schema::dropIfExists('diplomes');
    }
}
