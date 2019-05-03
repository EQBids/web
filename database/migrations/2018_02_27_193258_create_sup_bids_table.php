<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sup_bids', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->double('amount');
            $table->text('details');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('user_id');

            $table->foreign('supplier_id')->references('id')->on('sup_suppliers');
	        $table->foreign('order_id')->references('id')->on('buy_orders');
	        $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sup_bids');
    }
}
