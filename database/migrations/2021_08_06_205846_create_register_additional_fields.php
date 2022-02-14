<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterAdditionalFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_additional_fields', function (Blueprint $table) {
            $db = DB::connection('mysql2')->getDatabaseName();
            $table->id();
            $table->unsignedBigInteger('register_id');
            $table->foreign('register_id')->references('id')->on('registers');

            $table->unsignedInteger('act1_id')->nullable();
            $table->foreign('act1_id')->references('act1_id')->on(new Expression($db . '.act1_masters'));

            $table->unsignedInteger('act2_id')->nullable();
            $table->foreign('act2_id')->references('act2_id')->on(new Expression($db . '.act2_activities'));

            $table->unsignedInteger('act3_id')->nullable();
            $table->foreign('act3_id')->references('act3_id')->on(new Expression($db . '.act3_grouplists'));

            $table->unsignedInteger('act10_id')->nullable();
            $table->foreign('act10_id')->references('act10_id')->on(new Expression($db . '.act10_typeactivities'));

            $table->unsignedInteger('act11_id')->nullable();
            $table->foreign('act11_id')->references('act11_id')->on(new Expression($db . '.act11_category'));

            $table->string('code_activity')->nullable()->comment('Código actividad');
            $table->string('code_intern')->nullable()->comment('Código Interno');
            $table->boolean('experience_verify')->nullable()->comment('Experiencia Verificada');

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
        Schema::dropIfExists('register_additional_fields');
    }
}
