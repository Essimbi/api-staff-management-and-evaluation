<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learnings', function (Blueprint $table) {
            $table->id();
            $table->string('training', 300)->nullable();
            $table->string('capability', 300)->nullable();
            $table->string('improvement', 300)->nullable();
            $table->string('grow', 300)->nullable();
            $table->integer('note')->nullable();
            $table->bigInteger('id_personnel')->unsigned()->nullable();
            $table->integer('responsable')->nullable();
            $table->integer('id_campagne')->nullable();
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
        Schema::dropIfExists('learnings');
    }
}
