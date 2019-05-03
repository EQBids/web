<?php

namespace App\Repositories\Eloquent;


use App\Models\System\Setting;
use App\Repositories\Interfaces\settingsRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class SettingsRepository extends BaseRepository implements settingsRepositoryInterface
{

    public function __construct(Setting $model)
    {
        parent::__construct($model);
    }

    public function getValue( $key, $default=null ) {
		$key = explode('.',$key);
		$item = $this->findOneBy($key[0],'name');
		if(!$item){
			return $default;
		}
		$value = $item->value;
		if(!is_array($value)){
			return $item->value;
		}
		array_shift($key);
		foreach ($key as $k){
			if (!isset($value[$k])){
				return $default;
			}
			$value=$value[$k];
		}
		return $value;
    }
}