<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaderships', function (Blueprint $table) {
            $table->id();
            $table->string('accountability', 300)->nullable();
            $table->string('result', 300)->nullable();
            $table->string('best', 300)->nullable();
            $table->string('edge', 300)->nullable();
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
        Schema::dropIfExists('leaderships');
    }
}
