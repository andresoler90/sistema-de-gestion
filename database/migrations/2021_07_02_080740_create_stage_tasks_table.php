<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStageTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stage_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre de la tarea');
            $table->bigInteger('stages_id')->comment('Etapa de la tarea')->unsigned();
            $table->foreign('stages_id')->references('id')->on('stages');
            $table->double('estimated_time',8,2)->comment('Tiempo estimado de cada tarea');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stage_tasks');
    }
}
