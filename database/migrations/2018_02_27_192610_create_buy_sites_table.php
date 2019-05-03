<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuySitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_sites', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->char('nickname',100);
            $table->char('name',100);
            $table->double('lat');
            $table->double('lon');
            $table->text('address',510);
            $table->text('details');
            $table->unsignedInteger('contractor_id');
            $table->unsignedInteger('metro_id')->nullable();
            $table->unsignedInteger('city_id')->nullable();

            $table->foreign('contractor_id')->references('id')->on('buy_contractors');
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
        Schema::dropIfExists('buy_sites');
    }
}
