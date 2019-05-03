<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NormalizeStatusFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('countries',function (Blueprint $table){
			$table->dropColumn('status');
        });

	    Schema::table('states',function (Blueprint $table){
		    $table->dropColumn('status');

	    });

	    Schema::table('cities',function (Blueprint $table){
		    $table->dropColumn('status');
	    });

	    Schema::table('equipment_types',function (Blueprint $table){
		    $table->dropColumn('status');
	    });

	    Schema::table('user_status',function (Blueprint $table){
		    $table->dropColumn('status');
	    });

	    Schema::table('suppliers',function (Blueprint $table){
		    $table->dropColumn('status');
	    });

	    Schema::table('inventories',function (Blueprint $table){
		    $table->dropColumn('status');
	    });

	    Schema::table('languages',function (Blueprint $table){
		    $table->dropColumn('status');
	    });

	    Schema::table('brands',function (Blueprint $table){
		    $table->dropColumn('status');
	    });

	    Schema::table('branches',function (Blueprint $table){
		    $table->dropColumn('status');
	    });

	    Schema::table('metros',function (Blueprint $table){
		    $table->dropColumn('status');
	    });

	    Schema::table('equipments',function (Blueprint $table){
		    $table->dropColumn('status');
	    });


	    //insert the new columns
	    Schema::table('countries',function (Blueprint $table){
		    $table->integer('status');
	    });

	    Schema::table('states',function (Blueprint $table){
		    $table->integer('status');

	    });

	    Schema::table('cities',function (Blueprint $table){
		    $table->integer('status');
	    });

	    Schema::table('equipment_types',function (Blueprint $table){
		    $table->integer('status');
	    });

	    Schema::table('user_status',function (Blueprint $table){
		    $table->integer('status');
	    });

	    Schema::table('suppliers',function (Blueprint $table){
		    $table->integer('status');
	    });

	    Schema::table('inventories',function (Blueprint $table){
		    $table->integer('status');
	    });

	    Schema::table('languages',function (Blueprint $table){
		    $table->integer('status');
	    });

	    Schema::table('brands',function (Blueprint $table){
		    $table->integer('status');
	    });

	    Schema::table('branches',function (Blueprint $table){
		    $table->integer('status');
	    });

	    Schema::table('metros',function (Blueprint $table){
		    $table->integer('status');
	    });

	    Schema::table('equipments',function (Blueprint $table){
		    $table->integer('status');
	    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
