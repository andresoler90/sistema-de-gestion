<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Nombre de la plantilla');
            $table->bigInteger('countries_id')->unsigned()->comment('País para el que aplica el precio');
            $table->foreign('countries_id')->references('id')->on('countries');
            $table->enum('register_assumed_by', ['C', 'P'])->comment('Usuario quien asume la solicitud C: Cliente / P: Proveedor');
            $table->bigInteger('clients_id')->nullable()->unsigned()->default(1)->comment('Id del cliente al que pertenece el usuario');
            $table->foreign('clients_id')->references('id')->on('clients');
            $table->enum('register_type', ['L', 'I', 'B'])->comment('Tipo de registro L: Liviano / I:Integral / B: Basico');
            $table->enum('provider_type', ['N', 'I'])->nullable()->comment('Tipo de proveedor N: Nacional / I:Internacional / B: Basico');
            $table->enum('currency', ['COP', 'PEN', 'MXN', 'CLP', 'BRL','USD'])->comment('Tipo de moneda');
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
        Schema::dropIfExists('price_lists');
    }
}
