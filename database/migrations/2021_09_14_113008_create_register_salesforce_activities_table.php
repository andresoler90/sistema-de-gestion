<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterSalesforceActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_salesforce_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('register_id')->comment('Registro');
            $table->foreign('register_id')->references('id')->on('registers');
            $table->string('account')->nullable()->comment('Id Cuenta en SalesForce');
            $table->string('opportunity')->nullable()->comment('Id oportunidad en SalesForce');
            $table->string('activity')->nullable()->comment('Id actividad/tarea en SalesForce');
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
        Schema::dropIfExists('register_salesforce_activities');
    }
}
