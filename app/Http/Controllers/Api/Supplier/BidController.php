<?php

namespace App\Http\Controllers\Api\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\Bids\cancelBidRequest;
use App\Http\Requests\Supplier\Bids\createBidRequest;
use App\Http\Requests\Supplier\Bids\showBidRequest;
use App\Http\Requests\Supplier\Bids\updateBidRequest;
use App\Http\Resources\Supplier\bidResource;
use App\Models\Buyer\Order;
use App\Models\Supplier\Bid;
use App\Repositories\Eloquent\Supplier\bidRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class BidController
 * @package App\Http\Controllers\Api\Supplier
 *
 *
 *
 * @SWG\Definition(definition="bid_response_object",
 *     @SWG\Property(property="id",type="integer",description="Bid id"),
 *     @SWG\Property(property="supplier",type="objet",ref="#/definitions/suppliers_in_range_object"),
 *     @SWG\Property(property="amount",type="double",description="request amount for the equipment"),
 * )
 *
 * @SWG\Definition(definition="biding_item_request_object",
 *     @SWG\Property(property="id",type="integer",description="order-item id"),
 *     @SWG\Property(property="price",type="number",description=""),
 *     @SWG\Property(property="pick",type="number"),
 *     @SWG\Property(property="delivery",type="number"),
 *     @SWG\Property(property="insurance",type="number",description="insurance value"),
 *     @SWG\Property(property="from",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *     @SWG\Property(property="to",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *     @SWG\Property(property="notes",type="string"),
 * )
 *
 *
 * @SWG\Definition(definition="biding_item_detailed_response_object",
 *      @SWG\Property(property="oid",type="integer",description="Order item id"),
 *      @SWG\Property(property="id",type="integer",description="Equipments id"),
 *      @SWG\Property(property="name",type="string",maxLength=100,description="Equipments name"),
 *      @SWG\Property(property="image",type="string",maxLength=100),
 *      @SWG\Property(property="from",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *      @SWG\Property(property="to",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *      @SWG\Property(property="qty",type="integer",description="Desired quantity of this equipment"),
 *      @SWG\Property(property="price",type="number",description=""),
 *      @SWG\Property(property="pick",type="number"),
 *      @SWG\Property(property="delivery",type="number"),
 *      @SWG\Property(property="insurance",type="number",description="insurance value"),
 *      @SWG\Property(property="deliv_date",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *      @SWG\Property(property="return_date",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *      @SWG\Property(property="notes",type="string"),
 *     )
 *
 * @SWG\Definition(definition="bid_detailed_response_object",
 *     @SWG\Property(property="id",type="integer",description="Bid id"),
 *     @SWG\Property(property="supplier",type="objet",ref="#/definitions/suppliers_in_range_object"),
 *     @SWG\Property(property="amount",type="double",description="request amount for the equipment"),
 *     @SWG\Property(property="items",
 *              type="array",
 *              items=@SWG\Items(
 *                  ref="#/definitions/biding_item_detailed_response_object",
 *              )
 *      ),
 *     @SWG\Property(property="order",type="objet",ref="#/definitions/order_response_object"),
 *
 * )
 */
class BidController extends Controller
{
	protected $bid_repository;
	public function __construct(bidRepository $bid_repository) {
		$this->bid_repository=$bid_repository;

	}


	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/api/bids",
     *     summary="list bids current user can access, sorted by creation date",
     *     produces={"application/json"},
     *      tags={"order"},
     *     @SWG\Parameter(
     *          in="header",
     *          name="Authorization",
     *          description ="the authentication token generated by the app",
     *          required=true,
     *          type="string"
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="List of bids user can access",
     *           @SWG\Property(property="data",type="object",ref="#/definitions/bid_response_object")
     *      ),
     *     @SWG\Response(
     *          response=400,
     *          description="something went wrong listing the bids",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="error_message")
     *          )
     *      ),
     * )
     */
    public function index()
    {
	    $bids = $this->bid_repository->accesibleBids(Auth::user());
	    return bidResource::collection($bids);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *     path="/api/bids",
     *     summary="stores a new bid for the specified order and the supplier of the current user",
     *     produces={"application/json"},
     *      tags={"order"},
     *     @SWG\Parameter(
     *          in="header",
     *          name="Authorization",
     *          description ="the authentication token generated by the app",
     *          required=true,
     *          type="string"
     *      ),
     *     @SWG\Parameter(
     *          in="body",
     *          name="order_id",
     *          required=true,
     *          description="id of the order that's going to receive the bid",
                type="integer",
     *          schema="integer"
     *      ),
     *     @SWG\Parameter(
     *          in="body",
     *          name="equipments",
     *          required=true,
     *          description="array of bid and order-item ids corresponding to the accepted bids per item",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(
     *                  ref="#/definitions/biding_item_request_object",
     *              )
     *          )
     *      ),
     *     @SWG\Parameter(
     *          in="body",
     *          name="notes",
     *          required=false,
     *          description="bid notes",
     *          type="string",
     *          schema="string"
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="bid created, returns the new bid instance",
     *           @SWG\Property(property="data",type="object",ref="#/definitions/bid_response_object")
     *      ),
     *     @SWG\Response(
     *          response=400,
     *          description="something went wrong creating the bid",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="error_message")
     *          )
     *      ),
     * )
     */
    public function store(createBidRequest $request)
    {
	    $user = Auth::user();
	    $order = Order::find($request->get('order_id'));
	    $supplier=$order->suppliers()->whereIn('id',$user->suppliers->pluck('id'))->first();
	    $data = $request->only(['order_id','equipments']);
	    $data['supplier_id']=$supplier->id;
	    $data['details']=[
		    'notes'=>$request->get('notes')
	    ];
	    try{
		    $bid = $this->bid_repository->create($data);
		    return bidResource::make($bid);
	    }catch(\Error $ex){
		    return response()->json(['message'=>$ex->getMessage()],400);
	    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier\Bid  $bid
     * @return \Illuminate\Http\Response
     *
     *
     * @SWG\Get(
     *     path="/api/bids/{id}",
     *     summary="display the specified bid and all it's details",
     *     produces={"application/json"},
     *      tags={"order"},
     *     @SWG\Parameter(
     *          in="header",
     *          name="Authorization",
     *          description ="the authentication token generated by the app",
     *          required=true,
     *          type="string"
     *      ),
     *     @SWG\Parameter(
     *          in="path",
     *          name="id",
     *          description ="id of the bid that's going to be displayed",
     *          required=true,
     *          type="integer"
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="detailed bid information",
     *           @SWG\Property(property="data",type="object",ref="#/definitions/bid_detailed_response_object")
     *      ),
     *     @SWG\Response(
     *          response=400,
     *          description="something went wrong listing the bids",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="error_message")
     *          )
     *      ),
     * )
     */
    public function show(showBidRequest $request, Bid $bid)
    {
    	$bid->load(['items','order']);
        return bidResource::make($bid);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier\Bid  $bid
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *     path="/api/bids",
     *     summary="updates the specified bid is possible",
     *     produces={"application/json"},
     *      tags={"order"},
     *     @SWG\Parameter(
     *          in="header",
     *          name="Authorization",
     *          description ="the authentication token generated by the app",
     *          required=true,
     *          type="string"
     *      ),
     *     @SWG\Parameter(
     *          in="path",
     *          name="id",
     *          description ="id of the bid that's going to be displayed",
     *          required=true,
     *          type="integer"
     *      ),
     *     @SWG\Parameter(
     *          in="body",
     *          name="equipments",
     *          required=true,
     *          description="array of bid and order-item ids corresponding to the accepted bids per item",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(
     *                  ref="#/definitions/biding_item_request_object",
     *              )
     *          )
     *      ),
     *     @SWG\Parameter(
     *          in="body",
     *          name="notes",
     *          required=false,
     *          description="bid notes",
     *          type="string",
     *          schema="string"
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="bid updated, returns the detailed bid information",
     *           @SWG\Property(property="data",type="object",ref="#/definitions/bid_detailed_response_object")
     *      ),
     *     @SWG\Response(
     *          response=400,
     *          description="something went wrong updating the bid",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="error_message")
     *          )
     *      ),
     * )
     */
	public function update(updateBidRequest $request, Bid $bid)
	{
		$data = $request->only(['equipments']);
		$data['details']=$bid->details;
		$data['details']['notes']=$request->get('notes');
		try{
			$bid = $this->bid_repository->updateBy($data,$bid->id);
			return bidResource::make($bid);
		}catch(\Error $ex){
			return response()->json(['message'=>$ex->getMessage()],400);
		}
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier\Bid  $bid
     * @return \Illuminate\Http\Response
     *
     * @SWG\Delete(
     *     path="/api/bids/{id}",
     *     summary="cancels the specified bid",
     *     produces={"application/json"},
     *      tags={"order"},
     *     @SWG\Parameter(
     *          in="header",
     *          name="Authorization",
     *          description ="the authentication token generated by the app",
     *          required=true,
     *          type="string"
     *      ),
     *     @SWG\Parameter(
     *          in="path",
     *          name="id",
     *          description ="id of the bid that's going to be displayed",
     *          required=true,
     *          type="integer"
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="bid cancelled",
     *           @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="success_message")
     *          )
     *      ),
     *     @SWG\Response(
     *          response=400,
     *          description="something went canceling the bid",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="error_message")
     *          )
     *      ),
     * )
     */
    public function destroy(cancelBidRequest $request,Bid $bid)
    {
	    try{
		    $this->bid_repository->delete($bid->id);
		    return response()->json(['message'=>'Bid cancelled'],200);
	    }catch (\Error $error){
		    return response()->json([
		    	'message'=>"You are not authorized to cancel this bid"
		    ],400);
	    }
    }
}
