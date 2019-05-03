<?php

namespace App\Http\Controllers\Api\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Buyer\Site\SiteCDRequest;
use App\Http\Requests\Buyer\Site\siteSearchRequest;
use App\Http\Resources\Buyer\resumeSiteResource;
use App\Http\Resources\Buyer\siteResource;
use App\Models\Buyer\Contractor;
use App\Models\Buyer\Site;
use App\Repositories\Interfaces\Buyer\siteRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

/**
 * Class SiteController
 * @package App\Http\Controllers\Api\Buyer
 *
 * @SWG\Definition(
 *      definition="site_object",
 *      required={"name","address","city","state","country","phone"},
 *      @SWG\Property(property="contractor",type="integer"),
 *      @SWG\Property(property="nickname",type="string",maxLength=100),
 *      @SWG\Property(property="name",type="string",maxLength=100),
 *      @SWG\Property(property="address",type="string",maxLength=100),
 *      @SWG\Property(property="city",type="integer"),
 *      @SWG\Property(property="metro",type="integer"),
 *      @SWG\Property(property="state",type="integer"),
 *      @SWG\Property(property="country",type="integer"),
 *      @SWG\Property(property="zip",type="string",maxLength=10),
 *      @SWG\Property(property="phone",type="string",maxLength=15),
 *      @SWG\Property(property="contact",type="string",maxLength=100),
 *      @SWG\Property(property="special_instructions",type="string",maxLength=500)
 * )
 */
class SiteController extends Controller
{
	protected  $site_repository;

