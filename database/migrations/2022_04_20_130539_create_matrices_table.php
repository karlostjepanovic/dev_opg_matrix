<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matrices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('area');
            $table->boolean('locked')->default(false);
            $table->bigInteger('culture_id')->unsigned();
            $table->foreign('culture_id')
                ->references('id')
                ->on('cultures')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->bigInteger('cadastral_parcel_id')->unsigned();
            $table->foreign('cadastral_parcel_id')
                ->references('id')
                ->on('cadastral_parcels')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->unique(['name', 'culture_id', 'cadastral_parcel_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matrices');
    }
}
