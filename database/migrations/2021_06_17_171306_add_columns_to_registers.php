<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToRegisters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registers', function (Blueprint $table) {
            $table->string('code')->unique()->after('id')->comment('Código de la solicitud');
            $table->bigInteger('states_id')->unsigned()->default(1)->after('code')->comment('Estado de la solicitud');
            $table->foreign('states_id')->references('id')->on('states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registers', function (Blueprint $table) {
            $table->dropForeign(['states_id']);
            $table->dropColumn('states_id');
            $table->dropColumn('code');
        });
    }
}
