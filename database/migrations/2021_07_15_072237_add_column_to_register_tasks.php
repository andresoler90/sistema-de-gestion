<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToRegisterTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_tasks', function (Blueprint $table) {
            $table->bigInteger('analyst_users_id')->after('registers_id')->nullable()->comment('Id del analista')->unsigned();
            $table->foreign('analyst_users_id')->references('id')->on('users');
            $table->enum('priority',['CPC','CRI','ALT','MED','BAJ'])->default('MED')->after('analyst_users_id')->comment('Prioridad de la tarea CPC: Crítica por Calidad / CRI:Crítica / ALT: Alta / MED: Media / BAJ: Baja');
            $table->date('end_date')->nullable()->after('start_date')->comment('Fecha de finalización');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('register_tasks', function (Blueprint $table) {
            $table->dropForeign(['analyst_users_id']);
            $table->dropColumn('analyst_users_id');
            $table->dropColumn('priority');
            $table->dropColumn('end_date');
        });
    }
}
