<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSituationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('situations', function (Blueprint $table) {
            $table->id();
            $table->string('date_recrutement', 10)->nullable();
            $table->string('nature_acte', 45)->nullable();
            $table->string('statut_acte', 45)->nullable();
            $table->bigInteger('id_corps')->unsigned();
            $table->bigInteger('id_grade')->unsigned();
            $table->bigInteger('id_categorie')->unsigned();
            $table->boolean('nommination')->nullable();
            $table->integer('age_dep_retraite')->nullable();
            $table->date('date_dep_retraite')->nullable();
            $table->string('poste_actuel', 50)->nullable();
            $table->string('niv_instruction', 45)->nullable();
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
        Schema::dropIfExists('situations');
    }
}
