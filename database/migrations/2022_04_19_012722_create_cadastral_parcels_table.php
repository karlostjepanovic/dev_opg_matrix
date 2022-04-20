<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCadastralParcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadastral_parcels', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('arcode')->unique();
            $table->float('area');
            $table->string('community');
            $table->bigInteger('family_farm_id')->unsigned();
            $table->foreign('family_farm_id')
                ->references('id')
                ->on('family_farms')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->unique(['number', 'community']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cadastral_parcels');
    }
}
