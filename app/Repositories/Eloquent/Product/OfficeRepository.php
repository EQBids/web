<?php

namespace App\Repositories\Eloquent\Product;


use App\Models\Supplier\Supplier;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Product\officeRepositoryInterface;
use App\Repositories\Interfaces\usersRepositoryInterface;
use App\User;
use function foo\func;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OfficeRepository extends BaseRepository implements officeRepositoryInterface
{

    protected $userRepo;
    public function __construct(Supplier $model,usersRepositoryInterface $userRepo)
    {
        parent::__construct($model);
        $this->userRepo = $userRepo;
    }


    public function createAndAddUser(array $data, $userId)
    {
        try{
            DB::beginTransaction();

            $office = parent::create($data);

            $user = $this->userRepo->with('suppliers')->findOneBy($userId);

            $user->suppliers()->attach($office->id);
            DB::commit();
        }catch (\Exception $e){

            DB::rollback();
            throw $e;
        }

    }

    public function offices( User $user ) {
	    $this->query->whereExists(function($exists) use ($user){
	    	$exists->select(DB::raw(1))->from('supplier_user')
			    ->whereRaw('supplier_user.supplier_id = suppliers.id && supplier_user.user_id = ?',[$user->id]);
	    });
	    return $this->findAll();
    }
}