<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToRegisterEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_events', function (Blueprint $table) {
            $table->enum('management',['PAR','PRO','CLI'])->default('PAR')->after('states_id')->comment('Usuario quien se encuentra gestionando la solicitud PAR: Par / PRO: Proveedor / CLI: Cliente');
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
            $table->dropColumn('management');
        });
    }
}
