<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Equipment\CreateEquipmentRequest;
use App\Http\Requests\Admin\Equipment\UpdateEquipmentRequest;
use App\Repositories\Eloquent\Product\MaterialRepository;
use App\Repositories\Interfaces\Product\brandRepositoryInterface;
use App\Repositories\Interfaces\Product\categoryRepositoryInterface;
use App\Repositories\Interfaces\Product\equipmentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialsController extends Controller
{

    protected $brandRepo,$categoryRepo,$material_repository;
    public function __construct(MaterialRepository $material_repository,
                                brandRepositoryInterface $brandRepo,
                                categoryRepositoryInterface $categoryRepo)
    {
        $this->material_repository=$material_repository;
        $this->brandRepo = $brandRepo;
        $this->categoryRepo = $categoryRepo;
    }


    /**
     * Display the list of all equipments on the system.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $materials = $this->material_repository->with(['categories'])->findAll();
        return view('web.admin.materials.index',compact('materials'));
    }

    public function create(){
        $brands = $this->brandRepo->findAll();
        $categories = $this->categoryRepo->findAll();
        return view('web.admin.materials.create',compact('brands','categories'));
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

        $this->material_repository->create($data);

        return redirect()->route('admin.materials.index')->with('notifications',collect([
            [
                'text'=>__("The material was created successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }

    public function edit($id){
        $brands = $this->brandRepo->findAll();
        $categories = $this->categoryRepo->findAll();
        $material = $this->material_repository->with(['categories'])->findOneBy($id,'id');
        return view('web.admin.materials.edit',compact('brands','categories','material'));
    }

    public function update(UpdateEquipmentRequest $request,$id){


        $equipment = $this->material_repository->findOneBy($id,'id');

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

        $this->material_repository->updateBy($data,$id);

        return redirect()->back()->with('notifications',collect([
            [
                'text'=>__("The material was updated successfully"),
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

}
