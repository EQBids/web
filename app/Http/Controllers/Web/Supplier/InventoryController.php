<?php

namespace App\Http\Controllers\Web\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Inventory\InventoryRequest;
use App\Repositories\Interfaces\Product\categoryRepositoryInterface;
use App\Repositories\Interfaces\Product\equipmentRepositoryInterface;
use App\Repositories\Interfaces\Product\inventoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{

    protected $inventoryRepo;
    protected $equipmentRepo;
    protected $category_repository;
    public function __construct(inventoryRepositoryInterface $inventoryRepo,equipmentRepositoryInterface $equipRepo, categoryRepositoryInterface $category_repository)

    {
        $this->inventoryRepo = $inventoryRepo;
        $this->equipmentRepo = $equipRepo;
        $this->category_repository=$category_repository;
    }

    public function index(){

        $equipmentTypes = $this->category_repository->with('equipments')->findAll();
        $supplierId = Auth::user()->suppliers()->first()->id;

        $hasInventories = $this->inventoryRepo->hasInventories($supplierId);
        $equipmentIds = $this->inventoryRepo->findEquipmentIdsBySupplier($supplierId);

        return view('web.supplier.inventory.index',compact('equipmentTypes','hasInventories','equipmentIds'));
    }

    public function store(InventoryRequest $request){

        //TODO check if this is ok, or there will be added a user_id fk to the suppliers table.
        $supplierId = Auth::user()->suppliers()->first()->id;

        if(!$this->inventoryRepo->hasInventories($supplierId))
            $this->inventoryRepo->createWithMultipleEquipments($request->get('equipment'),$supplierId);
        else
            $this->inventoryRepo->updateWithMultipleEquipments($request->get('equipment'),$supplierId);

        return redirect()->route('supplier.inventory.index')->with('notifications',collect([
            [
                'text'=>__("The inventory was updated successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }

}
