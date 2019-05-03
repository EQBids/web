<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Equipment\CreateEquipmentRequest;
use App\Http\Requests\Admin\Equipment\UpdateEquipmentRequest;
use App\Repositories\Interfaces\Product\brandRepositoryInterface;
use App\Repositories\Interfaces\Product\categoryRepositoryInterface;
use App\Repositories\Interfaces\Product\equipmentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipmentsController extends Controller
{

    protected $equipmentRepo,$brandRepo,$categoryRepo;
    public function __construct(equipmentRepositoryInterface $equipmentRepo,
                                brandRepositoryInterface $brandRepo,
                                categoryRepositoryInterface $categoryRepo)
    {
        $this->equipmentRepo = $equipmentRepo;
        $this->brandRepo = $brandRepo;
        $this->categoryRepo = $categoryRepo;
    }


    /**
     * Display the list of all equipments on the system.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $equipments = $this->equipmentRepo->with(['categories'])->findAll();
        return view('web.admin.equipments.index',compact('equipments'));
    }

    public function create(){
        $brands = $this->brandRepo->findAll();
        $categories = $this->categoryRepo->findAll();
        return view('web.admin.equipments.create',compact('brands','categories'));
    }

    public function store(CreateEquipmentRequest $request){


    	$data = [
            'name'              =>      $request->get('name'),
            'category'          =>      $request->get('category'),
            'brand_id'          =>      $request->get('brand'),
            'status'            =>      $request->get('status'),
            'email_cost_code'   =>  $request->get('email_cost_code'),
            'bid_cost_code'     =>  $request->get('bid_cost_code'),
            'allow_attachments' =>      $request->get('allow_attachments'),
        ];

        $image = $request->file('image');
        $imageName = $image->hashName();

        $imageName=$request->file('image')->store('equipment/images','public');

        $data['details'] = [
        	'image'=>Storage::url($imageName),
	        'description'=>htmlentities(clean($request->get('description'))),
	        'excerpt'=>htmlentities(clean($request->get('excerpt'))),
        ];

        $this->equipmentRepo->create($data);

        return redirect()->route('admin.equipment.index')->with('notifications',collect([
            [
                'text'=>__("The equipment was created successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }

    public function edit($id){
        $brands = $this->brandRepo->findAll();
        $categories = $this->categoryRepo->findAll();
        $equipment = $this->equipmentRepo->with(['categories'])->findOneBy($id,'id');
        return view('web.admin.equipments.edit',compact('brands','categories','equipment'));
    }

    public function update(UpdateEquipmentRequest $request,$id){

		$equipment = $this->equipmentRepo->findOneBy($id,'id');
	    $data = [
            'name'              =>      $request->get('name'),
            'category'          =>      $request->get('category'),
            'brand_id'          =>      $request->get('brand'),
            'email_cost_code'   =>      $request->get('email_cost_code'),
            'bid_cost_code'     =>      $request->get('bid_cost_code'),
            'description'       =>      htmlentities(clean($request->get('description'))),
            'status'            =>      $request->get('status'),
	        'allow_attachments' =>      $request->get('allow_attachments'),
	        'excerpt'           =>      htmlentities(clean($request->get('excerpt'))),
        ];

        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = $image->hashName();

	        $imageName=$request->file('image')->store('equipment/images','public');
            $data['image_name'] = Storage::url($imageName);
        }

        $this->equipmentRepo->updateBy($data,$id);

        return redirect()->back()->with('notifications',collect([
            [
                'text'=>__("The equipment was updated successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));

    }

    public function delete($id){

        $equipment = $this->equipmentRepo->findOneBy($id,'id');

        return view('web.admin.equipments.delete',compact('equipment'));
    }

    public function destroy($id){

        $this->equipmentRepo->delete($id);

        return redirect()->route('admin.equipment.index')->with('notifications',collect([
            [
                'text'=>__("The equipment was deleted successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }

	public function equipment_listing_show($id,Request $request){

		$equipment = $this->equipmentRepo->with(['brand','categories'])->findOneBy($id,'id');

		if(!$equipment){
			throw new ModelNotFoundException();
		}

		return view('web.admin.equipments.show',compact('equipment'));
	}

	/**
	 * display all the categories
	 * @return $this
	 */
	public function equipment_listing_index(){
		$categories = $this->categoryRepo->generateCategoryOrderedList();
		$categories_ids = collect($categories);
		$categories_ids = $categories_ids->flatten(1);
		$subcategories_id=$categories_ids->pluck('id');
		$equipments = $this->equipmentRepo->paginateByCategory($subcategories_id,9);
		return view('web.admin.equipments.listing_index')->with(compact('categories','equipments'));
	}

	/**
	 * display the content of the specified category
	 */
	public function equipment_listing_category($slug){
		$current_category =$this->categoryRepo->active()->findOneBySlug(strval($slug));
		if(!$current_category || !$current_category->is_active){
			throw new ModelNotFoundException();
		}

		$categories = $this->categoryRepo->generateCategoryOrderedList();

		$categories_group = $categories[$current_category->parent_id];
		$subcategories_id = $current_category->id;
		foreach ($categories_group as $value){
			if ($value['id']==$current_category->id){
				$subcategories_id=$value['subcategories_ids'];
				break;
			}
		}
		$equipments = $this->equipmentRepo->paginateByCategory($subcategories_id,9);
		return view('web.admin.equipments.category.list')->with(compact('categories','current_category','equipments'));
	}
}
