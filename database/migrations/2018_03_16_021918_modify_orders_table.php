<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders',function(Blueprint $table){

        	$table->dropForeign(['equipment_id']);
	        $table->dropForeign(['contractor_id']);

			$table->dropColumn('sku','qty','max_amount','details',
				'equipment_id','contractor_id');

        	$table->tinyInteger('status')->default(0);
			$table->unsignedInteger('billing_id');
			$table->dateTime('return_date');
			$table->text('notes');
			$table->text('extra_fields');

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
	    Schema::table('orders',function(Blueprint $table){

	    	$table->dropForeign(['billing_id']);

		    $table->char('sku',20)->index();
		    $table->integer('qty');
		    $table->integer('max_amount');
		    $table->text('details');
		    $table->unsignedInteger('equipment_id');
		    $table->unsignedInteger('contractor_id');

		    $table->foreign('equipment_id')->references('id')->on('equipments');
		    $table->foreign('contractor_id')->references('id')->on('contractors');


		    $table->dropColumn('status','billing_id','return_date','notes','extra_fields');

	    });
    }
}
