<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLivingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('livings', function (Blueprint $table) {
            $table->id();
            $table->string('integrity', 300)->nullable();
            $table->string('courage', 300)->nullable();
            $table->string('creativity', 300)->nullable();
            $table->string('value', 300)->nullable();
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
        Schema::dropIfExists('livings');
    }
}
