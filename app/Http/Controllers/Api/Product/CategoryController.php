<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Requests\Product\Category\activeCategory;
use App\Http\Resources\Product\CategoryResource;
use App\Http\Resources\Product\EquipmentResource;
use App\Models\Product\Category;
use App\Repositories\Interfaces\Product\categoryRepositoryInterface;
use App\Repositories\Interfaces\Product\equipmentRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class CategoryController
 * @package App\Http\Controllers\Api\Product
 *
 * @SWG\Definition(
 *      definition="category_object",
 *      required={"name","brand","image","model","description"},
 *      @SWG\Property(property="id",type="integer"),
 *      @SWG\Property(property="name",type="string",maxLength=50),
 *      @SWG\Property(property="image",type="string",maxLength=50),
 *      @SWG\Property(property="description",type="string",maxLength=500),
 *      @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/category_object"))
 * )
 */
class CategoryController extends Controller
{
	protected $category_repository,$equipment_repository;

	public function __construct(categoryRepositoryInterface $category_repository,equipmentRepositoryInterface $equipment_repository) {
		$this->category_repository=$category_repository;
		$this->equipment_repository=$equipment_repository;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
	 *
	 * @SWG\Get(
	 *      path="/api/categories",
	 *      summary="List the main (those without parents) equipment categories",
	 *      produces={"application/json"},
	 *     tags={"equipment"},
	 *      @SWG\Response(
	 *          response=200,
	 *          description="the list of main categories",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/category_object"))
	 *      )
	 * )
	 *
     */
    public function index()
    {
	    $categories = $this->category_repository->active()->findAllBy(null,'parent_id',['*'],['childs']);
	    return response()->json(['data'=>CategoryResource::collection($categories)]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *      path="/api/categories/{category}",
     *      summary="display the content of the specified category",
     *      produces={"application/json"},
     *     tags={"equipment"},
     *      @SWG\Parameter(
     *          in="path",
     *          name="category",
     *          description="id of the specified category",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="the information of the specified category and subcategories",
     *          @SWG\Property(property="data",type="object",ref="#/definitions/equipment_response_object")
     *      )
     * )
     */
    public function show(activeCategory $request, Category $category)
    {
    	$category->load('childs');
        return CategoryResource::make($category);
    }

	/**
	 * @param Category $category
	 *
	 * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 *
	 * @SWG\Get(
	 *      path="/api/categories/{category}/items",
	 *      summary="List the equipment that belong to this category or subcategories",
	 *      produces={"application/json"},
	 *     tags={"equipment"},
	 *      @SWG\Parameter(
	 *          in="path",
	 *          name="category",
	 *          description="id of the specified category",
	 *          required=true,
	 *          type="integer",
	 *     @SWG\Schema(type="integer")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="the list of equipment that belongs to this category and subcatories",
	 *          @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/equipment_response_object"))
	 *      )
	 * )
	 */
    public function items(activeCategory $request, Category $category){

	    $categories = $this->category_repository->generateCategoryOrderedList();

	    $categories_group = $categories[$category->parent_id];
	    $subcategories_id = $category->id;
	    foreach ($categories_group as $value){
		    if ($value['id']==$category->id){
			    $subcategories_id=$value['subcategories_ids'];
			    break;
		    }
	    }


	    $equipments = $this->equipment_repository->paginateByCategory($subcategories_id,9);

	    return EquipmentResource::collection(
		    	        $equipments
			    );
    }


}
