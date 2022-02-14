<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_tasks_id')->comment('Id tareas del cliente')->unsigned();
            $table->foreign('client_tasks_id')->references('id')->on('client_tasks');
            $table->bigInteger('registers_id')->comment('Id de la solicitud')->unsigned();
            $table->foreign('registers_id')->references('id')->on('registers');
            $table->bigInteger('analyst_users_id')->comment('Id del analista')->unsigned();
            $table->foreign('analyst_users_id')->references('id')->on('users');
            $table->enum('priority',['CPC','CRI','ALT','MED','BAJ'])->comment('Prioridad de la tarea CPC: Crítica por Calidad / CRI:Crítica / ALT: Alta / MED: Media / BAJ: Baja');
            $table->date('start_date')->comment('Fecha de inicio');
            $table->date('end_date')->comment('Fecha de finalización');
            $table->enum('status',['ABI','CER','ATI','ATR'])->comment('Estado de la tarea ABI: Abierta / CER: Cerrada / ATI: A tiempo / ATR: Atrasada');
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
        Schema::dropIfExists('register_tasks');
    }
}
