<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre del cliente');
            $table->string('phone')->comment('Teléfono del cliente');
            $table->string('email')->unique()->comment('Correo del cliente');
            $table->string('contact_person')->comment('Nombre de la persona de contacto');
            $table->bigInteger('countries_id')->unsigned()->comment('País del cliente');
            $table->foreign('countries_id')->references('id')->on('countries');
            $table->string('acronym',3)->unique()->comment('Acrónimo de 3 dígitos para la creación de solicitudes únicas');
            $table->bigInteger('created_users_id')->unsigned();
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
        Schema::dropIfExists('clients');
    }
}
