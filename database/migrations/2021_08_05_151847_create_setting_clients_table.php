<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_clients', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('setting_field_id');
            $table->foreign('setting_field_id')->references('id')->on('setting_fields');

            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');

            $table->unsignedBigInteger('created_user_id')->nullable()->comment('Usuario Creador');
            $table->foreign('created_user_id')->references('id')->on('users');

            $table->unsignedBigInteger('updated_user_id')->nullable()->comment('Usuario modificador');
            $table->foreign('updated_user_id')->references('id')->on('users');

            $table->boolean('status')->default(0)->comment('Estado 1 => Activo/ 0 => Inactivo');
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
        Schema::dropIfExists('setting_clients');
    }
}
