<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToRegisterEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_events', function (Blueprint $table) {
            $table->bigInteger('register_tasks_id')->nullable()->after('states_id')->unsigned();
            $table->foreign('register_tasks_id')->references('id')->on('register_tasks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('register_events', function (Blueprint $table) {
            $table->dropForeign(['register_tasks_id']);
            $table->dropColumn('register_tasks_id');
        });
    }
}
