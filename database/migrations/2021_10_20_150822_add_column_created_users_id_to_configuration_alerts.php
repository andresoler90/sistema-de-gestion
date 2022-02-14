<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCreatedUsersIdToConfigurationAlerts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuration_alerts', function (Blueprint $table) {
            $table->bigInteger('created_users_id')->unsigned()->after('periodicity');
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
        Schema::table('configuration_alerts', function (Blueprint $table) {
            $table->dropColumn('created_users_id');
        });
    }
}
