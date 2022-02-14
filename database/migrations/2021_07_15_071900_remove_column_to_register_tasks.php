<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnToRegisterTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_tasks', function (Blueprint $table) {
            $table->dropForeign(['analyst_users_id']);
            $table->dropColumn('analyst_users_id');
            $table->dropColumn('priority');
            $table->dropColumn('end_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('register_tasks', function (Blueprint $table) {
        //     $table->bigInteger('analyst_users_id')->comment('Id del analista')->unsigned();
        //     $table->foreign('analyst_users_id')->references('id')->on('users');
        //     $table->date('end_date')->comment('Fecha de finalización');
        //     $table->enum('priority',['CPC','CRI','ALT','MED','BAJ'])->comment('Prioridad de la tarea CPC: Crítica por Calidad / CRI:Crítica / ALT: Alta / MED: Media / BAJ: Baja');
        // });
    }
}
