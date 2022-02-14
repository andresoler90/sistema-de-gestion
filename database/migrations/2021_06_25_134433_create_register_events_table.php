<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_events', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('registers_id')->comment('ID de la solicitud')->unsigned();
            $table->foreign('registers_id')->references('id')->on('registers');
            $table->bigInteger('states_id')->unsigned()->comment('Estado de la solicitud');
            $table->foreign('states_id')->references('id')->on('states');
            $table->enum('management',['PAR','PRO'])->comment('Usuario quien se encuentra gestionando la solicitud PAR: Par / PRO: proveedor');
            $table->text('description')->comment('Descripción del evento');
            $table->bigInteger('created_users_id')->comment('Responsable del cambio')->unsigned();
            $table->foreign('created_users_id')->references('id')->on('users');
            $table->timestamps();
            $table->dateTime('finished_at')->nullable()->comment('Fecha de finalización del evento');
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
        Schema::dropIfExists('register_events');
    }
}
