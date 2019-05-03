<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/14/18
 * Time: 8:59 AM
 */

namespace App\Repositories\Eloquent\Buyer;


use App\Models\Buyer\Site;
use App\Models\Geo\City;
use App\Models\Geo\Country;
use App\Models\Geo\Metro;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Buyer\siteRepositoryInterface;
use App\Repositories\Interfaces\geocodingRepositoryInterface;
use App\Repositories\Interfaces\usersRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class siteRepository extends BaseRepository implements siteRepositoryInterface {

	protected  $geocoding_repository, $usersRepo;
	public function __construct(geocodingRepositoryInterface $geocoding_repository,usersRepositoryInterface $usersRepo ) {
		parent::__construct( new Site() );
		$this->geocoding_repository=$geocoding_repository;
		$this->usersRepo = $usersRepo;
	}

	public function create( array $data ) {

	    try{

	        DB::beginTransaction();

            if (!isset($data['lat']) || !isset($data['lon'])){

                $city = City::find($data['city_id']);
                $country = Country::find($data['country_id']);
                $state = City::find($data['state_id']);

                $fullAddress = $data['address'].', '.$city->name;

                if($data['metro_id']){
                    $metro = Metro::find($data['metro_id']);
                    $fullAddress.=', '.$metro->name;
                }
                $fullAddress.=', '.$state->iso_code.', '.$country->name;
                $coordinates=$this->geocoding_repository->getAddressCoordinates($fullAddress,$city);
                $data['lat']=$coordinates->getLatitude();
                $data['lon']=$coordinates->getLongitude();

            }

            $site = parent::create( $data );

            if(isset($data['user_id'])){

                $user = $this->usersRepo->with('rols')->findOneBy($data['user_id']);
                $userRole = $user->rols()->first();
                $site->users()->attach([
                	$data['user_id']=>[
                		'role_id'=>$userRole->id
	                ]
                ]);

            }
            DB::commit();
            return $site;
        }catch (\Exception $e){
	        DB::rollback();
	        throw $e;
        }

	}

	public function paginateBy( $filters, $perPage = 10, $columns=['*'] ) {
		$query=$this->model->query();
		if ($filters){
			foreach ($filters as $key=>$value) {
				if(is_int($value)){
					$query = $query->where($key,$value);
				}else{
					$query = $query->where( DB::raw( 'lower('.$key.')' ), 'like', '%' . strtolower( $value ) . '%' );
				}

			}
		}
		return $query->paginate($perPage)->appends(Input::except(['page']));
	}

	public function updateBy( array $data, $value = null, $field = 'id' ) {
		if (!isset($data['lat']) || !isset($data['lon'])){

			$city = City::find($data['city_id']);
			$country = Country::find($data['country_id']);
			$state = City::find($data['state_id']);

			$fullAddress = $data['address'].', '.$city->name;

			if($data['metro_id']){
				$metro = Metro::find($data['metro_id']);
				$fullAddress.=', '.$metro->name;
			}
			$fullAddress.=', '.$state->iso_code.', '.$country->name;
			$coordinates=$this->geocoding_repository->getAddressCoordinates($fullAddress,$city);
			$data['lat']=$coordinates->getLatitude();
			$data['lon']=$coordinates->getLongitude();

		}

		$uContractor = Auth::user()->contractor;
		$site = Site::where($field,$value)->first();
		$user=Auth::user();
		if ($site && (($uContractor && $uContractor->id == $site->contractor_id) || $user->hasAnyRol([
					'superadmin',
					'admin',
					'staff',
				]))){
			return parent::updateBy( $data, $value, $field );
		}else{
			throw new Exception(__('insuficent privileges'));
		}


	}

	public function delete( $value = null, $field = 'id' ) {
		$uContractor = Auth::user()->contractor;
		$site = Site::where($field,$value)->first();
		$user=Auth::user();
		if ($site && (($uContractor && $uContractor->id == $site->contractor_id) || $user->hasAnyRol([
					'superadmin',
					'admin',
					'staff',
				]))){
				return parent::delete($value,$field);
		}else{
			throw new Exception(__('insuficent privileges'));
		}
	}


}