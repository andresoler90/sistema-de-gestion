<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToStageTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stage_tasks', function (Blueprint $table) {
            $table->string('label')->nullable()->after('name')->comment('Nombre que se mostrara en la etiqueta al usuario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stage_tasks', function (Blueprint $table) {
            $table->dropColumn('label');
        });
    }
}
