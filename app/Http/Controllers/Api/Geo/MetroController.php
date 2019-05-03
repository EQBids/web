<?php

namespace App\Http\Controllers\Api\Geo;

use App\Http\Resources\Geo\metroResource;
use App\Models\Geo\Metro;
use App\Repositories\Interfaces\Geo\metroRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MetroController extends Controller
{

	protected $metro_repository;
	public function __construct(metroRepositoryInterface $metro_repository) {
		$this->metro_repository=$metro_repository;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 *
	 * @SWG\Get(
	 *      path="/api/metros",
	 *      summary="List the available metro/areas",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          in="query",
	 *          name="name",
	 *          description="filter areas by its name",
	 *          required=false,
	 *          type="string",
	 *     @SWG\Schema(type="string")
	 *      ),
	 *     @SWG\Parameter(
	 *          in="query",
	 *          name="country",
	 *          description="filter states by its country",
	 *          required=false,
	 *          type="integer",
	 *     @SWG\Schema(type="integer")
	 *      ),
	 *     @SWG\Parameter(
	 *          in="query",
	 *          name="state",
	 *          description="filter states by its state and country",
	 *          required=false,
	 *          type="integer",
	 *     @SWG\Schema(type="integer")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="the list of available areas"
	 *      )
	 * )
	 *
	 */
    public function index(Request $request)
    {
        $data = $this->metro_repository->paginateByName(
        	$request->input('name'),10,['*'],
	        $request->input('country'),$request->input('state'));
		return metroResource::collection($data);
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Geo\Metro  $metro
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *      path="/api/metros/{metro}",
     *      summary="display the specified metro/area",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in="path",
     *          name="metro",
     *          description="id of the desired metro",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="returns the information of the desired metro/area"
     *      )
     * )
     */
    public function show(Metro $metro)
    {
        return response()->json(metroResource::make($metro));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Geo\Metro  $metro
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *      path="/api/metros/{metro}",
     *      summary="updates the specified metro/area",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in="path",
     *          name="metro",
     *          description="id of the desired metro",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *     @SWG\Parameter(
     *          in="body",
     *          name="status",
     *          description="new status for the specified metro",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="returns the updated information of the desired metro/area"
     *      )
     * )
     */
    public function update(Request $request, Metro $metro)
    {
	    $metro->status=$request->input('status');
	    $metro->save();
	    return metroResource::make($metro);
    }


}
