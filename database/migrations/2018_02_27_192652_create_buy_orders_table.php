<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->char('sku',20)->index();
            $table->integer('qty');
            $table->integer('max_amount');
            $table->date('deliv_date')->index();
            $table->text('details');
            $table->unsignedInteger('equipment_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('site_id')->nullable();
            $table->unsignedInteger('contractor_id');

            $table->foreign('equipment_id')->references('id')->on('pro_equipments');
            $table->foreign('site_id')->references('id')->on('buy_sites');
            $table->foreign('contractor_id')->references('id')->on('buy_contractors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buy_orders');
    }
}
