<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyOrderSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('order_suppliers', function (Blueprint $table) {
	    	$table->dropForeign('order_suppliers_order_id_foreign');
		    $table->dropForeign('order_suppliers_supplier_id_foreign');
		    $table->dropIndex('order_suppliers_order_id_supplier_id_unique');
		    $table->dropIndex('order_suppliers_supplier_id_foreign');
	    });

	    Schema::rename('order_suppliers','order_supplier');

	    Schema::table('order_supplier', function (Blueprint $table) {
		    $table->smallInteger('status')->default(1)->change();
		    $table->unique(['order_id','supplier_id']);
		    $table->foreign('order_id')->references('id')->on('orders');
		    $table->foreign('supplier_id')->references('id')->on('suppliers');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('order_supplier', function (Blueprint $table) {
		    $table->dropForeign('order_supplier_order_id_foreign');
		    $table->dropForeign('order_supplier_supplier_id_foreign');
		    $table->dropIndex('order_supplier_order_id_supplier_id_unique');
		    $table->dropIndex('order_supplier_supplier_id_foreign');
	    });

	    Schema::rename('order_supplier','order_suppliers');

	    Schema::table('order_suppliers', function (Blueprint $table) {
		    $table->smallInteger('status')->default(1)->change();
		    $table->unique(['order_id','supplier_id']);
		    $table->foreign('order_id')->references('id')->on('orders');
		    $table->foreign('supplier_id')->references('id')->on('suppliers');
	    });
    }
}
