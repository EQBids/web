<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\User\contractorSignupRequest;
use App\Http\Requests\User\createSubUserRequest;
use App\Http\Requests\User\queryRequest;
use App\Http\Requests\User\selfUpdateUserRequest;
use App\Http\Requests\User\storeRequest;
use App\Http\Requests\User\subUserRequest;
use App\Http\Requests\User\updateSubUserRequest;
use App\Http\Requests\User\userCDRequest;
use App\Http\Resources\roleResource;
use App\Http\Resources\userResource;
use App\Mail\Auth\ContractorSignup;
use App\Models\Role;
use App\Repositories\Eloquent\Buyer\OfficeRepository;
use App\Repositories\Interfaces\Buyer\contractorRepositoryInterface;
use App\Repositories\Interfaces\Geo\cityRepositoryInterface;
use App\Repositories\Interfaces\roleRepositoryInterface;
use App\Repositories\Interfaces\usersRepositoryInterface;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


/**
 * Class UserController
 * @package App\Http\Controllers\Api
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host=L5_SWAGGER_CONST_HOST,
 *     @SWG\Info(
 *          version="1.0",
 *          title="EQUBIDS API"
 *      ),
 *      @SWG\Definition(
 *          definition="form_error",
 *          @SWG\Property(property="error",type="integer",description="always 1",default=1,example=1),
 *          @SWG\Property(property="error_message",type="string"),
 *          @SWG\Property(property="errors",type="array",description="a list of form validation errors",
 *                      items=@SWG\Items(type="array",
 *                              items=@SWG\Items(type="string")
 *                              )
 *                      )
 *      )
 * )
 *
 * @SWG\Definition(
 *      definition="user_object",
 *      required={"email","first_name","last_name","city","country"},
 *      @SWG\Property(property="role",type="integer"),
 *      @SWG\Property(property="first_name",type="string",maxLength=100),
 *      @SWG\Property(property="last_name",type="string",maxLength=100),
 *      @SWG\Property(property="email",type="string",maxLength=100),
 *      @SWG\Property(property="phone",type="string",maxLength=15),
 *      @SWG\Property(property="city_id",type="integer"),
 *      @SWG\Property(property="metro_id",type="integer"),
 *      @SWG\Property(property="state_id",type="integer"),
 *      @SWG\Property(property="country_id",type="integer"),
 *      @SWG\Property(property="postal_code",type="string",maxLength=8),
 *      @SWG\Property(property="secondary_phone",type="string",maxLength=15),
 *      @SWG\Property(property="address",type="string",maxLength=200),
 *      @SWG\Property(property="company_position",type="string",maxLength=100),
 *      @SWG\Property(property="company_name",type="string",maxLength=100),
 * )
 *
 * @SWG\Definition(
 *      definition="user_object_request",
 *      required={"email","first_name","last_name","city","country"},
 *      @SWG\Property(property="role",type="integer"),
 *      @SWG\Property(property="first_name",type="string",maxLength=100),
 *      @SWG\Property(property="last_name",type="string",maxLength=100),
 *      @SWG\Property(property="email",type="string",maxLength=100),
 *      @SWG\Property(property="status",type="integer",description="User status: 1 => Active 2=>Inactive"),
 *      @SWG\Property(property="office_id",type="integer",description="id of the office the user belongs to")
 * )
 *
 * @SWG\Definition(
 *     definition="role_object",
 *     @SWG\Property(property="id",type="integer"),
 *     @SWG\Property(property="name",type="string")
 * )
 *
 */
class UserController extends Controller
{
	protected $user_repository;
	protected $contractor_repository;
	protected $role_repository;
	protected $city_repository;
	protected $office_repository;

