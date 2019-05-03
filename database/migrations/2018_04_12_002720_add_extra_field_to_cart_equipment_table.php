<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldToCartEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('cart_equipment', function (Blueprint $table) {
	    	$table->date('from')->nullable();
	    	$table->date('to')->nullable();
	    	$table->smallInteger('qty')->default(0);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('cart_equipment', function (Blueprint $table) {
		    $table->dropColumn('from','to','qty');
	    });
    }
}
