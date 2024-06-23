<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('q1_a', 300)->nullable();
            $table->string('q1_b', 300)->nullable();
            $table->string('q2', 300)->nullable();
            $table->string('q3', 300)->nullable();
            $table->bigInteger('id_personnel')->unsigned()->nullable();
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
        Schema::dropIfExists('questions');
    }
}
