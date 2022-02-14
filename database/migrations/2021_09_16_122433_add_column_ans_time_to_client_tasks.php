<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAnsTimeToClientTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_tasks', function (Blueprint $table) {
            $table->float('ans_time')->default(0)->after('created_users_id')->comment('Tiempo de ans en horas por tarea');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_tasks', function (Blueprint $table) {
            $table->dropColumn('ans_time');
        });
    }
}
