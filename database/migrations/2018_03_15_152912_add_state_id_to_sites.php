<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStateIdToSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('sites',function (Blueprint $table){
		    $table->unsignedInteger('state_id')->nullable();
		    $table->foreign('state_id')->references('id')->on('states');
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
		    $table->dropForeign(['state_id']);
		    $table->dropColumn('state_id');

	    });
    }
}
