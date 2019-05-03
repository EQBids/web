<?php

namespace App\Repositories\Eloquent\Product;


use App\Models\Product\Equipment;
use App\Models\Product\EquipmentType;
use App\Models\Product\Material;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Product\equipmentRepositoryInterface;
use Faker\Provider\Base;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MaterialRepository extends BaseRepository
{

    public function __construct(Material $model)
    {
        parent::__construct($model);
    }

    public function create(array $data)
    {
        try{

        	DB::beginTransaction();

		        $data['details']['allow_attachments']=$data['allow_attachments'];
                $material = $this->model->create($data);
                $material->categories()->attach($data['category']);
            DB::commit();
        }catch (\Exception $e){
            DB::rollback();
            throw $e;
        }
    }

    public function updateBy(array $data, $value = null, $field = 'id')
    {
        try{


            DB::beginTransaction();
            $material = $this->findOneBy($value,'id');

//            If the user replaced the image of the equipment.
	        $oldDetails = $material->details;
			$data['details']=$oldDetails;
            if(isset($data['image_name'])){
            	if(isset($oldDetails['image']) && $oldDetails['image']) {
		            $oldImage = $oldDetails['image'];
	            }
                $data['details']['image'] = $data['image_name'];
            }

			if(isset($data['description'])){
            	$data['details']['description']=$data['description'];
			}
	        if(isset($data['excerpt'])){
		        $data['details']['excerpt']=$data['excerpt'];
	        }
	        $data['details']['allow_attachments']=$data['allow_attachments'];
			$material->update($data);

            $material->categories()->sync([$data['category']]);

            DB::commit();

            if(isset($oldImage)){

            	try{
                    Storage::disk('public')->delete(preg_replace('^\/?storage\/?^','',$oldImage));
                }catch(\ErrorException $exception){
            		//silently handled, could be permissions error
		            Log::error('Unable to delete file: '.$oldImage);
	            }
            }

        }catch (\Exception $e){
            DB::rollback();
            throw $e;
        }
    }

    public function delete($value = null, $field = 'id')
    {
        $equipment = $this->findOneBy($value);

        return $equipment->delete();
    }

    public function paginateByCategory($category,$perPage=10,$columns=['*']){
    	$this->query->whereExists(function ($query) use ($category){
		    $query->select(DB::raw(1))
		          ->from('category_equipment')
		          ->whereRaw('category_equipment.equipment_id = equipments.id');
		    if(is_integer($category) || $category==null){
			    $query->where('category_id',$category);
		    }else{
			    $query->whereIn('category_id',$category);
		    }

	    });
	    return $this->paginate($perPage,$columns);
    }

	public function paginateAvailableCategory($category,$perPage=10,$lat,$lon,$radius,$columns=['*'],$country_id=null){
		$this->query->withSupplierInRange($radius,$lat,$lon,$country_id)->whereExists(function ($query) use ($category){
			$query->select(DB::raw(1))
			      ->from('category_equipment')
			      ->whereRaw('category_equipment.equipment_id = equipments.id');
			if (is_array($category)){
				$query->whereIn('category_id',$category);
			}elseif(is_integer($category)){
				$query->where('category_id',$category);
			}

		});
		return $this->paginate($perPage,$columns);
	}

	public function findOneInRange($value,$field,$lat,$lon,$radius,$columns=['*'],$country_id=null){
		$this->query->withSupplierInRange($radius,$lat,$lon,$country_id)->whereExists(function ($query){
			$query->select(DB::raw(1))
			      ->from('category_equipment')
			      ->whereRaw('category_equipment.equipment_id = equipments.id');
		});
		return $this->findOneBy($value,$field,$columns);
	}

	function active() {
		$this->query->active();
		return $this;
	}

}