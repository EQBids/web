<?php

namespace App\Http\Controllers\Api\Geo;

use App\Http\Resources\Geo\cityResource;
use App\Models\Geo\City;
use App\Repositories\Interfaces\Geo\cityRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class CityController
 * @package App\Http\Controllers\Api\Geo
 *
 * @SWG\Definition(
 *      definition="city_object",
 *      @SWG\Property(property="id",type="integer"),
 *      @SWG\Property(property="name",type="string",maxLength=100)
 * )
 */
class CityController extends Controller
{
	protected $city_repository;
	public function __construct(cityRepositoryInterface $city_repository) {
		$this->city_repository=$city_repository;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 *
	 * @SWG\Get(
	 *      path="/api/cities",
	 *      summary="List the available cities",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          in="query",
	 *          name="name",
	 *          description="filter cities by its name",
	 *          required=false,
	 *          type="string",
	 *     @SWG\Schema(type="string")
	 *      ),
	 *     @SWG\Parameter(
	 *          in="query",
	 *          name="country",
	 *          description="the id of a country, only cities that belong to this country will be returned",
	 *          required=false,
	 *          type="integer",
	 *          @SWG\Schema(type="integer")
	 *      ),
	 *     @SWG\Parameter(
	 *          in="query",
	 *          name="state",
	 *          description="the id of a state, only cities that belong to this state will be returned",
	 *          required=false,
	 *          type="integer",
	 *          @SWG\Schema(type="integer")
	 *      ),
	 *     @SWG\Parameter(
	 *          in="query",
	 *          name="metro",
	 *          description="the id of a metro/area, only cities that belong to this metro will be returned",
	 *          required=false,
	 *          type="integer",
	 *          @SWG\Schema(type="integer")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="the list of available cities"
	 *      )
	 * )
	 *
	 */
	public function index(Request $resquest)
	{
		$data = $this->city_repository->paginateByName($resquest->input('name'),50,['*']
			,$resquest->input('country'),
			$resquest->input('state'),
			$resquest->input('metro')
		);
		return cityResource::collection($data);
	}



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Geo\City  $city
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *      path="/api/cities/{city}",
     *      summary="display the specified city",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in="path",
     *          name="city",
     *          description="id of the desired city",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="return the information of the specified city"
     *      )
     * )
     */
    public function show(City $city)
    {
        return cityResource::make($city);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Geo\City  $city
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *      path="/api/cities/{city}",
     *      summary="updates the information of specified city",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in="path",
     *          name="city",
     *          description="id of the city being updated",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Parameter(
     *          in="body",
     *          name="status",
     *          description="the new status for the specified city",
     *          required=true,
     *          type="ineteger",
     *     @SWG\Schema(type="integer")
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="return the updated information of the specified city"
     *      )
     * )
     */
    public function update(Request $request, City $city)
    {
        //
    }

}
