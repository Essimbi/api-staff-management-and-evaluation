<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objectifs', function (Blueprint $table) {
            $table->id();
            $table->string('specifique', 250)->nullable();
            $table->string('operationnel', 250)->nullable();
            $table->string('indicateur', 100)->nullable();
            $table->bigInteger('id_personnel')->unsigned()->nullable();
            $table->bigInteger('id_campagne')->unsigned()->nullable();
            $table->integer('valeur');
            $table->integer('cible');
            $table->string('statut', 255);
            $table->string('source_collecte', 250)->nullable();
            $table->string('frequence', 250)->nullable();
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
        Schema::dropIfExists('objectifs');
    }
}
