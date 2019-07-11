<?php

namespace App\Repositories\Eloquent\Buyer;


use function App\Http\buildAddress;
use App\Models\Buyer\Contractor;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Buyer\officeRepositoryInterface;
use App\Repositories\Interfaces\Geo\cityRepositoryInterface;
use App\Repositories\Interfaces\Geo\CountryRepositoryInterface;
use App\Repositories\Interfaces\Geo\stateRepositoryInterface;
use App\Repositories\Interfaces\geocodingRepositoryInterface;
use App\Repositories\Interfaces\usersRepositoryInterface;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OfficeRepository extends BaseRepository implements officeRepositoryInterface
{

    protected $geoRepo,$cityRepo,$stateRepo,$countryRepo,$usersRepo;
    public function __construct(Contractor $model,
                                geocodingRepositoryInterface $geoRepo,
                                cityRepositoryInterface $cityRepo,
                                stateRepositoryInterface $stateRepo,
                                CountryRepositoryInterface $countryRepo,
                                usersRepositoryInterface $usersRepo
    )
    {
        parent::__construct($model);
        $this->geoRepo = $geoRepo;
        $this->cityRepo = $cityRepo;
        $this->stateRepo = $stateRepo;
        $this->countryRepo = $countryRepo;
        $this->usersRepo = $usersRepo;
    }

    public function create(array $data)
    {
        try{
            DB::beginTransaction();

            $city = $this->cityRepo->findOneBy($data['city_id']);
            $state = $this->stateRepo->findOneBy($data['state_id']);
            $country = $this->countryRepo->findOneBy($data['country_id']);

            $fullAddress = buildAddress($data['address'],$country->name,$state->name,$city->name);

            $coordinates = $this->geoRepo->getAddressCoordinates($fullAddress,$city);

            $data['lat'] = $coordinates->getLatitude();
            $data['lon'] = $coordinates->getLongitude();
            if(!isset($data['details'])){
                $data['details'] = [];
            }

            $contractor = parent::create($data);

            DB::commit();
        }catch (\Exception $e){
            DB::rollback();
            throw $e;
        }

    }

    public function update($id, $data)
    {
        try{
            DB::beginTransaction();

            $office = $this->with(['country','state','city'])->findOneBy($id);

            $city = $office->city;
            $state = $office->state;
            $country = $office->country;

            $fullAddress = buildAddress($data['address'],$country->name,$state->name,$city->name);

            $coordinates = $this->geoRepo->getAddressCoordinates($fullAddress,$city);

            $data['lat'] = $coordinates->getLatitude();
            $data['lon'] = $coordinates->getLongitude();

            if(!isset($data['details'])){
                $data['details'] = [];
            }

            parent::updateBy($data,$id);

            DB::commit();

        }catch (\Exception $e){
            DB::rollback();
            throw $e;
        }
    }

    public function findAllWorkersAffiliatedTo($id)
    {
        //TODO check if there's a more efficient way to do this.

       $usersBelongingToContractor = DB::table('contractor_user')->select('user_id')->where('contractor_id',$id);
       
       $usersIds = DB::table('site_user')->select('site_user.user_id')
                                                ->join('sites','sites.id','=','site_user.site_id')
                                                ->join('contractors','sites.contractor_id','=','contractors.id')
                                                ->where('contractors.id',$id)
                                                ->union($usersBelongingToContractor)->get();

       if(isset($usersIds) && count($usersIds) > 0){

           $arrayOfUsersIds = $usersIds->pluck('user_id')->toArray();

           //We return all the users that either belong to the contractor model with $id, or belong to a job site that belongs
           //to the mentioned contractor "office"
           return $this->usersRepo->with('rols')->findAllWhereIn($arrayOfUsersIds,'id');
       }

       return null;

    }

    public function findEligibleWorkersForOffice($id,User $userCreating)
    {
        $role = $userCreating->rols()->first();

        if(isset($role)){

            switch (strtolower($role->name)){
                case 'contractor-manager':{

                    /// /Selecting only users that have the roles: [contractor-worker] role and do not belong already to the current office
                    $eligibleUsers = DB::table('users')->join('role_user','role_user.user_id','=','users.id')
                        ->join('roles',function ($join){
                            $join->on('role_user.role_id','=','roles.id')
                                ->where('roles.name','contractor-worker');
                        })
                        ->whereNotExists(function ($query) use ($id){
                            $query->select('user_id')->from('contractor_user')
                                ->where('contractor_id',$id)
                                ->whereRaw('contractor_user.user_id = users.id');
                        })->whereNotExists(function ($query) use ($id){
                            $query->select('site_user.user_id')->from('site_user')
                                ->join('sites','site_user.site_id','=','sites.id')
                                ->join('contractors','contractors.id','=','sites.contractor_id')
                                ->where('contractors.id',$id)
                                ->whereRaw('site_user.user_id = users.id');

                        })->select('users.id','users.first_name','users.last_name')->get();
                    break;
                }
                case 'contractor-admin':{

                    /// /Selecting only users that have the role [contractor-manager,contractor-worker] and do not belong already to the current office
                    $eligibleUsers = DB::table('users')->join('role_user','role_user.user_id','=','users.id')
                        ->join('roles',function ($join){
                            $join->on('role_user.role_id','=','roles.id')
                                ->where(function ($query){
                                    $query->where('roles.name','contractor-manager')
                                        ->orWhere('roles.name','contractor-worker');
                                });
                        })
                        ->whereNotExists(function ($query) use ($id){
                            $query->select('user_id')->from('contractor_user')
                                ->where('contractor_id',$id)
                                ->whereRaw('contractor_user.user_id = users.id');
                        })->whereNotExists(function ($query) use ($id){
                            $query->select('site_user.user_id')->from('site_user')
                                ->join('sites','site_user.site_id','=','sites.id')
                                ->join('contractors','contractors.id','=','sites.contractor_id')
                                ->where('contractors.id',$id)
                                ->whereRaw('site_user.user_id = users.id');

                        })->select('users.id','users.first_name','users.last_name')->get();
                    break;
                }
                case 'contractor-superadmin':{
                    //Selecting only users that have the role any contractor role and do not belong already to the current office
                    $eligibleUsers = DB::table('users')->join('role_user','role_user.user_id','=','users.id')
                        ->join('roles',function ($join){
                            $join->on('role_user.role_id','=','roles.id')
                                ->where('roles.name','like','contractor-%');
                        })
                        ->whereNotExists(function ($query) use ($id){
                            $query->select('user_id')->from('contractor_user')
                                ->where('contractor_id',$id)
                                ->whereRaw('contractor_user.user_id = users.id');
                        })->whereNotExists(function ($query) use ($id){
                            $query->select('site_user.user_id')->from('site_user')
                                                                ->join('sites','site_user.site_id','=','sites.id')
                                                                ->join('contractors','contractors.id','=','sites.contractor_id')
                                                                ->where('contractors.id',$id)
                                                                ->whereRaw('site_user.user_id = users.id');

                        })->select('users.id','users.first_name','users.last_name')->get();

                    break;
                }
                default :{
                    $eligibleUsers = null;
                    break;
                }
            }

            return $eligibleUsers;
        }

        return null;
    }

    public function addWorkerToOffice($id, $userId)
    {
        $user = $this->usersRepo->findOneBy($userId);
        return $user->contractors()->attach($id);
    }

    public function findAllWhereUserBelongsTo($userId)
    {
        return DB::table('contractors')->whereRaw('contractors.deleted_at is null')->where('contractors.user_id',$userId)
        ->orderBy('created_at')->get();
    }
}