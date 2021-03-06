<?php

namespace App\Repositories\Eloquent\Product;


use App\Models\Supplier\Inventory;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Product\inventoryRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryRepository extends BaseRepository implements inventoryRepositoryInterface
{

    public function __construct(Inventory $model)
    {
        parent::__construct($model);
    }

    public function createWithMultipleEquipments(Array $equipments,$supplierId)
    {

        try{
            DB::beginTransaction();

            for($i = 0 ; $i < count($equipments) ; $i++){
                $this->create([
                    'supplier_id'       =>      $supplierId,
                    'equipment_id'      =>      $equipments[$i],
                    'details'           =>      array(),
                    'status'            =>      1
                ]);
            }

            DB::commit();
        }catch(\Exception $e){
            throw $e;
            DB::rollback();
        }

    }

    public function findAllBy( $value = null, $field = null, array $columns = [ '*' ], array $with=[] ) {
		if ($field==null){
			return $this->findAll();
		}
		$res= $this->query->where($field,$value)->whereRaw('deleted_at is null')->with($with)->get($columns);
		$this->resetScope();
		return $res;
	}

    public function findEquipmentIdsBySupplier($supplierId)
    {
        return $this->findAllBy($supplierId,'supplier_id')->pluck('equipment_id')->toArray();
    }

    public function hasInventories($supplierId)
    {
        return $this->findAllBy($supplierId,'supplier_id') ? true : false;
    }

    public function updateWithMultipleEquipments(Array $equipments, $supplierId)
    {
        try{
            DB::beginTransaction();
            
            $equipmentsAlreadyOnInventory = $this->findEquipmentIdsBySupplier($supplierId);
            
            for($i = 0 ; $i < count($equipments) ; $i++){
            
                //If the equipment was already on the inventory
                if(in_array($equipments[$i] , $equipmentsAlreadyOnInventory)){

                    $inventory = $this->model->whereRaw('deleted_at is null')->where('supplier_id',$supplierId)->where('equipment_id',$equipments[$i])->first();

                    if($inventory->status == 2){
                        $inventory->status = 1;
                    }
                    elseif ($inventory->status == 1){
                        $inventory->status = 2;
                    }
                    $inventory->save();
                }
                else{

                    $this->create([
                        'supplier_id'       =>      $supplierId,
                        'equipment_id'      =>      $equipments[$i],
                        'details'           =>      array(),
                        'status'            =>      1
                    ]);
                }
            }
            
            for($i = 0 ; $i < count($equipmentsAlreadyOnInventory) ; $i++){
                if(count($equipments) ==0 || !in_array($equipmentsAlreadyOnInventory[$i] , $equipments)){
                    $inventory = $this->model->whereRaw('deleted_at is null')->where('supplier_id',$supplierId)->where('equipment_id',$equipmentsAlreadyOnInventory[$i])->first();
                    echo $equipmentsAlreadyOnInventory[$i];
                    $inventory->deleted_at = date("Y-m-d");
                    $inventory->save();
                }
            }
            DB::commit();
           
        }catch(\Exception $e){
            throw $e;
            DB::rollback();
        }
    }
}