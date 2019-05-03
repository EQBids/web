<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->integer('qty')->default(1);
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedInteger('equipment_id');
            $table->dateTime('deliv_date')->nullable();
	        $table->dateTime('return_date')->nullable();
	        $table->text('notes')->nullable();

	        $table->foreign('order_id')->references('id')->on('orders');
	        $table->foreign('equipment_id')->references('id')->on('equipments');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
