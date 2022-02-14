<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurableWordsClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configurable_words_clients', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('configurable_words_id')->unsigned();
            $table->foreign('configurable_words_id')->references('id')->on('configurable_words');

            $table->bigInteger('clients_id')->nullable()->unsigned()->comment('Id del cliente');
            $table->foreign('clients_id')->references('id')->on('clients');

            $table->string("name")->comment('Nombre que se mostrara al usuario');

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
        Schema::dropIfExists('configurable_words_clients');
    }
}
