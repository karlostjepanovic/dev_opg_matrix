<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_amounts', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->bigInteger('amount_id')->unsigned();
            $table->foreign('amount_id')
                ->references('id')
                ->on('amounts')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->bigInteger('process_id')->unsigned();
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unique(['amount_id', 'process_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_amounts');
    }
}
