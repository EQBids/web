<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToContractorUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contractor_user', function (Blueprint $table) {
	        $table->unsignedInteger('role_id')->default(0);
	        $table->tinyInteger('status')->default(1);
	        //$table->foreign('role_id')->references('id')->on('roles');
	        $table->unique(['contractor_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contractor_user', function (Blueprint $table) {
	        $table->dropColumn('role_id');
	        $table->dropColumn('status');
        });
    }
}
