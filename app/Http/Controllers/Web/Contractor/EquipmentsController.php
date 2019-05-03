<?php

namespace App\Http\Controllers\Web\Contractor;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Product\categoryRepositoryInterface;
use App\Repositories\Interfaces\Product\equipmentRepositoryInterface;
use App\Repositories\Interfaces\settingsRepositoryInterface;
use function GuzzleHttp\Psr7\str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentsController extends Controller
{
    protected $equipment_repository,$category_repository,$settings_repositoy;
    public function __construct(equipmentRepositoryInterface $equipment,
	    categoryRepositoryInterface $category_repository,
		settingsRepositoryInterface $settings_repository
)
    {
        $this->equipment_repository = $equipment;
        $this->category_repository=$category_repository;
        $this->settings_repositoy=$settings_repository;
    }

    /**
     * Displays all the info about an equipment.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id,Request $request){


	    $equipment = $this->equipment_repository->with(['brand','categories'])->findOneBy($id,'id');
	    $categories = $this->category_repository->generateCategoryOrderedList();

	    if(!$equipment || !$equipment->is_active){
		    throw new ModelNotFoundException();
	    }

	    return view('web.contractor.equipment.show',compact('equipment','categories'));
    }

	/**
	 * display all the categories
	 * @return $this
	 */
    public function index(){
	    $currentOrder = Auth::user()->orderInProcess();
	    $categories = $this->category_repository->generateCategoryOrderedList();
	    $categories_ids = collect($categories);
	    $categories_ids = $categories_ids->flatten(1);
	    $subcategories_id=$categories_ids->pluck('id');
	    $equipments = $this->equipment_repository->paginateByCategory($subcategories_id,9);
	    return view('web.contractor.equipment.index')->with(compact('categories','equipments'));
    }

	/**
	 * display the content of the specified category
	 */
    public function category($slug){
    	$current_category =$this->category_repository->active()->findOneBySlug(strval($slug));
    	if(!$current_category || !$current_category->is_active){
    		throw new ModelNotFoundException();
	    }

	    $currentOrder = Auth::user()->orderInProcess();
    	$categories = $this->category_repository->generateCategoryOrderedList();

	    $categories_group = $categories[$current_category->parent_id];
    	$subcategories_id = $current_category->id;
    	foreach ($categories_group as $value){
    		if ($value['id']==$current_category->id){
    			$subcategories_id=$value['subcategories_ids'];
    			break;
		    }
	    }
		$equipments = $this->equipment_repository->paginateByCategory($subcategories_id,9);
	    return view('web.contractor.equipment.category.list')->with(compact('categories','current_category','equipments'));
    }


}
