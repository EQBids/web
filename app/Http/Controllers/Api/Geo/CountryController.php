<?php

namespace App\Http\Controllers\Api\Geo;

use App\Http\Requests\Geo\Country\CountryUpdateRequest;
use App\Http\Resources\Geo\CountryResource;
use App\Models\Geo\Country;
use App\Repositories\Interfaces\Geo\CountryRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

/**
 * Class CountriesController
 * @package App\Http\Controllers\Api\Geo
 *
 * @SWG\Definition(
 *      definition="country_object",
 *      @SWG\Property(property="id",type="integer"),
 *      @SWG\Property(property="name",type="string",maxLength=100)
 * )
 */
class CountryController extends Controller
{

	private $country_repository;

	public function __construct(CountryRepositoryInterface $country_repository) {
		$this->country_repository=$country_repository;

	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *      path="/api/countries",
     *      summary="List the available countries",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in="query",
     *          name="name",
     *          description="filter countries by its name",
     *          required=false,
     *          type="string",
     *     @SWG\Schema(type="string")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="the list of available countries"
     *      )
     * )
     *
     */
    public function index(Request $resquest)
    {
      $data = $this->country_repository->paginateByName($resquest->input('name'),10);
      
    	return CountryResource::collection($data);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Geo\Country $countries
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *      path="/api/countries/{country}",
     *      summary="Show the specified country",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in="path",
     *          name="country",
     *          description="id of the displayed country",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="the information of the specified country"
     *      )
     * )
     */
    public function show(Country $country)
    {
    	return new CountryResource($country);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Geo\Country  $countries
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *      path="/api/countries/{country}",
     *      summary="update the state of the specified country",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in="path",
     *          name="country",
     *          description="id of the country that's being updated",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="return the updated instance of the country"
     *      )
     * )
     */
    public function update(CountryUpdateRequest $request, Country $country)
    {
    	$country->status=$request->input('status');
		  $country->save();
		  return new CountryResource($country);
    }

}
