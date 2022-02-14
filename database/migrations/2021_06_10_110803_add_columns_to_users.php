<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->after('name')->comment('nickname del usuario');
            $table->softDeletes()->after('updated_at');
            $table->string('phone')->after('password')->comment('Teléfono del cliente');
            $table->bigInteger('clients_id')->after('phone')->nullable()->unsigned()->default(1)->comment('Id del cliente al que pertenece el usuario');
            $table->foreign('clients_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('deleted_at');
            $table->dropColumn('phone');
            $table->dropForeign(['clients_id']);
            $table->dropColumn('clients_id');
        });
    }
}
