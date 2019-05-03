<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingFielsToSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers',function (Blueprint $table){
        	$table->text('address')->nullable();
	        $table->double('lat');
        	$table->double('lon');


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
		    $table->dropColumn('address','lat','lon');
		});
    }
}
