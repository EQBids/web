<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\CreateCategoryRequest;
use App\Models\Product\Category;
use App\Repositories\Interfaces\Product\categoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{

    protected $categoriesRepo;
    public function __construct(categoryRepositoryInterface $categoryRepo)
    {
        $this->categoriesRepo = $categoryRepo;
    }

    public function index(){
        $categories = $this->categoriesRepo->findAll();
        return view('web.admin.categories.index',compact('categories'));
    }


    public function create(Request $request){
	    $categories = $this->categoriesRepo->with('childs')->findAllBy(null,'parent_id');
	    if($request->old('parent')){
		    $category_id = $request->old('parent');

	    }else{
	    	$category_id=null;
	    }

        return view('web.admin.categories.new',compact('categories','category_id'));
    }

    public function store(CreateCategoryRequest $request){


        $data = [
            'parent_id'     =>  $request->get('parent'),
            'status'        =>  $request->get('status'),
            'details'       =>  array(),
            'name'          =>  $request->get('name'),

        ];

	    if($request->hasFile('image')){
		    $image = $request->file('image');

		    $imageName = $request->file('image')->store('categories/images','public');
		    $data['details']['image'] = Storage::url($imageName);
	    }
        $this->categoriesRepo->create($data);

        return redirect()->route('admin.categories.index')->with('notifications',collect([
            [
                'text'=>__("The category was created successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));

    }

    public function edit($id){

	    $categories = $this->categoriesRepo->with('childs')->findAllBy(null,'parent_id');
        $category = $this->categoriesRepo->findOneBy($id,'id');

        return view('web.admin.categories.edit',compact('category','categories'));
    }

    public function update(CreateCategoryRequest $request,$id){

        $data = [
            'parent_id'         =>      $request->get('parent'),
            'name'              =>      $request->get('name'),
            'status'            =>      $request->get('status'),
        ];

        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = $image->hashName();
            $destinationPath = public_path('storage/categories/images');
            $imageName = $request->file('image')->store('categories/images','public');
            $image->move($destinationPath, $imageName);
            $data['details']['image'] = Storage::url($imageName);
        }
        
        $this->categoriesRepo->updateBy($data,$id,'id');

        return redirect()->route('admin.categories.index')->with('notifications',collect([
            [
                'text'=>__("The category was updated successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }

    public function delete($id){

        if($this->categoriesRepo->hasEquipmentOrCategories($id)){
            return redirect()->back()->with('message',__("The category can not be deleted."));
        }

        $this->categoriesRepo->delete($id,'id');

        return redirect()->route('admin.categories.index')->with('notifications',collect([
            [
                'text'=>__("The category was deleted successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));

    }
}
