<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_managements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trackings_id')->comment('Id del seguimiento')->unsigned();
            $table->foreign('trackings_id')->references('id')->on('trackings');
            $table->bigInteger('client_documents_id')->comment('Id del documento')->unsigned();
            $table->foreign('client_documents_id')->references('id')->on('client_documents');
            $table->enum('exist',['S','N'])->default('N')->comment('Existe S:Si / N:No');
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
        Schema::dropIfExists('document_managements');
    }
}
