<?php

namespace App\Http\Controllers\Api\Geo;

use App\Http\Controllers\Controller;
use App\Http\Resources\Geo\StateResource;
use App\Models\Geo\State;
use App\Repositories\Eloquent\Geo\stateRepository;
use App\Repositories\Interfaces\Geo\stateRepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class StateController
 * @package App\Http\Controllers\Api\Geo
 *
 * @SWG\Definition(
 *      definition="state_object",
 *      @SWG\Property(property="id",type="integer"),
 *      @SWG\Property(property="name",type="string",maxLength=100)
 * )
 */
class StateController extends Controller
{

	private $state_repository;

	public function __construct(stateRepositoryInterface $state_repository) {
		$this->state_repository=$state_repository;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 *
	 * @SWG\Get(
	 *      path="/api/states",
	 *      summary="List the available states",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          in="query",
	 *          name="name",
	 *          description="filter states by its name",
	 *          required=false,
	 *          type="string",
	 *     @SWG\Schema(type="string")
	 *      ),
	 *      @SWG\Parameter(
	 *          in="query",
	 *          name="country",
	 *          description="filter states by its country",
	 *          required=false,
	 *          type="integer",
	 *     @SWG\Schema(type="integer")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="the list of available states"
	 *      )
	 * )
	 *
	 */
	public function index(Request $resquest)
	{
		$data = $this->state_repository->paginateByName($resquest->input('name'),10,['*'],$resquest->input('country'));
		return StateResource::collection($data);
	}


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Geo\State  $state
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *      path="/api/states/{state}",
     *      summary="Display the information of the specified state",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in="path",
     *          name="state",
     *          description="id of the state to be displayed",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="information of the displayed state"
     *      )
     * )
     */
    public function show(State $state)
    {
        return response()->json(StateResource::make($state));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Geo\State  $state
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *      path="/api/states/{state}",
     *      summary="updates the state of the specified state",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in="path",
     *          name="state",
     *          description="id of the state to be displayed",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *     @SWG\Parameter(
     *          in="body",
     *          name="status",
     *          description="new status of the state",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="information of the updated state"
     *      )
     * )
     */
    public function update(Request $request, State $state)
    {
	    $state->status=$request->input('status');
	    $state->save();
	    return response()->json(['data'=>StateResource::make($state)]);
    }

}
