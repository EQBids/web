<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixingForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('orders',function (Blueprint $table){
			$table->dropForeign('buy_orders_site_id_foreign');
	        $table->dropForeign('buy_orders_equipment_id_foreign');
	        $table->dropForeign('buy_orders_contractor_id_foreign');
        });

        Schema::table('sites',function (Blueprint $table){
        	$table->dropForeign('buy_sites_city_id_foreign');
	        $table->dropForeign('buy_sites_contractor_id_foreign');
	        $table->dropForeign('buy_sites_metro_id_foreign');
        });

        Schema::table('cities',function (Blueprint $table){
        	$table->dropForeign('geo_cities_country_id_foreign');
	        $table->dropForeign('geo_cities_metro_id_foreign');
	        $table->dropForeign('geo_cities_state_id_foreign');
        });

        Schema::table('metros',function (Blueprint $table){
        	$table->dropForeign('geo_metros_country_id_foreign');
        	$table->dropForeign('geo_metros_state_id_foreign');
        });

        Schema::table('states',function (Blueprint $table){
        	$table->dropForeign('geo_states_country_id_foreign');
        });

        Schema::table('messages',function (Blueprint $table){
        	$table->dropForeign('not_messages_order_id_foreign');
	        $table->dropForeign('not_messages_rcpt_user_id_foreign');
	        $table->dropForeign('not_messages_related_message_id_foreign');
	        $table->dropForeign('not_messages_sender_user_id_foreign');
        });

        Schema::table('equipment_type_equipment',function (Blueprint $table){
			$table->dropForeign('pro_eq_type_eq_p_eq_type_f');
			$table->dropForeign('pro_eq_type_eq_pro_eq_f');
        });

        Schema::table('equipments',function (Blueprint $table){
			$table->dropForeign('pro_equipments_brand_id_foreign');
        });


        Schema::table('role_user',function (Blueprint $table){
        	$table->dropForeign('role_user_contractor_id_foreign');
	        $table->dropForeign('role_user_role_id_foreign');
	        $table->dropForeign('role_user_supplier_id_foreign');
	        $table->dropForeign('role_user_user_id_foreign');
        });

        Schema::table('bids',function (Blueprint $table){
        	$table->dropForeign('sup_bids_order_id_foreign');
	        $table->dropForeign('sup_bids_supplier_id_foreign');
	        $table->dropForeign('sup_bids_user_id_foreign');
        });

        Schema::table('branches',function (Blueprint $table){
			$table->dropForeign('sup_branches_city_id_foreign');
	        $table->dropForeign('sup_branches_country_id_foreign');
	        $table->dropForeign('sup_branches_metro_id_foreign');
	        $table->dropForeign('sup_branches_state_id_foreign');
	        $table->dropForeign('sup_branches_supplier_id_foreign');
        });

        Schema::table('inventories',function (Blueprint $table){
			$table->dropForeign('sup_inventories_equipment_id_foreign');
	        $table->dropForeign('sup_inventories_supplier_id_foreign');
        });

        // generates new fk
	    Schema::table('orders',function (Blueprint $table){
		    $table->foreign('site_id')->references('id')->on('sites');
		    $table->foreign('equipment_id')->references('id')->on('equipments');
		    $table->foreign('contractor_id')->references('id')->on('contractors');
	    });

	    Schema::table('sites',function (Blueprint $table){
		    $table->foreign('city_id')->references('id')->on('cities');
		    $table->foreign('contractor_id')->references('id')->on('contractors');
		    $table->foreign('metro_id')->references('id')->on('metros');
	    });

	    Schema::table('cities',function (Blueprint $table){
		    $table->foreign('country_id')->references('id')->on('countries');
		    $table->foreign('metro_id')->references('id')->on('metros');
		    $table->foreign('state_id')->references('id')->on('states');
	    });

	    Schema::table('metros',function (Blueprint $table){
		    $table->foreign('country_id')->references('id')->on('countries');
		    $table->foreign('state_id')->references('id')->on('states');
	    });

	    Schema::table('states',function (Blueprint $table){
		    $table->foreign('country_id')->references('id')->on('countries');
	    });

	    Schema::table('messages',function (Blueprint $table){
		    $table->foreign('order_id')->references('id')->on('orders');
		    $table->foreign('rcpt_user_id')->references('id')->on('users');
		    $table->foreign('related_message_id')->references('id')->on('messages');
		    $table->foreign('sender_user_id')->references('id')->on('users');
	    });

	    Schema::table('equipment_type_equipment',function (Blueprint $table){
		    $table->foreign('equipment_type_id','eq_type_eq_eq_type_f')->references('id')->on('equipment_types');
		    $table->foreign('equipment_id','eq_type_eq_eq_f')->references('id')->on('equipments');
	    });

	    Schema::table('equipments',function (Blueprint $table){
		    $table->foreign('brand_id')->references('id')->on('brands');
	    });

	    Schema::table('role_user',function (Blueprint $table){
		    $table->foreign('contractor_id')->references('id')->on('contractors');
		    $table->foreign('role_id')->references('id')->on('roles');
		    $table->foreign('supplier_id')->references('id')->on('suppliers');
		    $table->foreign('user_id')->references('id')->on('users');
	    });

	    Schema::table('bids',function (Blueprint $table){
		    $table->foreign('order_id')->references('id')->on('orders');
		    $table->foreign('supplier_id')->references('id')->on('suppliers');
		    $table->foreign('user_id')->references('id')->on('users');
	    });

	    Schema::table('branches',function (Blueprint $table){
		    $table->foreign('city_id')->references('id')->on('cities');
		    $table->foreign('country_id')->references('id')->on('countries');
		    $table->foreign('metro_id')->references('id')->on('metros');
		    $table->foreign('state_id')->references('id')->on('states');
		    $table->foreign('supplier_id')->references('id')->on('suppliers');
	    });

	    Schema::table('inventories',function (Blueprint $table){
		    $table->foreign('equipment_id')->references('id')->on('equipments');
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
	    Schema::table('orders',function (Blueprint $table){
		    $table->dropForeign(['site_id']);
		    $table->dropForeign(['equipment_id']);
		    $table->dropForeign(['contractor_id']);
	    });

	    Schema::table('sites',function (Blueprint $table){
		    $table->dropForeign(['city_id']);
		    $table->dropForeign(['contractor_id']);
		    $table->dropForeign(['metro_id']);
	    });

	    Schema::table('cities',function (Blueprint $table){
		    $table->dropForeign(['country_id']);
		    $table->dropForeign(['metro_id']);
		    $table->dropForeign(['state_id']);
	    });

	    Schema::table('metros',function (Blueprint $table){
		    $table->dropForeign(['country_id']);
		    $table->dropForeign(['state_id']);
	    });

	    Schema::table('states',function (Blueprint $table){
		    $table->dropForeign(['country_id']);
	    });

	    Schema::table('messages',function (Blueprint $table){
		    $table->dropForeign(['order_id']);
		    $table->dropForeign(['rcpt_user_id']);
		    $table->dropForeign(['related_message_id']);
		    $table->dropForeign(['sender_user_id']);
	    });

	    Schema::table('equipment_type_equipment',function (Blueprint $table){
		    $table->dropForeign('eq_type_eq_eq_type_f');
		    $table->dropForeign('eq_type_eq_eq_f');
	    });

	    Schema::table('equipments',function (Blueprint $table){
		    $table->dropForeign(['brand_id']);
	    });


	    Schema::table('role_user',function (Blueprint $table){
		    $table->dropForeign(['contractor_id']);
		    $table->dropForeign(['role_id']);
		    $table->dropForeign(['supplier_id']);
		    $table->dropForeign(['user_id']);
	    });

	    Schema::table('bids',function (Blueprint $table){
		    $table->dropForeign(['order_id']);
		    $table->dropForeign(['supplier_id']);
		    $table->dropForeign(['user_id']);
	    });

	    Schema::table('branches',function (Blueprint $table){
		    $table->dropForeign(['city_id']);
		    $table->dropForeign(['country_id']);
		    $table->dropForeign(['metro_id']);
		    $table->dropForeign(['state_id']);
		    $table->dropForeign(['supplier_id']);
	    });

	    Schema::table('inventories',function (Blueprint $table){
		    $table->dropForeign(['equipment_id']);
		    $table->dropForeign(['supplier_id']);
	    });

	    // generates new fk
	    Schema::table('orders',function (Blueprint $table){
		    $table->foreign('site_id','buy_orders_site_id_foreign')->references('id')->on('sites');
		    $table->foreign('equipment_id','buy_orders_equipment_id_foreign')->references('id')->on('equipments');
		    $table->foreign('contractor_id','buy_orders_contractor_id_foreign')->references('id')->on('contractors');
	    });

	    Schema::table('sites',function (Blueprint $table){
		    $table->foreign('city_id','buy_sites_city_id_foreign')->references('id')->on('cities');
		    $table->foreign('contractor_id','buy_sites_contractor_id_foreign')->references('id')->on('contractors');
		    $table->foreign('metro_id','buy_sites_metro_id_foreign')->references('id')->on('metros');
	    });

	    Schema::table('cities',function (Blueprint $table){
		    $table->foreign('country_id','geo_cities_country_id_foreign')->references('id')->on('countries');
		    $table->foreign('metro_id','geo_cities_metro_id_foreign')->references('id')->on('metros');
		    $table->foreign('state_id','geo_cities_state_id_foreign')->references('id')->on('states');
	    });

	    Schema::table('metros',function (Blueprint $table){
		    $table->foreign('country_id','geo_metros_country_id_foreign')->references('id')->on('countries');
		    $table->foreign('state_id','geo_metros_state_id_foreign')->references('id')->on('states');
	    });

	    Schema::table('states',function (Blueprint $table){
		    $table->foreign('country_id','geo_states_country_id_foreign')->references('id')->on('countries');
	    });

	    Schema::table('messages',function (Blueprint $table){
		    $table->foreign('order_id','not_messages_order_id_foreign')->references('id')->on('orders');
		    $table->foreign('rcpt_user_id','not_messages_rcpt_user_id_foreign')->references('id')->on('users');
		    $table->foreign('related_message_id','not_messages_related_message_id_foreign')->references('id')->on('messages');
		    $table->foreign('sender_user_id','not_messages_sender_user_id_foreign')->references('id')->on('users');
	    });

	    Schema::table('equipment_type_equipment',function (Blueprint $table){
		    $table->foreign('equipment_type_id','pro_eq_type_eq_p_eq_type_f')->references('id')->on('equipment_types');
		    $table->foreign('equipment_id','pro_eq_type_eq_pro_eq_f')->references('id')->on('equipments');
	    });

	    Schema::table('equipments',function (Blueprint $table){
		    $table->foreign('brand_id','pro_equipments_brand_id_foreign')->references('id')->on('brands');
	    });

	    Schema::table('role_user',function (Blueprint $table){
		    $table->foreign('contractor_id','rol_user_contractor_id_foreign')->references('id')->on('contractors');
		    $table->foreign('role_id','rol_user_role_id_foreign')->references('id')->on('roles');
		    $table->foreign('supplier_id','rol_user_supplier_id_foreign')->references('id')->on('suppliers');
		    $table->foreign('user_id','rol_user_user_id_foreign')->references('id')->on('users');
	    });

	    Schema::table('bids',function (Blueprint $table){
		    $table->foreign('order_id','sup_bids_order_id_foreign')->references('id')->on('orders');
		    $table->foreign('supplier_id','sup_bids_supplier_id_foreign')->references('id')->on('suppliers');
		    $table->foreign('user_id','sup_bids_user_id_foreign')->references('id')->on('users');
	    });

	    Schema::table('branches',function (Blueprint $table){
		    $table->foreign('city_id','sup_branches_city_id_foreign')->references('id')->on('cities');
		    $table->foreign('country_id','sup_branches_country_id_foreign')->references('id')->on('countries');
		    $table->foreign('metro_id','sup_branches_metro_id_foreign')->references('id')->on('metros');
		    $table->foreign('state_id','sup_branches_state_id_foreign')->references('id')->on('states');
		    $table->foreign('supplier_id','sup_branches_supplier_id_foreign')->references('id')->on('suppliers');
	    });

	    Schema::table('inventories',function (Blueprint $table){
		    $table->foreign('equipment_id','sup_inventories_equipment_id_foreign')->references('id')->on('equipments');
		    $table->foreign('supplier_id','sup_inventories_supplier_id_foreign')->references('id')->on('suppliers');
	    });
    }
}
