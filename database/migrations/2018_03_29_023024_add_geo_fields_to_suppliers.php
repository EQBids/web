<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGeoFieldsToSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers',function (Blueprint $table){
        	$table->unsignedInteger('country_id')->nullable();
	        $table->unsignedInteger('metro_id')->nullable();
	        $table->unsignedInteger('state_id')->nullable();
	        $table->unsignedInteger('city_id')->nullable();

	        $table->foreign('country_id')->references('id')->on('countries');
	        $table->foreign('metro_id')->references('id')->on('metros');
	        $table->foreign('state_id')->references('id')->on('states');
	        $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('suppliers',function (Blueprint $table){

		    $table->dropForeign(['country_id']);
		    $table->dropForeign(['metro_id']);
		    $table->dropForeign(['state_id']);
		    $table->dropForeign(['city_id']);

	    	$table->dropColumn('country_id');
		    $table->dropColumn('metro_id');
		    $table->dropColumn('state_id');
		    $table->dropColumn('city_id');


	    });
    }
}
