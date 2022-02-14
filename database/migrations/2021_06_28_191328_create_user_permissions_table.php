<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('permissions_id')->comment('Permiso que se le brinda al usuario')->unsigned();
            $table->foreign('permissions_id')->references('id')->on('permissions');
            $table->bigInteger('users_id')->comment('Usuario al que se le brinda el permiso')->unsigned();
            $table->foreign('users_id')->references('id')->on('users');
            $table->bigInteger('created_users_id')->comment('Responsable de la asociación')->unsigned();
            $table->foreign('created_users_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_permissions');
    }
}
