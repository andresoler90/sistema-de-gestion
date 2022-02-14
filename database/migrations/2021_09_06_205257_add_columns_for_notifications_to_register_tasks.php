<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForNotificationsToRegisterTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_tasks', function (Blueprint $table) {

            $table->enum('analyst_notification',['S','N'])->default('N')->after('management_status')->comment('Se le notifico al analista, S:SI / N:NO');
            $table->enum('coordinator_notification',['S','N'])->default('N')->after('analyst_notification')->comment('Se le notifico al coordinador, S:SI / N:NO');

            $table->integer('analyst_time')->default(5)->after('coordinator_notification')->comment('Tiempo que puede tardar un analista cerrando una tarea antes de que se le notifique al propio analista');
            $table->integer('coordinator_time')->default(5)->after('analyst_time')->comment('Tiempo que puede tardar un analista cerrando una tarea antes de que se le notifique al coordinador');
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
            $table->dropColumn('analyst_notification');
            $table->dropColumn('coordinator_notification');
            $table->dropColumn('analyst_time');
            $table->dropColumn('coordinator_time');

        });
    }
}
