<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lan_languages', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
	        $table->char('iso_code',5)->unique();
	        $table->char('status',50)->index();
	        $table->char('name',100);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lan_languages');
    }
}
