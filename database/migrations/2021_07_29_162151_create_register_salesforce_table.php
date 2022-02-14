<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterSalesforceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_salesforce', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('register_id');
            $table->foreign('register_id')->references('id')->on('registers');

            $table->enum('status',['P','E'])->comment('Estado de ejecucion P => Pendiente E => Ejecutado');
            $table->string('account')->nullable()->comment('ID de Cuenta SalesForce');
            $table->string('contact')->nullable()->comment('ID de Contacto SalesForce');
            $table->string('opportunity')->nullable()->comment('ID de Oprtunidad SalesForce');
            $table->json('response_ws')->nullable()->comment('Response json de consumo ws');;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('register_salesforce');
    }
}
