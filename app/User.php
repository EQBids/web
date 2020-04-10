<?php

namespace App;

use App\Models\Buyer\Cart;
use App\Models\Buyer\Contractor;
use App\Models\Buyer\Site;
use App\Models\Geo\City;
use App\Models\Geo\Country;
use App\Models\Geo\Metro;
use App\Models\Geo\State;
use App\Models\Message;
use App\Models\RoleUser;
use App\Models\Security\Pin;
use App\Models\Supplier\Bid;
use App\Models\Supplier\Supplier;
use App\Models\UserStatus;
use App\Scopes\User\activeOnlyScope;
use App\Scopes\User\nonDeletedScope;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use App\Models\Buyer\Order;


class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    const STATUS_PENDING=0;
	const STATUS_ACTIVE=1;
	const STATUS_INACTIVE=2;
	const STATUS_BANNED=3;
	const STATUS_ON_APPROVAL=4;
	const STATUS_BLOCKED=5;
	const STATUS_AWAY=6;
	const STATUS_REJECTED=7;




    protected static function boot() {
	    parent::boot();
	    static::addGlobalScope(new nonDeletedScope());
    }


	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'email', 'password','phone',
	    'city_id','metro_id','state_id','country_id','creator_user_id',
	    'status',
	    'settings',
    ];

    protected $appends=['full_normal_name'];

    protected $casts=[
    	'settings'=>'json'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','created_at','updated_at'
    ];

    public function rols(){
    	return $this->belongsToMany(\App\Models\Role::class,'role_user','user_id','role_id');
    }

	function hasRole($rol){
		if (is_string($rol)){
			foreach ($this->rols as $user_rol){
				if ($user_rol->name === $rol){
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * determines if the user belong to atleast one of the specified rols
	 *
	 * @param array $rols array of rols to witch user my belong
	 *
	 * @return bool return @true if user belongs to atleast one of the specified rols, @false otherwise.
	 *
	 * NOTE: performance is not an issue for less than 100 rols
	 */
	function hasAnyRol(array $rols){
		foreach ($this->rols as $user_rol){
			foreach ($rols as $target_rol) {
				if (is_string($target_rol) && trim($user_rol->name) == trim($target_rol) ) {
					return true;
				}
			}
		}
		return false;
	}


	public function pin(){
		return $this->hasOne(Pin::class);
	}

	public function bid(){
		return $this->hasMany(Bid::class);
	}

	public function orders(){
		return $this->hasMany(Order::class);
	}

	public function orderInProcess(){
		return $this->orders()->Inprocess()->first();
	}

	public function contractors(){
		return $this->belongsToMany(Contractor::class,'contractor_user');
	}

	public function ownedContractors(){
		return $this->hasMany(Contractor::class);
	}

	public function suppliers(){
		return $this->belongsToMany(Supplier::class)->withPivot(['status']);
	}

	public function city(){
		return $this->belongsTo(City::class);
	}

	public function metro(){
		return $this->belongsTo(Metro::class);
	}

	public function state(){
		return $this->belongsTo(State::class);
	}

	public function country(){
		return $this->belongsTo(Country::class);
	}

	public function creatorUser(){
		return $this->belongsTo(User::class);
	}

	public function users(){
		return $this->hasMany(User::class);
	}

	public function recursiveUsers(){

		return User::whereExists(function ($exists){
			$exists->select(DB::raw('id as sorted_id'))->from(DB::raw("(select * from users
         order by creator_user_id, id) users_sorted,
        (select @pv := ".$this->attributes['id'].") initialisation"))
		->whereRaw("find_in_set(creator_user_id, @pv)
					and length(@pv := concat(@pv, ',', id))")
		->havingRaw(DB::raw('sorted_id = users.id'));
		});
	}


	public function sendedMessages(){
		return $this->hasMany(Message::class,'sender_user_id');
	}

	public function rcptMessages(){
		return $this->hasMany(Message::class,'rcpt_user_id');
	}

	public function UserStatus(){
		return $this->hasOne(UserStatus::class,'user_id');
	}

	public function sites(){
		return $this->belongsToMany(Site::class)->withPivot('status','role_id');
	}

	public function ownedSites(){
		return $this->hasManyThrough(Site::class,Contractor::class,'user_id');
	}

	public function getFullNameAttribute(){
		return $this->first_name.' '.$this->last_name;
	}

	public function setPasswordAttribute($password){
		$this->attributes['password']=bcrypt($password);
	}

	public function setStatusAttribute($status){
		//prevents unsecure user reactivation
		if (isset($this->attributes['status'])
		    && (in_array($this->attributes['status'],[]))){
			return;
		}
		$this->attributes['status']=$status;
	}

	public function cart(){
		return $this->hasOne(Cart::class);
	}



	public function getContractorAttribute(){
		if($this->contractors->count()==0){
			$this->contractors()->attach($this->ownedContractors()->get(['id'])->pluck('id'));
		}
		return $this->contractors()->first();
	}

	public function isPending(){
		return $this->attributes['status']==User::STATUS_PENDING;
	}

	public function isActive(){
		return $this->attributes['status']==User::STATUS_ACTIVE;
	}

	public function isInactive(){
		return $this->attributes['status']==User::STATUS_INACTIVE;
	}

	public function isBanned(){
		return $this->attributes['status']==User::STATUS_BANNED;
	}

	public function isOnApproval(){
		return $this->attributes['status']==User::STATUS_ON_APPROVAL;
	}

	public function isBlocked(){
		return $this->attributes['status']==User::STATUS_BLOCKED;
	}

	public function isAway(){
		return $this->attributes['status']==User::STATUS_AWAY;
	}

	public function getIsContractorAttribute(){
		return $this->hasAnyRol(['contractor-superadmin','contractor-admin','contractor-manager','contractor-worker']);
	}

	public function getIsAdminAttribute(){
		return $this->hasAnyRol(['superadmin','admin','staff']);
	}

	public function getIsSupplierAttribute(){
		return $this->hasAnyRol(['supplier-superadmin','supplier-admin','supplier-manager','supplier-worker','supplier-salesperson']);
	}

	public function getSupplierAttribute(){
		if($this->suppliers->count()==0){
			return null;
		}
		return $this->suppliers()->first();
	}

	public function getStatusName(){
		switch ($this->status){
			case User::STATUS_PENDING: return 'Pending';
			case User::STATUS_ACTIVE: return 'Active';
			case User::STATUS_INACTIVE: return 'Inactive';
			case User::STATUS_BANNED: return 'Banned';
			case User::STATUS_ON_APPROVAL: return 'On approval';
			case User::STATUS_BLOCKED: return 'Blocked';
			case User::STATUS_AWAY: return 'Away';
			case User::STATUS_REJECTED: return 'Rejected';
		}
		return 'Unkown';
	}
}


