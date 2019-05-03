<?php
namespace App\Repositories\Interfaces\Product;

use App\Repositories\Interfaces\baseRepositoryInterface;

interface inventoryRepositoryInterface extends baseRepositoryInterface{


    public function createWithMultipleEquipments(Array $equipments,$supplierId);

    public function updateWithMultipleEquipments(Array $equipments,$supplierId);

    public function findEquipmentIdsBySupplier($supplierId);

    public function hasInventories($supplierId);
}