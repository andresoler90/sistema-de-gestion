<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAndRemoveToDocumentManagements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_managements', function (Blueprint $table) {
            $table->enum('valid',['S','N'])->after('client_documents_id')->default('N')->comment('Valido S:Si / N:No');
            $table->dropColumn('exist');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_managements', function (Blueprint $table) {
            $table->dropColumn('valid');
            $table->enum('exist',['S','N'])->default('N')->comment('Existe S: Si N:No');
        });
    }
}
