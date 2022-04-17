<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplyCulturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_cultures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('supply_id')->unsigned();
            $table->foreign('supply_id')
                ->references('id')
                ->on('supplies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->bigInteger('culture_id')->unsigned();
            $table->foreign('culture_id')
                ->references('id')
                ->on('cultures')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unique(['supply_id', 'culture_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supply_cultures');
    }
}
