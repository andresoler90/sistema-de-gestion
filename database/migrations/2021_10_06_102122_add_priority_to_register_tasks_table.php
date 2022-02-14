<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriorityToRegisterTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_tasks', function (Blueprint $table) {
            $table->enum('priority',['CPC','CPM','CRI','ALT','MED','BAJ'])->default('MED')->after('analyst_users_id')->comment('Prioridad de la tarea CPC: Crítica por Calidad / CPM: Crítica por Modificación/ CRI:Crítica / ALT: Alta / MED: Media / BAJ: Baja');
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
            $table->dropColumn('priority');
        });
    }
}
