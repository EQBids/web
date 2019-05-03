<?php

namespace App\Http\Controllers\Api\Buyer;

use App\Http\Requests\Buyer\Billing\billingCURequest;
use App\Http\Requests\Buyer\Billing\billingDeleteRequest;
use App\Http\Requests\Buyer\Billing\billingIndexRequest;
use App\Http\Requests\Buyer\Billing\billingUpdateRequest;
use App\Http\Resources\Buyer\billingResource;
use App\Models\Billing;
use App\Repositories\Interfaces\Buyer\billingRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class BillingController
 * @package App\Http\Controllers\Api\Buyer
 *
 * @SWG\Definition(
 *      definition="billing_object",
 *      required={"name","address","city","state","country","phone"},
 *      @SWG\Property(property="contractor",type="integer"),
 *      @SWG\Property(property="name",type="string",maxLength=100),
 *      @SWG\Property(property="address",type="string",maxLength=200),
 *      @SWG\Property(property="notes",type="string",maxLength=200)
 * )
 */
class BillingController extends Controller
{


	protected $billing_repository;
	public function __construct(billingRepositoryInterface $billing_repository) {
		$this->billing_repository=$billing_repository;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
	 *
	 * @SWG\Get(
	 *      path="/api/contractors/{contractor}/billings",
	 *      summary="List the billing address for this contractor",
	 *      produces={"application/json"},
	 *     tags={"order"},
	 *      @SWG\Parameter(
	 *          in="path",
	 *          name="contractor",
	 *          description="id of the contractor that owns the billings",
	 *          required=true,
	 *          type="integer",
	 *     @SWG\Schema(type="integer")
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="the list of billing address owned by the specified contractor",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/billing_object"))
	 *          )
	 *      ),
	 *     deprecated=true
	 *
	 * )
     */
    public function index(billingIndexRequest $request)
    {
	    $data = $this->billing_repository->paginateBy(10,$request->route('contractor'),'contractor_id');
	    return billingResource::collection($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *      path="/api/contractors/{contractor}/billings",
     *      summary="stores a new billing address for the current contractor",
     *      produces={"application/json"},
     *     tags={"order"},
     *     @SWG\Parameter(
     *          in="path",
     *          name="contractor",
     *          description="id of the contractor that owns the billings",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *     @SWG\Parameter(
     *          in="body",
     *          name="body",
     *          description="billing address information",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/billing_object")
	 *     ),
     *      @SWG\Response(
     *          response=200,
     *          description="stores a new billing address and return it's instance",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(  property="data",
     *                              type="object",
     *                              ref="#/definitions/billing_object")
     *          )
     *      ),
     *     deprecated=true
     * )
     */
    public function store(billingCURequest $request)
    {
        $bill = $request->only(['name']);
        $bill['contractor_id']=$request->route('contractor');
        $bill['description']=[
        	'address'=>$request->get('address'),
	        'notes'=>$request->get('notes')
        ];
        $bill = $this->billing_repository->create($bill);
        return response()->json(['data'=>$bill]);
    }

	/**
	 * @param billingIndexRequest $request
	 * @param $contractor
	 * @param Billing $billing
	 *
	 * @return \Illuminate\Http\JsonResponse
	 *
	 * @SWG\Get(
	 *      path="/api/contractors/{contractor}/billings/{billing}",
	 *      summary="returns the billing address identified by {billing}",
	 *      produces={"application/json"},
	 *     tags={"order"},
	 *     @SWG\Parameter(
	 *          in="path",
	 *          name="contractor",
	 *          description="id of the contractor that owns the billings",
	 *          required=true,
	 *          type="integer",
	 *     @SWG\Schema(type="integer")
	 *      ),
	 *      @SWG\Parameter(
	 *          in="path",
	 *          name="billing",
	 *          description="id of the billing address the is being showed",
	 *          required=true,
	 *          type="integer",
	 *     @SWG\Schema(type="integer")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="returns the identified billing address",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(  property="data",
	 *                              type="object",
	 *                              ref="#/definitions/billing_object")
	 *          )
	 *      ),
	 *     deprecated=true
	 * )
	 */
    public function show(billingIndexRequest $request,$contractor,Billing $billing)
    {
		return response()->json(['data'=>billingResource::make($billing)]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *      path="/api/contractors/{contractor}/billings/{billing}",
     *      summary="updates the billing address identified by {billing}",
     *      produces={"application/json"},
     *     tags={"order"},
     *     @SWG\Parameter(
     *          in="path",
     *          name="contractor",
     *          description="id of the contractor that owns the billings",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Parameter(
     *          in="path",
     *          name="billing",
     *          description="id of the billing address the is being updated",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *     @SWG\Parameter(
     *          in="body",
     *          name="body",
     *          description="billing address information",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/billing_object")
     *     ),
     *      @SWG\Response(
     *          response=200,
     *          description="returns the updated billing address",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(  property="data",
     *                              type="object",
     *                              ref="#/definitions/billing_object")
     *          )
     *      ),
     *     deprecated=true
     * )
     */
    public function update(billingUpdateRequest $request,$contractor, Billing $billing)
    {
	    $bill = $request->only(['name']);
	    $bill['contractor_id']=$contractor;
	    $bill['description']=[
		    'address'=>$request->get('address'),
		    'notes'=>$request->get('notes')
	    ];

	    try {
		    $bill = $this->billing_repository->updateBy($bill,$billing->id);
	    }catch (Exception $exception){
		    return response()->json(['message'=>$exception->getMessage()],400);
	    }
	    return response()->json($bill);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @SWG\Delete(
     *      path="/api/contractors/{contractor}/billings/{billing}",
     *      summary="deletes the specified billing address",
     *      produces={"application/json"},
     *      tags={"order"},
     *     @SWG\Parameter(
     *          in="path",
     *          name="contractor",
     *          description="id of the contractor that owns the billings",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Parameter(
     *          in="path",
     *          name="billing",
     *          description="id of the billing address the is being updated",
     *          required=true,
     *          type="integer",
     *     @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="the specified billing address was deleted",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(  property="data",
     *                              type="object",
     *                              ref="#/definitions/billing_object")
     *          )
     *      ),
     *     deprecated=true
     * )
     */
    public function destroy(billingDeleteRequest $request,$contractor,Billing $billing)
    {
	    try{
		    $this->billing_repository->delete($billing->id);
		    return response()->json(['message'=>__('billing address deleted')]);
	    }catch (Exception $exception){
		    return response()->json(['message'=>__('error deleting the billing address')],500);
	    }
    }


}
