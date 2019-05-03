<?php

namespace App\Observers;


use App\Models\Product\Equipment;
use App\Repositories\Interfaces\Product\equipmentRepositoryInterface;

class EquipmentObserver
{

    public function deleting(Equipment $equipment){

        return true;
    }

}