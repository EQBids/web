<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyContractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_contractors', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->char('company_name',150)->index();
            $table->text('address',512);
            $table->double('lat');
            $table->double('lon');
            $table->text('details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buy_contractors');
    }
}
