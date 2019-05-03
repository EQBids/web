<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
	        $table->dropColumn('name');
	        $table->char('first_name',100);
	        $table->char('last_name',100);
	        $table->char('phone',50);
			$table->unsignedInteger('city_id')->nullable();
			$table->unsignedInteger('metro_id')->nullable();
			$table->unsignedInteger('state_id')->nullable();
			$table->unsignedInteger('country_id');
			$table->unsignedInteger('creator_user_id')->nullable();
	        $table->text('settings');

	        $table->foreign('city_id')->references('id')->on('geo_cities');
	        $table->foreign('metro_id')->references('id')->on('geo_metros');
	        $table->foreign('state_id')->references('id')->on('geo_states');
	        $table->foreign('country_id')->references('id')->on('geo_countries');
	        $table->foreign('creator_user_id')->references('id')->on('users');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
	        $table->string('name');
	        $table->dropForeign(['city_id']);
	        $table->dropForeign(['metro_id']);
	        $table->dropForeign(['state_id']);
	        $table->dropForeign(['country_id']);
	        $table->dropForeign(['creator_user_id']);
	        $table->dropColumn('first_name',100);
	        $table->dropColumn('last_name',100);
	        $table->dropColumn('phone',50);
	        $table->dropColumn('city_id');
	        $table->dropColumn('metro_id');
	        $table->dropColumn('state_id');
	        $table->dropColumn('country_id');
	        $table->dropColumn('creator_user_id');
	        $table->dropColumn('settings');


        });
    }
}
