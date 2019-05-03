<?php

namespace App\Models\Security;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Hashing\BcryptHasher;
use phpseclib\Crypt\Hash;

class Pin extends Model
{

	protected $table='pin_user';
	protected $primaryKey='user_id';

	protected $hidden=['pin'];
	protected $fillable=['user_id','device_id','expires_at','pin'];

	protected $dates=[
		'expires_at'
	];

	public function setPinAttribute($val){
		$this->attributes['pin']=bcrypt($val);
	}

	public function generatePin($expTime,$device_id){
		$base=111111;
		$pin = random_int($base,$base*9);
		$this->setPinAttribute($pin);
		$this->attributes['device_id']=$device_id;
		$this->attributes['expires_at']=Carbon::now()->addSeconds($expTime);
		return $pin;
	}

	public function user(){
		return $this->belongsTo(User::class);
	}


	/**
	 * return true and invalidates the pin if $pin matches the encoded value.
	 * @param $pin
	 */
	public function checkAndInvalidate($pin){
		$response= password_verify(strval($pin),$this->attributes['pin']);
		if($response){
			$this->delete();
		}
		return $response;
	}


}
