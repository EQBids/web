<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBillingToSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites',function (Blueprint $table){
        	$table->unsignedInteger('billing_id')->nullable();
        	$table->foreign('billing_id')->references('id')->on('billings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('sites',function (Blueprint $table){
			$table->dropForeign(['billing_id']);
			$table->dropColumn('billing_id');
	    });
    }
}
