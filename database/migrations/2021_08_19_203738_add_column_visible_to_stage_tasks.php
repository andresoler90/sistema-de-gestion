<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnVisibleToStageTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stage_tasks', function (Blueprint $table) {
            $table->enum('visible',['S','N'])->after('stages_id')->default('S')->comment('Tarea visible, sera tenido en cuenta al momento de verificar la documentacion asociada');
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
            $table->dropColumn('visible');
        });
    }
}
