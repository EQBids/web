<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('buy_contractors','contractors');
	    Schema::rename('buy_orders','orders');
	    Schema::rename('buy_sites','sites');
	    Schema::rename('geo_cities','cities');
	    Schema::rename('geo_countries','countries');
	    Schema::rename('geo_metros','metros');
	    Schema::rename('geo_states','states');
	    Schema::rename('lan_languages','languages');
	    Schema::rename('not_messages','messages');
	    Schema::rename('pro_brands','brands');
	    Schema::rename('pro_equipments','equipments');
	    Schema::rename('pro_equipment_type_equipment','equipment_type_equipment');
	    Schema::rename('pro_equipment_types','equipment_types');
	    Schema::rename('sec_banned','banned');
	    Schema::rename('sec_failed_logins','failed_logins');
	    Schema::rename('sec_user_status','user_status');
	    Schema::rename('sup_bids','bids');
	    Schema::rename('sup_branches','branches');
	    Schema::rename('sup_inventories','inventories');
	    Schema::rename('sup_suppliers','suppliers');
	    Schema::rename('sys_settings','settings');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::rename('contractors','buy_contractors');
	    Schema::rename('orders','buy_orders');
	    Schema::rename('sites','buy_sites');
	    Schema::rename('cities','geo_cities');
	    Schema::rename('countries','geo_countries');
	    Schema::rename('metros','geo_metros');
	    Schema::rename('states','geo_states');
	    Schema::rename('languages','lan_languages');
	    Schema::rename('messages','not_messages');
	    Schema::rename('brands','pro_brands');
	    Schema::rename('equipments','pro_equipments');
	    Schema::rename('equipment_type_equipment','pro_equipment_type_equipment');
	    Schema::rename('equipment_types', 'pro_equipment_types');
	    Schema::rename('banned', 'sec_banned');
	    Schema::rename('failed_logins','sec_failed_logins');
	    Schema::rename('user_status','sec_user_status');
	    Schema::rename('bids','sup_bids');
	    Schema::rename('branches','sup_branches');
	    Schema::rename('inventories', 'sup_inventories');
	    Schema::rename('suppliers','sup_suppliers');
	    Schema::rename('settings','sys_settings');
    }
}
