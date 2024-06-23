<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConcernesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concernes', function (Blueprint $table) {
            $table->id();
            $table->double('score', 8, 2)->nullable();
            $table->double('score_final')->nullable();
            $table->string('appreciation')->nullable();
            $table->bigInteger('id_campagne')->unsigned()->nullable();
            $table->foreign('id_campagne')->references('id')->on('campagnes')->onDelete('cascade');
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
        Schema::dropIfExists('concernes');
    }
}
