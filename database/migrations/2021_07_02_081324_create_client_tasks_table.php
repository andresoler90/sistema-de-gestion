<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('clients_id')->comment('Cliente responsable de la tarea')->unsigned();
            $table->foreign('clients_id')->references('id')->on('clients');
            $table->bigInteger('stage_tasks_id')->comment('Responsable de la asociacion')->unsigned();
            $table->foreign('stage_tasks_id')->references('id')->on('stage_tasks');
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
        Schema::dropIfExists('client_tasks');
    }
}
