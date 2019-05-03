<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Equipment\activeEquipment;
use App\Http\Resources\Product\EquipmentResource;
use App\Models\Product\Equipment;
use App\Repositories\Eloquent\Product\CategoryRepository;
use App\Repositories\Interfaces\Product\equipmentRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * Class EquipmentController
 * @package App\Http\Controllers\Api\Product
 *
 * @SWG\Definition(
 *      definition="equipment_response_object",
 *      required={"name","brand","image","model","description"},
 *      @SWG\Property(property="name",type="string",maxLength=50),
 *      @SWG\Property(property="brand",type="integer"),
 *      @SWG\Property(property="image",type="string",maxLength=100),
 *      @SWG\Property(property="model",type="string",maxLength=100),
 *      @SWG\Property(property="description",type="string",maxLength=5000),
 * )
 *
 */
class EquipmentController extends Controller
{

	protected $equipment_repository,$category_repository;
	public function __construct(equipmentRepositoryInterface $equipment_repository,CategoryRepository $category_repository) {
		$this->equipment_repository=$equipment_repository;
		$this->category_repository=$category_repository;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     *
     * @SWG\Get(
	 *      path="/api/equipments",
	 *      summary="List all the available equipment",
	 *      produces={"application/json"},
	 *     tags={"equipment"},
	 *      @SWG\Response(
	 *          response=200,
	 *          description="the list of the equipment",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/equipment_response_object"))
	 *      )
	 * )
     */
    public function index()
    {
	    $categories = $this->category_repository->generateCategoryOrderedList();
	    $categories_ids = collect($categories);
	    $categories_ids = $categories_ids->flatten(1);
	    $subcategories_id=$categories_ids->pluck('id');
	    $equipments = $this->equipment_repository->paginateByCategory($subcategories_id,9);
        return EquipmentResource::collection($equipments);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product\Equipment  $equipment
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *      path="/api/equipments/{equipment}",
     *      summary="the information of the specified equipment",
     *      produces={"application/json"},
     *     tags={"equipment"},
     *     @SWG\Parameter(
     *          in="path",
     *          name="equipment",
     *          description="id of the specified equipment",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="the specified equipment",
     *              @SWG\Property(property="data",type="object",ref="#/definitions/equipment_response_object")
     *      )
     * )
     *
     */
    public function show(activeEquipment $request, Equipment $equipment)
    {
	    return EquipmentResource::make($equipment);
    }

}
