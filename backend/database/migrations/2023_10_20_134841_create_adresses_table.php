<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adresses', function (Blueprint $table) {
            $table->id();
            $table->string('zip_code')->comment('Código Postal');
            $table->string('street')->comment('Logradouro');
            $table->string('number')->nullable()->comment('Número da residência caso exista (Não é obrigatório)');
            $table->string('complement')->nullable()->comment('Complemento do endereço (Não é obrigatório)');
            $table->string('district')->comment('Comunidade ou Região de uma cidade');
            $table->string('city')->comment('cidade');
            $table->string('uf')->comment('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adresses');
    }
}