	public function __construct(siteRepositoryInterface $site_repository) {
		$this->site_repository=$site_repository;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
	 *
	 * @SWG\Get(
	 *      path="/api/sites",
	 *      summary="List the available sites",
	 *      produces={"application/json"},
	 *      tags={"sites"},
	 *      @SWG\Parameter(
	 *          in="query",
	 *          name="name",
	 *          description="filter sites by its name",
	 *          required=false,
	 *          type="string",
	 *     @SWG\Schema(type="string")
	 *      ),
	 *     @SWG\Parameter(
	 *          in="query",
	 *          name="nickname",
	 *          description="filter sites by its nickname",
	 *          required=false,
	 *          type="string",
	 *     @SWG\Schema(type="string")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="the list of available sites",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/site_object"))
	 *          )
	 *      )
	 * )
     */
    public function index(siteSearchRequest $request)
    {
    	$fields = $request->only(['name','nickname']);
    	if($request->input('contractor')){
    		$fields['contractor_id']=$request->input('contractor');
	    }
	    $data = $this->site_repository->paginateBy($fields,10);
	    return siteResource::collection($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *      path="/api/sites",
     *      summary="stores a new site for the current contractor",
     *      produces={"application/json"},
     *      tags={"sites"},
     *      @SWG\Response(
     *          response=200,
     *          description="stores a new site and return it's instance",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(  property="data",
     *                              type="object",
     *                              ref="#/definitions/site_object")
     *          )
     *      )
     * )
     */
    public function store(SiteCDRequest $request)
    {

		$data=$request->only(['nickname','name','address','phone','contact','zip','contractor']);

    	$data['city_id']=$request->input('city');
	    $data['metro_id']=$request->input('metro');
	    $data['state_id']=$request->input('state');
	    $data['country_id']=$request->input('country');
	    $data['contractor_id']=$request->input('contractor');

	    if(!$request->get('contractor')){
		    $data['contractor_id']=Auth::user()->contractor->id;
	    }

	    $data['details']=[
	    	'special_instructions'=>$request->input('special_instructions')
	    ];
		$site=$this->site_repository->create($data);
    	return response()->json($site);
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Buyer\Site $site
	 *
	 * @return siteResource
	 *
	 * @SWG\Get(
	 *      path="/api/sites/{site}",
	 *      summary="returns the site identified by {site}",
	 *      produces={"application/json"},
	 *      tags={"sites"},
	 *     @SWG\Parameter(
	 *          in="path",
	 *          name="site",
	 *          type="integer",
	 *          required=true,
	 *          description="the id of the site that's going to be returned"
	 *     ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="returns the identified site",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(  property="data",
	 *                              type="object",
	 *                              ref="#/definitions/site_object")
	 *          )
	 *      )
	 * )
	 */
    public function show(Site $site)
    {
        return siteResource::make($site);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Buyer\Site  $site
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *      path="/api/sites/{site}",
     *      summary="updates the site identified by {site}",
     *      produces={"application/json"},
     *      tags={"sites"},
     *     @SWG\Parameter(
     *          in="path",
     *          name="site",
     *          type="integer",
     *          required=true,
     *          description="the id of the site that's going to be updated"
     *     ),
     *      @SWG\Response(
     *          response=200,
     *          description="updates the site identified by the given id",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(  property="data",
     *                              type="object",
     *                              ref="#/definitions/site_object")
     *          )
     *      )
     * )
     *
     */
    public function update(SiteCDRequest $request, Site $site)
    {
    	$contractor = Auth::user()->contractors->first();
		if (!$contractor || $contractor->id != $site->contractor_id){
			return response()->json(['message'=>__('User not allowed to update this site')],400);
		}

	    $data=$request->only(['nickname','name','address','phone','contact','zip']);
	    $data['city_id']=$request->input('city');
	    $data['metro_id']=$request->input('metro');
	    $data['state_id']=$request->input('state');
	    $data['country_id']=$request->input('country');
	    $data['details']=[
		    'special_instructions'=>$request->input('special_instructions')
	    ];
	    try {
		    $site = $this->site_repository->updateBy( $data, $site->id );
	    }catch (Exception $exception){
	    	return response()->json(['message'=>$exception->getMessage()],400);
	    }
	    return response()->json($site);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Buyer\Site  $site
     * @return \Illuminate\Http\Response
     *
     * @SWG\Delete(
     *      path="/api/sites/{site}",
     *      summary="deletes the specified site",
     *      produces={"application/json"},
     *      tags={"sites"},
     *     @SWG\Parameter(
     *          in="path",
     *          name="site",
     *          type="integer",
     *          required=true,
     *          description="the id of the site that's going to be deleted"
     *     ),
     *      @SWG\Response(
     *          response=200,
     *          description="the specified site was deleted",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(  property="message",
     *                              type="string",
     *                              description="confirmation message",
     *              )
     *          )
     *      )
     * )
     */
    public function destroy(Site $site)
    {

    	try{
	        $this->site_repository->delete($site->id);
	        return response()->json(['message'=>__('site deleted')]);
    	}catch (Exception $exception){
    		return response()->json(['message'=>__('error deleting the site')],500);
	    }
    }

	/**
	 * @param Request $request
	 * @param Contractor $contractor
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 *
	 * @SWG\Get(
	 *      path="/api/sites/resume/{contractor}",
	 *      summary="list the sites that belongs to the given contractor; if no contractor is given, the returned sites are those that belogns to the authenticated user ",
	 *      produces={"application/json"},
	 *      tags={"sites"},
	 *     @SWG\Parameter(
	 *          in="path",
	 *          name="contractor",
	 *          type="integer",
	 *          required=false,
	 *          description="the id of the contractor whose sites are going to be listed"
	 *     ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="the list of the sites of the given contractor or authenticated user",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(  property="data",
	 *                              type="array",
	 *                              items=@SWG\Items(
	 *                                  type="object",
	 *                                  @SWG\Property(property="name",type="string"),
	 *                                  @SWG\Property(property="city",type="string"),
	 *                                  @SWG\Property(property="state",type="string")
	 *                  )
	 *              )
	 *          )
	 *      )
	 * )
	 *
	 */
    public function resume(Request $request, Contractor $contractor){
		if (!$contractor->exists){
			$contractor=Auth::user()->contractor;
		}
		if (!$contractor){
			return response()->json(['message'=>__('not a contractor')]);
		}
		return resumeSiteResource::collection($this->site_repository->findAllBy(
			$contractor->id,
			'contractor_id',
			['name','city_id','state_id'],
			['city','state']
			)
		);

    }

}
