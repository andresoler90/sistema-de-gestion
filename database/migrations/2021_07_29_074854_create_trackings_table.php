<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('registers_id')->comment('Id de la solicitud')->unsigned();
            $table->foreign('registers_id')->references('id')->on('registers');
            $table->date('date')->nullable()->comment('Fecha del seguimiento');
            $table->string('contact_name')->nullable()->comment('Nombre del contacto');
            $table->string('phone')->nullable()->comment('Teléfono');
            $table->string('email')->nullable()->comment('Correo');
            $table->string('type_contact')->nullable()->comment('Tipo de contacto');
            $table->text('observations')->nullable()->comment('Observaciones');
            $table->text('consecutive_code')->nullable()->comment('Código consecutivo');
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
        Schema::dropIfExists('trackings');
    }
}