	public function __construct(usersRepositoryInterface $user_repository,
								contractorRepositoryInterface $contractor_repository,
								roleRepositoryInterface $role_repository,
								cityRepositoryInterface $city_repository,
								OfficeRepository $office_repository) {
		$this->user_repository=$user_repository;
		$this->contractor_repository=$contractor_repository;
		$this->role_repository=$role_repository;
		$this->city_repository=$city_repository;
		$this->office_repository=$office_repository;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
	 *
	 * @SWG\Get(
	 *      path="/api/users",
	 *      summary="List the users the current user can manage",
	 *      produces={"application/json"},
	 *      tags={"users"},
	 *      @SWG\Parameter(
	 *          in="query",
	 *          name="email",
	 *          description="filter users by its email",
	 *          required=false,
	 *          type="string",
	 *     @SWG\Schema(type="string")
	 *      ),
	 *      @SWG\Parameter(
	 *          in="query",
	 *          name="first_name",
	 *          description="filter users by its first name",
	 *          required=false,
	 *          type="string",
	 *     @SWG\Schema(type="string")
	 *      ),
	 *      @SWG\Parameter(
	 *          in="query",
	 *          name="last_name",
	 *          description="filter users by its last name",
	 *          required=false,
	 *          type="string",
	 *     @SWG\Schema(type="string")
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="List of the filtered users that the current user can manage",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/user_object"))
	 *          )
	 *      )
	 * )
     */
    public function index(queryRequest $request)
    {
    	$current_user = Auth::user();
    	if($current_user->rols->first()->name=='contractor-superadmin'){
		    $this->user_repository->allChilds($current_user->id);
	    }else{
    	    $this->user_repository->createdBy($current_user->id);
    	}

	    $filter = $request->only(['first_name','last_name','email']);
    	foreach ($filter as $item=>$value){
		    $this->user_repository->like($item,$value);
	    }

	    $paginator=$this->user_repository->paginate();

    	$paginator->appends($request->except(['page']));
		return userResource::collection($paginator);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *      path="/api/users",
     *      summary="Stores a new user 'owned' for the current user",
     *      produces={"application/json"},
     *      tags={"users"},
     *     @SWG\Parameter(
     *          in="body",
     *          name="body",
     *          description="user's data",
     *          required=true,
     *          type="object",
     *     @SWG\Schema(type="object",ref="#/definitions/user_object_request")
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="stores the new user and return it's data",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/user_object"))
     *          )
     *      )
     * )
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



		   $user=$this->user_repository->createWithRole( $userData, $role );
		    return userResource::make($user);

	    }catch(\Exception $exception){
		    return response()->json(['message'=>__('Error creating the user') ],400);
	    }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *      path="/api/users/{user}",
     *      summary="return the information of the specified user if the authenticated one has the permissions",
     *      produces={"application/json"},
     *      tags={"users"},
     *     @SWG\Parameter(
     *          in="path",
     *          name="user",
     *          description="user's id",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="returns the information of the specified user",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/user_object"))
     *          )
     *      )
     * )
     */
    public function show(subUserRequest $request,User $user)
    {
        return userResource::make($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *      path="/api/users/{user}",
     *      summary="updates the information of the specified user if the authenticated user is allowed",
     *      produces={"application/json"},
     *      tags={"users"},
     *     @SWG\Parameter(
     *          in="path",
     *          name="user",
     *          description="user's id",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *     @SWG\Parameter(
     *          in="body",
     *          name="body",
     *          description="user's data",
     *          required=true,
     *          type="object",
     *     @SWG\Schema(type="object",ref="#/definitions/user_object_request")
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="updates the specified user and return it's data",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/user_object"))
     *          )
     *      )
     * )
     */
    public function update(updateSubUserRequest $request, User $user)
    {
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

	    $user = $this->user_repository->updateWithRole($user->id,$userData,$role);
	    return userResource::make($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     *
     * @SWG\Delete(
     *      path="/api/users/{user}",
     *      summary="deletes the specified user if the authenticated one has the permissions",
     *      produces={"application/json"},
     *      tags={"users"},
     *     @SWG\Parameter(
     *          in="path",
     *          name="user",
     *          description="user's id",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="the specified user was deleted",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(  property="message",
     *                              type="string",
     *                              description="confirmation message"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy(subUserRequest $request,User $user)
    {
       // dd($user);
    }

	/**
	 * @param contractorSignupRequest $request
	 *
	 * @SWG\Post(
	 *     path="api/contractor/signup",
	 *     summary="Allows contractors to signup",
	 *      consumes={"application/json"},
	 *     @SWG\Parameter(
	 *          in="body",
	 *          name="userdata",
	 *          description="constractor to create",
	 *          @SWG\Schema(
	 *              type="object",
	 *              required={"first_name","last_name","email","company_name","city","state","country","phone"},
	 *              @SWG\Property(property="first_name",type="string",maxLength=100),
	 *              @SWG\Property(property="last_name",type="string",maxLength=100),
	 *	            @SWG\Property(property="email",type="email",maxLength=100),
	 *	            @SWG\Property(property="company_name",type="string",maxLength=100),
	 *              @SWG\Property(property="company_position",type="string",maxLength=100),
	 *              @SWG\Property(property="address",type="string",maxLength=200),
	 *              @SWG\Property(property="city",type="integer",description="The integer id of a city on eqbids"),
	 *              @SWG\Property(property="metro",type="integer",description="The integer id of a metro/area on eqbids"),
	 *              @SWG\Property(property="state",type="integer",description="The integer id of a state on eqbids"),
	 *              @SWG\Property(property="country",type="integer",description="The integer id of a country on eqbids"),
	 *              @SWG\Property(property="postal_code",type="string",maxLength=8),
	 *              @SWG\Property(property="phone",type="string",maxLength=15),
	 *              @SWG\Property(property="phone_alt",type="string",maxLength=15),
	 *          )
	 *      ),
	 *     @SWG\Response(
	 *          response=422,
	 *          description="error",
	 *          @SWG\Schema(ref="#/definitions/form_error"),
	 *      )
	 * )
	 *
	 */
    public function contractorSignup(contractorSignupRequest $request){
    	$role = $this->role_repository->findOneBy('contractor-superadmin','name');
    	$user_fields=$request->only(['first_name','last_name','email','phone','city','state','country','metro']);
    	$user_fields['city_id']=$user_fields['city'];
	    $user_fields['metro_id']=$request->get('metro');
	    $user_fields['state_id']=$user_fields['state'];
	    $user_fields['country_id']=$user_fields['country'];
	    $user_fields['settings']=$request->only(['company_name',
		                        'company_position','address','postal_code','phone_alt']);

	    $user=$this->user_repository->create($user_fields);


	    if($request->input('address')){
	    	$city=$this->city_repository->findOneBy($request->input('city'));
		    $contractor_fields=$request->only(['company_name','address']);
		    $contractor_fields['lat']=$city->lat;
		    $contractor_fields['lon']=$city->lon;
		    $contractor_fields['details']=array();
		    $contractor=$this->contractor_repository->create($contractor_fields);
		    $user->contractors()->attach($contractor->id);
	    }

	    Mail::to($user->email)->send(new ContractorSignup($user));
	    return response()->json(['message'=>'successful signup']);

    }

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 *
	 * @SWG\Get(
	 *      path="/api/users/roles",
	 *      summary="List the roles the current user can use on his managed users",
	 *      produces={"application/json"},
	 *      tags={"users"},
	 *      @SWG\Response(
	 *          response=200,
	 *          description="List of roles the authenticated user can use",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/role_object"))
	 *          )
	 *      )
	 * )
	 */
    public function allowedRoles(Request $request){
    	return roleResource::collection($this->role_repository->allowedCrudRoles(Auth::user()->rols->first()));
    }

	/**
	 * @return static
	 * @SWG\Get(
	 *      path="/api/profile",
	 *      summary="return the information of the authenticated user",
	 *      produces={"application/json"},
	 *      tags={"users"},
	 *     @SWG\Response(
	 *          response=200,
	 *          description="returns the information of the authenticated user",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/user_object"))
	 *          )
	 *      )
	 * )
	 */
    public function self(){
    	return userResource::make(Auth::user());
    }

	/**
	 * @param selfUpdateUserRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 *
	 * @SWG\Put(
	 *      path="/api/profile",
	 *      summary="updates the information of the  authenticated user",
	 *      produces={"application/json"},
	 *      tags={"users"},
	 *     @SWG\Parameter(
	 *          in="body",
	 *          name="body",
	 *          description="user's data",
	 *          required=true,
	 *          type="object",
	 *     @SWG\Schema(type="object",ref="#/definitions/user_object_request")
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="updates the authenticated user and return it's data",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/user_object"))
	 *          )
	 *      )
	 * )
	 */
    public function selfUpdate(selfUpdateUserRequest $request){
    	$requestData = $request->all();
    	$requestData['role']=Auth::user()->rols->first()->id;
    	$request->merge($requestData);
		return $this->update($request,Auth::user());
    }

}
