<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('registers_id')->comment('ID de la solicitud')->unsigned();
            $table->foreign('registers_id')->references('id')->on('registers');

            $table->bigInteger('register_tasks_id')->comment('ID de la tarea')->unsigned();
            $table->foreign('register_tasks_id')->references('id')->on('register_tasks');

            $table->bigInteger('client_documents_id')->comment('ID del documento')->unsigned();
            $table->foreign('client_documents_id')->references('id')->on('client_documents');

            $table->enum('satisfy',['S','N'])->default('N')->comment('Cumple, S:Si / N:No');
            $table->text('outcome')->nullable()->comment('Resultado de la verificación');

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
        Schema::dropIfExists('verifications');
    }
}
