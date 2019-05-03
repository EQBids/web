<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePinUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pin_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->unique();
            $table->string('pin');
            $table->timestamp('expires_at');
            $table->text('device_id',200);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->primary('user_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pin_user');
    }
}
