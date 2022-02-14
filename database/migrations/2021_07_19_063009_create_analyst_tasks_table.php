<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalystTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analyst_tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('stage_tasks_id')->unsigned()->comment('ID del analista');
            $table->foreign('stage_tasks_id')->references('id')->on('stage_tasks');
            $table->bigInteger('analyst_id')->unsigned()->comment('ID de la tarea');
            $table->foreign('analyst_id')->references('id')->on('users');
            $table->bigInteger('created_users_id')->unsigned();
            $table->foreign('created_users_id')->references('id')->on('users');
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
        Schema::dropIfExists('analyst_tasks');
    }
}
