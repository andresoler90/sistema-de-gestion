<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStageTasksToTrackings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trackings', function (Blueprint $table) {
            $table->bigInteger('stage_tasks_id')->default(4)->after('registers_id')->comment('Id de la tarea')->unsigned();
            $table->foreign('stage_tasks_id')->references('id')->on('stage_tasks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trackings', function (Blueprint $table) {
            $table->dropForeign(['stage_tasks_id']);
            $table->dropColumn('stage_tasks_id');
        });
    }
}
