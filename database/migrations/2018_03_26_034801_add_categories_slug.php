<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoriesSlug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories',function (Blueprint $table){
        	$table->string('slug',50)->nullable();
        	$table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('categories',function (Blueprint $table){
		    $table->dropUnique(['slug']);
	    	$table->dropColumn('slug');
	    });
    }
}
