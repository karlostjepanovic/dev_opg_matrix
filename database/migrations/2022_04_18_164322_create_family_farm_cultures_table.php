<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamilyFarmCulturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_farm_cultures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('family_farm_id')->unsigned();
            $table->foreign('family_farm_id')
                ->references('id')
                ->on('family_farms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->bigInteger('culture_id')->unsigned();
            $table->foreign('culture_id')
                ->references('id')
                ->on('cultures')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->unique(['family_farm_id', 'culture_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('family_farm_cultures');
    }
}
