<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sup_branches', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->char('name',100);
            $table->text('address');
            $table->double('lat');
            $table->double('lon');
            $table->text('details');
            $table->char('status',50);
            $table->unsignedInteger('supplier_id');
	        $table->unsignedInteger('country_id');
	        $table->unsignedInteger('state_id')->nullable();
	        $table->unsignedInteger('metro_id')->nullable();
	        $table->unsignedInteger('city_id')->nullable();

            $table->foreign('supplier_id')->references('id')->on('sup_suppliers');
	        $table->foreign('country_id')->references('id')->on('geo_countries');
	        $table->foreign('state_id')->references('id')->on('geo_states');
	        $table->foreign('metro_id')->references('id')->on('geo_metros');
	        $table->foreign('city_id')->references('id')->on('geo_cities');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sup_branches');
    }
}
