<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_managements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('registers_id')->comment('Id de la solicitud')->unsigned();
            $table->foreign('registers_id')->references('id')->on('registers');
            $table->bigInteger('stages_id')->comment('Id de la etapa')->unsigned();
            $table->foreign('stages_id')->references('id')->on('stages');
            $table->enum('management_type',['T','C','A'])->comment('Tipo de gestión T:Teléfono / C:Correo / A:Ambos');
            $table->text('observations')->nullable()->comment('Resultado de la verificación');
            $table->enum('decision',['SN','ES','CS'])->comment('decisión SN: Seguimiento nuevo / ES:Enviar soporte al proveedor / CS:Cancelar solicitud');
            $table->enum('status',['A','C'])->default('A')->comment('Estado A: Abierto / C:Cerrado');
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
        Schema::dropIfExists('register_managements');
    }
}
