<?php

namespace App\Repositories\Eloquent;


use function App\Http\buildAddress;
use App\Mail\Auth\Admin\NewUserEmail;
use App\Mail\Auth\Applicants\approved;
use App\Mail\Auth\Applicants\rejected;
use App\Mail\Signup\WelcomeMessage;
use App\Mail\User\emailChangeMessage;
use App\Models\Buyer\Contractor;
use App\Models\Supplier\Supplier;
use App\Models\UserStatus;
use App\Repositories\Interfaces\geocodingRepositoryInterface;
use App\Repositories\Interfaces\usersRepositoryInterface;
use App\Scopes\User\nonDeletedScope;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UsersRepository extends BaseRepository implements usersRepositoryInterface
{

    protected $contractorModel,$supplierModel,$userStatusModel,$role_repository;
    protected $geoRepo;
    public function __construct(User $model,Contractor $contractor, Supplier $supplier,UserStatus $userStatus,
                            geocodingRepositoryInterface $geoRepo)
    {
        parent::__construct($model);
		$this->contractorModel = $contractor;
        $this->supplierModel = $supplier;
        $this->userStatusModel = $userStatus;
        $this->geoRepo = $geoRepo;
        $this->role_repository=app('App\Repositories\Eloquent\roleRepository');
	}
	
	public function findAll (array $columns = [ '*' ] ) {
		return $this->model
		->get();
	}

    public function emailExists($email)
    {
        return $this->model->where('email',$email)->first() ? true : false;
    }

    public function create( array $data ) {
    	if (!isset($data['password'])){
    		$data['password']=str_random(5);
	    }

	    if(isset($data['status'])){
		    if($data['status']<User::STATUS_PENDING || $data['status']>User::STATUS_AWAY){
			    $data['status']=0; //is an invalid status
		    }
	    }else {
		    $data['status'] = 4;
	    }

	    if(!isset($data['creator_user_id']) && Auth::check()){
		    $data['creator_user_id']=Auth::user()->id;
	    }
	    return parent::create( $data );
    }

    public function createWithRole($data, $role)
    {

        try{
            DB::beginTransaction();
			if(isset($data['status'])){
				if($data['status']<User::STATUS_PENDING || $data['status']>User::STATUS_AWAY){
					$data['status']=0; //is an invalid status
				}
			}else {
				$data['status'] = 4;
			}

            $user = $this->create($data);
            $user->rols()->attach($role->id);

            $user->UserStatus()->create(['status'=>$data['status'],'device_identifier'=>'web']);

            if(isset($data['site_id'])){
            	$user->sites()->attach($data['site_id'],['role_id'=>$role->id]);
            }

	        if(isset($data['contractor_id'])){
		        $user->contractors()->sync($data['contractor_id'],['role_id'=>$role->id]);
	        }elseif(isset($data['supplier_id'])){
		        $user->suppliers()->sync($data['supplier_id'],['role_id'=>$role->id]);
	        }

            if($role->name == 'contractor-superadmin' && isset($data['contractor'])){
                $this->createAndAddContractor($user,$data['contractor']);
            }
            elseif ($role->name == 'supplier-superadmin'){

                $this->createAndAddSupplier($user,$data['supplier']);
            }
            DB::commit();
	        //Sending the welcome message to the user just registered.
	        if(!$user->is_admin) {
		        Mail::to( $user->email )->send( new WelcomeMessage( $user ) );
	        }else{
		        Mail::to( $user->email )->send( new NewUserEmail( $user ) );
	        }

	        return $user;
        }catch (\Exception $e){
            DB::rollback();
            throw $e;
        }

    }


    private function createAndAddContractor(User $user, $data)
    {
        $data['user_id'] = $user->id;


        $contractor = $this->contractorModel->create($data);
        $user->contractors()->sync([$contractor->id]);
    }

    private function createAndAddSupplier($user,$data){

        $supplier = $this->supplierModel->create($data);
        $user->suppliers()->attach($supplier->id);
    }

    /**
     * Changes the status of an user (in its corresponding table).
     * @param $user_id
     * @param $status
     * @return mixed
     */
    public function changeStatus($user_id, $status)
    {

        $user = $this->findOneBy($user_id);

        $user->status = $status;

        return $user->save();
    }


	public function updateWithRole($id,$data, $role)
	{
		$email_change=null;
		try{
			DB::beginTransaction();

			$user = $user=$this->findOneBy($id);
			if (isset($data['email']) && $data['email']!=$user->email){
		
				$email_change=$data['email'];
			}
			$user->status = $data['status'];
			$user->fill($data);
			$user->save();
			if($role){
				$user->rols()->sync([$role->id]);
			}

			if(isset($data['site_id'])){
		
				$user->sites()->sync([$data['site_id']=>['role_id'=>$role->id]]);
			}

			if(isset($data['contractor_id'])){
			
				$user->contractors()->sync([$data['contractor_id']=>['role_id'=>$role->id]]);
			}elseif(isset($data['supplier_id'])){
		
				$user->suppliers()->sync($data['supplier_id'],['role_id'=>$role->id]);
			}
			
			if(preg_match('/contractor-.*/',$role->name)) {
		
				if ($user->contractors->count() == 0 ) {
					if ($role->name=='contractor-superadmin'){
						if ($user->ownedContractors()->count()==0){
							if ( isset( $data['contractor'])){
								$this->createAndAddContractor( $user, $data['contractor'] );
							}
						}else{
							$ownedContractors = $user->ownedContractors->map(function ($item){
								return $item->id;
							});
							$user->contractors()->sync($ownedContractors);
						}
					}
				}
			}
			elseif (preg_match('/supplier-.*/',$role->name)){
				$this->createAndAddSupplier($user,$data);
			}

			if(Auth::user()->is_admin && isset($data['company_name']) && $user->contractor){
				$contractor=$user->contractor;
				$contractor->company_name=$data['company_name'];
				$contractor->save();
			}

			if(Auth::user()->is_admin && isset($data['address']) && $user->contractor){
				$contractor=$user->contractor;
				$contractor->address=$data['address'];
				
				$contractor->save();
			}
			if(Auth::user()->is_admin  && $user->suppliers->count() > 0 ){
	
				$supplier = $user->suppliers[0];
				$supplier->address = $data['address'];
				$supplier->name = $data['company_name'];
				$supplier->save();
			}
			DB::commit();
			if ($email_change){
				Mail::to($email_change)->send(new emailChangeMessage($user));
			}

			return $user;
		}catch (\Exception $e){
			DB::rollback();
			throw $e;
		}

	}

	function delete( $value = null, $field = 'id' ) {
	    $user=$this->findOneBy($value,$field);
	    $user->status=2;
	    $user->save();
	}

    /**
     * Finds all the users that have status = 0
     * @return mixed
     */
    public function findApplicants()
    {
        return $this->model->withoutGlobalScope(nonDeletedScope::class)->whereIn('status',[User::STATUS_ON_APPROVAL,User::STATUS_REJECTED])->orderBy('created_at','desc')->get();
    }

    public function findApplicantBy($value, $field = 'id')
    {
        return $this->model->withoutGlobalScope(nonDeletedScope::class)->whereIn('status',[User::STATUS_ON_APPROVAL,User::STATUS_REJECTED])->where($field,$value)->first();
    }

    public function acceptApplicant($data, $id)
    {
        try{
            DB::beginTransaction();

            $user = $this->updateBy($data,$id);
            $country = $user->country;
            $state = $user->state;
            $city = $user->city;

            $fullAddress = buildAddress($data['settings']['address'],$country->name,$state->name,$city->name);
            $coordinates = $this->geoRepo->getAddressCoordinates($fullAddress,$city);

            $role = $user->rols()->first();
            if(!$role){
            	return false;
            }

            if ($role->name == 'supplier-superadmin'){

                $supplier = $user->suppliers()->withoutGlobalScope('active')->first();

                $dataSupplier = [
                    'city_id'       =>  $data['city_id'],
                    'country_id'    =>  $data['country_id'],
                    'state_id'      =>  $data['state_id'],
                    'address'       =>  $data['settings']['address'],
                    'company_name'  =>  $data['settings']['company_name'],
                    'status'        =>  1,
                ];

                $dataContractor['lat'] = $coordinates->getLatitude();
                $dataContractor['lon'] = $coordinates->getLongitude();

                if($supplier) {
	                $supplier->update( $dataSupplier );
                }
            }
	        DB::commit();
	        Mail::to($user->email)->send(new approved());
	        return true;

        }catch(\Exception $e){

            DB::rollback();
            throw $e;
        }

    }

    public function rejectApplicant($id)
    {
        try{
            DB::beginTransaction();
            $user = $this->updateBy(['status'=>User::STATUS_REJECTED],$id);
            DB::commit();
	        Mail::to($user->email)->send(new rejected());
        }catch (\Exception $e){
            DB::rollback();
            throw $e;
        }
    }
	public function createdBy( $parent_id ) {
		$this->query->where('creator_user_id',$parent_id);
		return $this;
	}

	public function allChilds( $parent_id ) {
		$this->query=$this->model->find($parent_id)->recursiveUsers();
		return $this;
	}

	public function accesibleUsers( User $user ) {
    	$this->query->where(function($q1) use ($user){

		    $q1->whereExists(function ($exists) use ($user){
			    $exists->select(DB::raw('id as sorted_id'))->from(DB::raw("(select * from users
         order by creator_user_id, id) users_sorted,
        (select @pv := ".$user->id.") initialisation"))
			           ->whereRaw("find_in_set(creator_user_id, @pv)
					and length(@pv := concat(@pv, ',', id))")
			           ->havingRaw(DB::raw('sorted_id = users.id'));
		    });

		    $contractors = $user->contractors()->pluck('id');
		    $q1->orWhereExists(function ($exists) use($contractors){
			    $exists->select(DB::raw(1))->from('contractor_user')
			           ->whereRaw('contractor_user.user_id = users.id')
			           ->whereIn('contractor_user.contractor_id',$contractors);
		    });
	    });
		$role = $user->rols()->first();
		$roles = $this->role_repository->allowedCrudRoles($role)->pluck('id');
		$this->query->whereExists(function ($exists) use($user,$roles){
			$exists->select(DB::raw(1))->from('role_user')
			       ->whereRaw('role_user.user_id = users.id')
			       ->whereIn('role_user.role_id',$roles);
		});

		return $this;
	}

	public function destroy( $value = null, $field = 'id' ) {
    	try {
		    DB::beginTransaction();

		    $user = $this->model->withoutGlobalScope( nonDeletedScope::class )->where( $field, $value )->first();
		    $user->rols()->detach();
		    $user->suppliers()->detach();
		    $result=$user->forceDelete();
		    DB::commit();
		    return $result;
	    }catch(\Error $error){
		    DB::rollback();
		    throw $error;
	    }
	}
}