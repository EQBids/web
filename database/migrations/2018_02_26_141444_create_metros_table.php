<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_metros', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
	        $table->char('status',50)->index();
	        $table->char('name',100);
	        $table->double('lat');
	        $table->double('lon');

	        $table->unsignedInteger('state_id')->nullable();
	        $table->unsignedInteger('country_id');

	        $table->foreign('state_id')->references('id')->on('geo_states');
	        $table->foreign('country_id')->references('id')->on('geo_countries');
	        $table->unique(['name','state_id','country_id']);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geo_metros');
    }
}
