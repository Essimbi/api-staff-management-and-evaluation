<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagerialesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manageriales', function (Blueprint $table) {
            $table->id();
            $table->integer('auto_eval');
            $table->integer('n1_eval');
            $table->integer('result_attendu');
            $table->integer('realisation');
            $table->integer('note');
            $table->bigInteger('id_personnel')->unsigned();
            $table->bigInteger('id_campagne')->unsigned();
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
        Schema::dropIfExists('manageriales');
    }
}
