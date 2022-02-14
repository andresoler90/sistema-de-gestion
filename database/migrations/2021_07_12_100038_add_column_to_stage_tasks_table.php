<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToStageTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stage_tasks', function (Blueprint $table) {
            $table->bigInteger('created_users_id')->default(1)->after('estimated_time')->unsigned();
            $table->foreign('created_users_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stage_tasks', function (Blueprint $table) {
            $table->dropForeign(['created_users_id']);
            $table->dropColumn('created_users_id');
        });
    }
}
