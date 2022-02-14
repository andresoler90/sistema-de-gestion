<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurationAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuration_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('command');
            $table->unsignedBigInteger('clients_id')->comment('Id del cliente');
            $table->foreign('clients_id')->references('id')->on('clients');
            $table->enum('periodicity', ['none', 'daily', 'weekly', 'monthly']);
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
        Schema::dropIfExists('configuration_alerts');
    }
}
