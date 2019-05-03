<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountryToSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('sites',function (Blueprint $table){
		    $table->string('contact',100)->nullable()->change();
		    $table->unsignedInteger('country_id');
		    $table->string('zip',10)->nullable()->change();
		    $table->foreign('country_id')->references('id')->on('countries');
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
		    $table->string('contact',100)->change();
		    $table->char('zip',10)->change();
		    $table->dropForeign(['country_id']);
		    $table->dropColumn('country_id');
	    });
    }
}
