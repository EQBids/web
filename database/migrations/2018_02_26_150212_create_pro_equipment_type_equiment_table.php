<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProEquipmentTypeEquimentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_equipment_type_equipment', function (Blueprint $table) {
	        $table->unsignedInteger('equipment_id');
	        $table->unsignedInteger('equipment_type_id');
	        $table->unique(['equipment_id','equipment_type_id'],'pro_equiment_type_equiment_uq');
	        $table->timestamps();
			$table->foreign('equipment_id','pro_eq_type_eq_pro_eq_f')->references('id')->on('pro_equipments');
	        $table->foreign('equipment_type_id','pro_eq_type_eq_p_eq_type_f')->references('id')->on('pro_equipment_types');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pro_equipment_type_equiment');
    }
}
