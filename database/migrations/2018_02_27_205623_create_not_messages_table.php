<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('not_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->char('subject');
            $table->text('details');
            $table->unsignedInteger('sender_user_id');
            $table->unsignedInteger('rcpt_user_id');
            $table->unsignedInteger('related_message_id')->nullable();
            $table->unsignedInteger('order_id')->nullable();

            $table->foreign('sender_user_id')->references('id')->on('users');
	        $table->foreign('rcpt_user_id')->references('id')->on('users');
	        $table->foreign('related_message_id')->references('id')->on('not_messages');
	        $table->foreign('order_id')->references('id')->on('buy_orders');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('not_messages');
    }
}
