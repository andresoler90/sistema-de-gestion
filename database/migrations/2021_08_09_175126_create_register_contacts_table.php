<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('register_id')->comment('Registro');
            $table->foreign('register_id')->references('id')->on('registers');
            $table->string('account_id')->nullable()->comment('Id Cuenta en SalesForce');
            $table->string('contact_id')->nullable()->comment('Id Contacto en SalesForce');
            $table->json('data_json')->nullable()->comment('Data consultada de API - Salesforce');

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
        Schema::dropIfExists('register_contacts');
    }
}
