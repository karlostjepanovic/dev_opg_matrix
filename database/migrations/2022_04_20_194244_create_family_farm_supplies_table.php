<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamilyFarmSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_farm_supplies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('supply_id')->unsigned();
            $table->foreign('supply_id')
                ->references('id')
                ->on('supplies')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->bigInteger('family_farm_id')->unsigned();
            $table->foreign('family_farm_id')
                ->references('id')
                ->on('family_farms')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->unique(['supply_id', 'family_farm_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('family_farm_supplies');
    }
}
