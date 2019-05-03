<?php

namespace App\Repositories\Eloquent\Supplier;


use App\Models\Supplier\Supplier;
use App\Models\Supplier\SupplierSetting;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Supplier\settingsRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class SettingsRepository extends BaseRepository implements settingsRepositoryInterface
{

	const setting_names=array(
		'insurance',
		'distance'
	);

    public function __construct(SupplierSetting $model)
    {
        parent::__construct($model);
    }

    function getValue( Supplier $supplier, $name, $default = null ) {
	    if(!$supplier){
	    	return null;
	    }

	    $this->query->where('name',$name);
	    $setting = $this->findOneBy($supplier->id,'supplier_id');
	    return $setting?$setting->value:$default;

    }

    function getAll( Supplier $supplier ) {
    	if(!$supplier){return null;}
	    $this->query->where('supplier_id',$supplier->id);
    	$settings = $this->findAllWhereIn(SettingsRepository::setting_names,'name');
    	$settings_map = $settings->pluck('name');
    	$missing_settings = collect(SettingsRepository::setting_names);
    	$missing_settings=$missing_settings->diff($settings_map);
    	foreach ($missing_settings as $name){
    		$settings->push($this->create([
    			'supplier_id'=>$supplier->id,
			    'name'=>$name,
			    'value'=>null,
		    ]));
	    }

	    return $settings;

    }

}