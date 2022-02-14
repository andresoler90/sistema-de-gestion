<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToRegisterTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_tasks', function (Blueprint $table) {
            $table->bigInteger('created_users_id')->default(1)->after('status')->unsigned();
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
        Schema::table('register_tasks', function (Blueprint $table) {
            $table->dropForeign(['created_users_id']);
            $table->dropColumn('created_users_id');
        });
    }
}
