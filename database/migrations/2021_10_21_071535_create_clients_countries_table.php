<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_countries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('clients_id')->unsigned()->comment('Id del cliente');
            $table->foreign('clients_id')->references('id')->on('clients');
            $table->bigInteger('countries_id')->unsigned()->comment('Id del país del cliente');
            $table->foreign('countries_id')->references('id')->on('countries');
            $table->bigInteger('created_users_id')->unsigned()->comment('Usuario quien creo el registro');
            $table->foreign('created_users_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::select("ALTER TABLE clients_countries COMMENT = 'Países del los clientes'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients_countries');
    }
}
