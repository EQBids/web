<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sup_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->char('status',50);
            $table->text('details');
            $table->unsignedInteger('equipment_id');
            $table->unsignedInteger('supplier_id');

            $table->foreign('equipment_id')->references('id')->on('pro_equipments');
            $table->foreign('supplier_id')->references('id')->on('sup_suppliers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sup_inventories');
    }
}
