<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNomminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nomminations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_rang')->unsigned()->nullable();
            $table->bigInteger('id_nh')->unsigned()->nullable();
            $table->string('fonction', 150)->nullable();
            $table->string('ref_acte', 50)->nullable();
            $table->string('date_nommination', 10)->nullable();
            $table->bigInteger('id_personnel')->nullable();
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
        Schema::dropIfExists('nomminations');
    }
}
