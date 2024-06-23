<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonnelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->string('matricule_perso', 10)->nullable();
            $table->string('nom_perso', 250)->nullable();
            $table->string('sexe_perso', 15)->nullable();
            $table->string('prenom_perso', 250)->nullable();
            $table->string('lieu_nais', 10)->nullable();
            $table->string('statut_matrimonial', 40)->nullable();
            $table->string('date_nais', 10)->nullable();
            $table->integer('nbre_enfant')->default(0);
            $table->string('position_gest', 10)->nullable();
            $table->string('motif_sortie', 100)->nullable();
            $table->date('date_sortie')->nullable();
            $table->string('structure_perso', 100)->nullable();
            $table->string('structure_rattachee', 100)->nullable();
            $table->bigInteger('id_sg')->unsigned()->nullable();
            $table->bigInteger('id_type')->unsigned()->nullable();
            $table->bigInteger('id_arrond_origine')->unsigned()->nullable();
            $table->bigInteger('id_arrond_travail')->unsigned()->nullable();
            $table->integer('n1')->nullable();
            $table->integer('n2')->nullable();
            $table->string('anciennete', 255)->nullable();
            $table->string('date_embauche', 10)->nullable();
            $table->string('payroll', 255)->nullable();
            $table->string('direction', 255)->nullable();
            $table->string('fonction', 255)->nullable();
            $table->boolean('chef_dir')->nullable();
            $table->string('level', 255)->nullable();
            $table->string('categorie', 100)->nullable();
            $table->string('lieu_travail', 255)->nullable();
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
        Schema::dropIfExists('personnels');
    }
}
