<?php

namespace App\Models;

use App\Models\Buyer\Order;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable=['subject','details','sender_user_id','rcpt_user_id','related_message_id','order_id'];
	protected $casts=[
		'details'=>'json'
	];

	public function senderUser(){
		return $this->belongsTo(User::class);
	}

	public function rcptUser(){
		return $this->belongsTo(User::class);
	}

	public function parentMessage(){
		return $this->belongsTo(Message::class,'related_message_id');
	}

	public function order(){
		return $this->belongsTo(Order::class);
	}

	public function childMessages(){
		return $this->hasMany(Message::class,'related_message_id');
	}

}
