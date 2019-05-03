<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullableOrderDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders',function (Blueprint $table){
        	$table->date('deliv_date')->nullable()->change();
	        $table->date('return_date')->nullable()->change();
	        $table->unsignedInteger('billing_id')->nullable()->change();
	        $table->unsignedInteger('site_id')->change();
        });

	    Schema::table('order_items',function (Blueprint $table){
		    $table->date('deliv_date')->change();
		    $table->date('return_date')->change();
	    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('orders',function (Blueprint $table){
		    $table->date('deliv_date')->change();
		    $table->date('return_date')->change();
		    $table->unsignedInteger('billing_id')->change();
		    $table->unsignedInteger('site_id')->nullable()->change();
	    });

	    Schema::table('order_items',function (Blueprint $table){
		    $table->date('deliv_date')->nullable()->change();
		    $table->date('return_date')->nullable()->change();
	    });
    }
}
