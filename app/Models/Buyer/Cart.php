<?php

namespace App\Models\Buyer;

use App\Models\Product\Equipment;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable=['user_id','details'];

    protected $casts=[
        'details'=>'json'
    ];


    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function items(){
    	return $this->belongsToMany(Equipment::class)
	                ->with('brand','categories')
		            ->active()
	                ->using(CartEquipment::class)->withPivot('to','from','qty','extras');
    }

    public function getEmptyAttribute(){
    	return $this->items->count()==0;
    }

    public function getDetailsAttribute(){
    	if(!$this->attributes['details']){
    		$this->attributes['details']='[]';
	    }
	    return json_decode($this->attributes['details'],true);
    }

    public function setSite($site_id){
    	$att=$this->details;
    	$att['site_id']=$site_id;
	    $this->attributes['details']=json_encode($att);
    }

	public function setSuppliers($suppliers){
		$att=$this->details;
		$att['suppliers']=$suppliers;
		$this->attributes['details']=json_encode($att);
	}


	public function setQuantitiesAndDates($details){
		$att=$this->details;
		$att['qtys']=$details;
		$this->attributes['details']=json_encode($att);
	}

	public function setStageAttribute($stage){
		$att=$this->details;
		$att['stage']=$stage;
		$this->attributes['details']=json_encode($att);
	}

	public function getStageAttribute(){
    	if(isset($this->details['stage'])){
    		return $this->details['stage'];
	    }
	    return '';
	}

	public function delete() {
    	$this->items()->withoutGlobalScope('active')->sync([]);
		return parent::delete(); // TODO: Change the autogenerated stub
	}

}
