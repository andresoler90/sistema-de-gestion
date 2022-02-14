<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAndAddColumnToRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registers', function (Blueprint $table) {
            $table->dropForeign(['provider_countries_id']);
            $table->dropColumn('provider_countries_id');

            $table->bigInteger('countries_id')->unsigned()->after('register_type')->comment('País del proveedor a solicitar');
            $table->foreign('countries_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('registers', function (Blueprint $table) {
        //     $table->bigInteger('provider_countries_id')->unsigned();
        //     $table->foreign('provider_countries_id')->references('id')->on('countries');

        //     $table->dropForeign(['countries_id']);
        //     $table->dropColumn('countries_id');
        // });
    }
}
