<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_states', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
	        $table->char('iso_code',5)->unique();
	        $table->char('status',50)->index();
	        $table->char('name',100);
	        $table->unsignedInteger('country_id');

	        $table->foreign('country_id')->references('id')->on('geo_countries');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geo_states');
    }
}
