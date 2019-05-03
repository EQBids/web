<?php

namespace App\Models\Supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierSetting extends Model
{
    use SoftDeletes;
    protected $table = 'supplier_setting';

    protected $fillable = [
        'supplier_id',
        'name',
        'value'
    ];

    public function getLabelAttribute(){
    	switch ($this->name){
    	    case 'distance': return 'Operation distance (KM)';
		    case 'insurance': return 'Insurance (%)';
		    default: return $this->name;
	    }

    }

}
