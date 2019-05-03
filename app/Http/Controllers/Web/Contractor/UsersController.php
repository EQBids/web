<?php

namespace App\Http\Controllers\Web\Contractor;

use App\Http\Requests\User\createSubUserRequest;
use App\Http\Requests\User\updateSubUserRequest;
use App\Http\Requests\User\userCDRequest;
use App\Repositories\Eloquent\Buyer\OfficeRepository;
use App\Repositories\Eloquent\Geo\cityRepository;
use App\Repositories\Eloquent\Geo\CountryRepository;
use App\Repositories\Eloquent\Geo\stateRepository;
use App\Repositories\Eloquent\roleRepository;
use App\Repositories\Eloquent\UsersRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
	protected $users_repository,$role_repository,$country_repository,$state_repository,$city_repository,$office_repository;
	public function __construct(UsersRepository $users_repository,roleRepository $role_repository,
		CountryRepository $country_repository, stateRepository $state_repository,cityRepository $city_repository,
		OfficeRepository $office_repository) {
		$this->users_repository=$users_repository;
		$this->role_repository=$role_repository;
		$this->country_repository=$country_repository;
		$this->state_repository=$state_repository;
		$this->city_repository=$city_repository;
		$this->office_repository=$office_repository;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $data=$this->users_repository->accesibleUsers(Auth::user())->with('rols')->paginate(1000);
	    return view('web.contractor.user.index')->with(compact('data'));
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
	    $offices = $this->office_repository->findAllWhereUserBelongsTo(Auth::user()->id);
	    $viewData = compact('roles','offices');
	    if($country){
		    $viewData['country']=$country;
	    }

	    if($state){
		    $viewData['state']=$state;
	    }

	    if($city){
		    $viewData['city']=$city;
	    }

	    return view('web.contractor.user.create')->with($viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(createSubUserRequest $request)
    {
    	try {
    		$current_user = Auth::user();
		    $role = $this->role_repository->findOneBy( $request->get( 'role' ) );

		    $userData = $request->only( [
			    'first_name',
			    'last_name',
			    'phone',
			    'email',
			    'status'
		    ] );
		    if (!isset( $userData['status'] ) ) {
			    $userData['status'] = User::STATUS_ACTIVE;
		    }

		    $contractor = $this->office_repository->findOneBy($request->get('office_id'));
		    $userData['country_id']=$contractor->country_id;
		    $userData['state_id']=$contractor->state_id;
		    $userData['city_id']=$contractor->city_id;

		    $jsonData = [
			    'postal_code'      => $request->get( 'postal_code' ),
			    'secondary_phone'  => $request->get( 'phone_alt' ),
			    'address'          => $request->get( 'address' ),
			    'company_position' => $request->get( 'company_position' ),
		    ];

		    $userData['settings'] = $jsonData;
		    $userData['contractor_id']=$request->get('office_id');


		    if(Auth::user()->is_contractor && Auth::user()->contractor->sites->first() && ($role->name=='contractor-worker' || $role->name=='contractor-manager')){
		    	$userData['site_id']=Auth::user()->contractor->sites->first()->id;
		    }



		    $this->users_repository->createWithRole( $userData, $role );

		    return redirect( route( 'contractor.users.index' ) )->with( 'notifications', collect( [
			    [
				    'text' => 'User created successfully',
				    'type' => 'success',
				    'wait' => 10,
			    ]
		    ] ) );

	    }catch(\Exception $exception){
    		return redirect()->back()->with( 'notifications', collect( [
			    [
				    'text' => 'Error creating the user',
				    'type' => 'error',
				    'wait' => 10,
			    ]
		    ] ) );
	    }
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
    public function edit(User $user)
    {

	    $role = Auth::user()->rols()->first();
	    $roles = $this->role_repository->allowedCrudRoles($role);
	    $offices = $this->office_repository->findAllWhereUserBelongsTo(Auth::user()->id);

	    $viewData = compact('roles','user','offices');
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

	    return view('web.contractor.user.edit')->with($viewData);
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
			return redirect()->back();
		}
		$role = $this->role_repository->findOneBy($request->get('role'));

		$userData =  $request->only(['first_name','last_name','country_id','state_id','city_id','phone','email','status']);
		$contractor = $this->office_repository->findOneBy($request->get('office_id'));
		$userData['country_id']=$contractor->country_id;
		$userData['state_id']=$contractor->state_id;
		$userData['city_id']=$contractor->city_id;


		$jsonData = [
			'postal_code'       =>  $request->get('postal_code'),
			'secondary_phone'   =>  $request->get('phone_alt'),
			'address'    =>  $request->get('address'),
			'company_position'          =>  $request->get('company_position'),
		];

		$userData['settings'] = $jsonData;

		$user = $this->users_repository->updateWithRole($user->id,$userData,$role);
		return redirect(route('contractor.users.index'))->with('notifications',collect([
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
	public function destroy(User $user)
	{
		$this->users_repository->delete($user->id);
		return redirect(route('contractor.users.index'))->with('notifications',collect([
			[
				'text'=>'User deleted sucessfully',
				'type'=>'warning',
				'wait'=>10
			]
		]));
	}

	public function delete(User $user){
		return view('web.contractor.user.delete')->with(compact('user'));
	}
}
