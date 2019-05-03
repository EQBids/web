<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
	        $table->increments('id');
	        $table->timestamps();
	        $table->char('name',100);
	        $table->unsignedInteger('brand_id')->nullable();
	        $table->text('details');
			$table->unsignedInteger('status');
			$table->softDeletes();
	        $table->tinyInteger('email_cost_code')->default(1);
	        $table->tinyInteger('bid_cost_code')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
