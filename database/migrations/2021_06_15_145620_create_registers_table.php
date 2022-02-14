<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registers', function (Blueprint $table) {
            $table->id();
            $table->enum('register_type',['L','I'])->comment('Tipo de registro L: Liviano / I:Integral');
            $table->bigInteger('provider_countries_id')->unsigned()->comment('País del proveedor a solicitar');
            $table->foreign('provider_countries_id')->references('id')->on('countries');
            $table->string('identification_type')->comment('Tipo de identificación');
            $table->string('identification_number')->comment('Número de identificación');
            $table->string('check_digit')->nullable()->comment('Dígito de verificación, solo para Colombia');
            $table->string('business_name')->comment('Razón social');
            $table->string('telephone_contact')->comment('Teléfono de contacto');
            $table->string('name_contact')->comment('Nombre de contacto');
            $table->string('email_contact')->comment('Correo de contacto');
            $table->enum('register_assumed_by',['C','P'])->comment('Usuario quien asume la solicitud C: Cliente / P: Proveedor');
            $table->bigInteger('requesting_users_id')->unsigned()->comment('Usuario solicitante');
            $table->foreign('requesting_users_id')->references('id')->on('users');
            $table->bigInteger('created_users_id')->unsigned()->comment('Usuario quien creo la solicitud en el sistema');
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
        Schema::dropIfExists('registers');
    }
}
