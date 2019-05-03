<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites',function (Blueprint $table){
        	$table->string('zip',10);
        	$table->string('phone',50);
        	$table->string('contact',100);

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
		    $table->dropColumn('zip','phone','contact');
	    });
    }
}
