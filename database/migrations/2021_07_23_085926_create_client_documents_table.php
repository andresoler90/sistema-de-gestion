<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_documents', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('clients_id')->unsigned()->comment('ID del cliente');
            $table->foreign('clients_id')->references('id')->on('clients');
            $table->bigInteger('documents_id')->unsigned()->comment('ID del documento');
            $table->foreign('documents_id')->references('id')->on('documents');
            $table->enum('register_type',['L','I'])->comment('Tipo de registro L: Liviano / I:Integral');
            $table->enum('provider_type',['N','E'])->comment('Tipo de proveedor N: Nacional / E:Extranjero');
            $table->enum('document_type',['OP','OB'])->comment('Tipo de documento OP: Opcional / OB: obligatorio');
            $table->text('validity')->nullable()->comment('Vigencia');
            $table->text('commentary')->nullable()->comment('Comentario');
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
        Schema::dropIfExists('client_documents');
    }
}
