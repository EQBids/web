<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Requests\User\updateSubUserRequest;
use App\Http\Requests\User\userCDRequest;
use App\Models\Role;
use App\Repositories\Interfaces\Geo\cityRepositoryInterface;
use App\Repositories\Interfaces\Geo\CountryRepositoryInterface;
use App\Repositories\Interfaces\Geo\stateRepositoryInterface;
use App\Repositories\Interfaces\geocodingRepositoryInterface;
use App\Repositories\Interfaces\roleRepositoryInterface;
use App\Repositories\Interfaces\usersRepositoryInterface;
use App\User;
use Geocoder\Model\Coordinates;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

	protected $user_repository;
	protected $role_repository;
	protected $country_repository;
	protected $state_repository;
	protected $city_repository;
	protected $geocoding_repository;

	public function __construct(usersRepositoryInterface $user_repository,
								roleRepositoryInterface $role_repository,
								CountryRepositoryInterface $country_repository,
								stateRepositoryInterface $state_repository,
								cityRepositoryInterface $city_repository,
								geocodingRepositoryInterface $geocoding_repository
) {
		$this->user_repository=$user_repository;
		$this->role_repository=$role_repository;
		$this->country_repository=$country_repository;
		$this->state_repository=$state_repository;
		$this->city_repository=$city_repository;
		$this->geocoding_repository=$geocoding_repository;

		$this->middleware('auth');
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$data=$this->user_repository->with(['rols','suppliers','contractors','city'])->paginate(1000);
    	return view('web.admin.user.index')->with(compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$role = Auth::user()->rols()->first();
    	$roles = $this->role_repository->allowedCrudRoles($role);
    	$country = $this->country_repository->findOneBy(old('country_id',null));
	    $state = $this->state_repository->findOneBy(old('state_id',null));
	    $city = $this->city_repository->findOneBy(old('city_id',null));

	    $viewData = compact('roles');
	    if($country){
		    $viewData['country']=$country;
	    }

	    if($state){
		    $viewData['state']=$state;
	    }

	    if($city){
		    $viewData['city']=$city;
	    }

        return view('web.admin.user.create')->with($viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(userCDRequest $request)
    {
	    $role = Role::find($request->get('role'));

	    $userData =  $request->only(['first_name','last_name','country_id','state_id','city_id','phone','email']);
	    if(isset($userData['status'])){
	    	$userData['status']=User::STATUS_ACTIVE;
	    }

	    if($request->get('address')) {

		    $address = $request->get('address');
		    $city = $this->city_repository->findOneBy($request->get('city_id'));

		    if($address) {
			    $address .= ', ' . $city->name.', '.$city->country->name;
		    }else{
			    $address = $city->name.', '.$city->country->name;
		    }
		    $coordinates = $this->geocoding_repository->getAddressCoordinates($address,$city);

		    if ( $role->name == 'contractor-superadmin' ) {
			    $contractorData         = [

				    'company_name' => $request->get( 'company_name' ) ? $request->get( 'company_name' )
					    : $request->get( 'first_name' ) . ' ' . $request->get( 'last_name' ),
				    'address'      => $request->get( 'address' ),
				    'lat'          => $coordinates->getLatitude(),
				    'lon'          => $coordinates->getLatitude(),
				    'details'      => array(),
			    ];
			    $userData['contractor'] = $contractorData;
		    } elseif ( $role->name == 'supplier-superadmin' && $request->get( 'address' ) ) {
			    $supplierData = [
				    'details' => array(),
				    'status'  => 0,
				    'name' => $request->get( 'company_name' ) ? $request->get( 'company_name' )
					    : $request->get( 'first_name' ) . ' ' . $request->get( 'last_name' ),
				    'address' => $request->get( 'address' ),
				    'lat'     => $coordinates->getLatitude(),
				    'lon'     => $coordinates->getLatitude(),
			    ];

			    $userData['supplier'] = $supplierData;
		    }
	    }

	    $jsonData = [
		    'postal_code'       =>  $request->get('postal_code'),
		    'secondary_phone'   =>  $request->get('phone_alt'),
		    'address'    =>  $request->get('address'),
		    'company_position'          =>  $request->get('company_position'),
	    ];

	    $userData['settings'] = $jsonData;

	    $user = $this->user_repository->createWithRole($userData,$role);
		return redirect(route('admin.users.index'))->with('notifications',collect([
			[
				'text'=>'User created successfully',
				'type'=>'success',
				'wait'=>10,
			]
		]));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$user=User::findOrFail($id);
    	$role = Auth::user()->rols()->first();
	    $roles = $this->role_repository->allowedCrudRoles($role);

	    $viewData = compact('roles','user');
	    $country = $this->country_repository->findOneBy(old('country_id',$user->country_id));
	    $state = $this->state_repository->findOneBy(old('state_id',$user->state_id));
	    $city = $this->city_repository->findOneBy(old('city_id',$user->city_id));

	    if($country){
		    $viewData['country']=$country;
	    }

	    if($state){
		    $viewData['state']=$state;
	    }

	    if($city){
		    $viewData['city']=$city;
	    }

	    return view('web.admin.user.edit')->with($viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateSubUserRequest $request, User $user)
    {
		if(!$user){
    		return redirect('/');
	    }
	    $role = Role::find($request->get('role'));

	    $userData =  $request->only(['first_name','last_name','country_id','state_id','city_id','phone','email','company_name','address']);

	    $address = $request->get('address');
	    $city = $this->city_repository->with('country')->findOneBy($request->get('city_id'));
	    if($address) {
		    $address .= ', ' . $city->name.', '.$city->country->name;
	    }else{
		    $address = $city->name.', '.$city->country->name;
	    }

	    $coordinates = $this->geocoding_repository->getAddressCoordinates($address,$city);
		
	    if($role->name == 'contractor-superadmin' && $user->contractors->count()==0 && $request->get('address')){
		    $contractorData = [

			    'company_name'      =>  $request->get('company_name')?$request->get('company_name')
				    :$request->get('first_name').' '.$request->get('last_name'),
			    'address'           =>  $request->get('address'),
			    'lat'=> $coordinates->getLatitude(),
			    'lon'=> $coordinates->getLatitude(),
			    'details'           =>  array(),
		    ];
		    $userData['contractor'] = $contractorData;
	    }
	    elseif ($role->name == 'supplier-superadmin' && $user->suppliers->count()==0 && $request->get('address')){
		    $supplierData = [
				'company_name'      =>  $request->get('company_name')?$request->get('company_name')
				    :$request->get('first_name').' '.$request->get('last_name'),
			    'details'       =>  array(),
			    'status'        =>  0,
			    'name'          =>  $request->get('first_name').' '.$request->get('last_name'),
			    'address'       =>  $request->get('address'),
			    'lat'=> $coordinates->getLatitude(),
			    'lon'=> $coordinates->getLatitude(),

		    ];

		    $userData['supplier'] = $supplierData;
	    }

	    $jsonData = [
		    'postal_code'       =>  $request->get('postal_code'),
		    'secondary_phone'   =>  $request->get('phone_alt'),
		    'address'    =>  $request->get('address'),
		    'company_position'          =>  $request->get('company_position'),
	    ];

	    $userData['settings'] = $jsonData;
	    $user = $this->user_repository->updateWithRole($user->id,$userData,$role);
	    return redirect(route('admin.users.index'))->with('notifications',collect([
	    	[
	    		'text'=>'User updated sucessfully',
			    'type'=>'success',
			    'wait'=>10
		    ]
	    ]));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$this->user_repository->delete($id);
	    return redirect(route('admin.users.index'))->with('notifications',collect([
		    [
			    'text'=>'User deleted sucessfully',
			    'type'=>'warning',
			    'wait'=>10
		    ]
	    ]));
    }

    public function delete($id){
    	$user=$this->user_repository->findOneBy($id);
    	return view('web.admin.user.delete')->with(compact('user'));
    }
}
