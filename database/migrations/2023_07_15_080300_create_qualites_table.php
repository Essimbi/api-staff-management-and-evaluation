<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qualites', function (Blueprint $table) {
            $table->id();
            $table->integer('creativite')->nullable();
            $table->integer('esprit_equipe')->nullable();
            $table->integer('adaptation')->nullable();
            $table->integer('relation')->nullable();
            $table->integer('communication')->nullable();
            $table->bigInteger('id_personnel')->unsigned()->nullable();
            $table->integer('responsable')->nullable();
            $table->bigInteger('id_campagne')->unsigned()->nullable();
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
        Schema::dropIfExists('qualites');
    }
}
