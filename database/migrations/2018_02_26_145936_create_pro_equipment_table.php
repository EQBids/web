<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_equipments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
	        $table->char('name');
	        $table->char('status',50)->index();
	        $table->unsignedInteger('brand_id');

	        $table->text('details');
	        $table->unique(['name','brand_id']);

	        $table->foreign('brand_id')->references('id')->on('pro_brands');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pro_equipments');
    }
}
