<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAndRemoveToClientDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_documents', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->bigInteger('stage_tasks_id')->default(5)->after('commentary')->comment('ID de la tarea')->unsigned();
            $table->foreign('stage_tasks_id')->references('id')->on('stage_tasks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_documents', function (Blueprint $table) {
            $table->dropForeign(['stage_tasks_id']);
            $table->dropColumn('stage_tasks_id');

            $table->enum('category',['B','E','F','D'])->default('B')->after('validity')->comment('Categoría del documento, B: Básico / E: Experiencia / F: Financiera / D: Documentos del cliente');

        });
    }
}
