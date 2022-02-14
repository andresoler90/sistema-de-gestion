<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToRegisterTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_tasks', function (Blueprint $table) {
            $table->enum('status',['A','C'])->default('A')->after('end_date')->comment('Estado de la tarea A: Abierta / C: Cerrada');
            $table->enum('management_status',['ATI','ATR'])->default('ATI')->after('status')->comment('Estado de gestión ATI: A tiempo / ATR: Atrasada');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('register_tasks', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('management_status');
        });
    }
}
