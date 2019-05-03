<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatrBidOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bid_order_item', function (Blueprint $table) {
            $table->unsignedInteger('bid_id');
            $table->unsignedInteger('order_item_id');
            $table->decimal('price');
            $table->decimal('dropoff_fee')->default(0);
	        $table->decimal('pickup_fee')->default(0);
	        $table->decimal('insurance')->default(0);
	        $table->date('deliv_date')->nullable();
	        $table->date('return_date')->nullable();
	        $table->text('notes')->nullable();

	        $table->foreign('bid_id')->references('id')->on('bids');
	        $table->foreign('order_item_id')->references('id')->on('order_items');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bid_order_item');
    }
}
