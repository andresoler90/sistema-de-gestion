<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->integer('analyst_time')->default(1)->after('review')->comment('Tiempo que puede tardar un analista cerrando una tarea antes de que se le notifique al propio analista');
            $table->integer('coordinator_time')->default(2)->after('analyst_time')->comment('Tiempo que puede tardar un analista cerrando una tarea antes de que se le notifique al coordinador');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('analyst_time');
            $table->dropColumn('coordinator_time');
        });
    }
}
