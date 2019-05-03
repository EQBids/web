<?php

namespace App\Http\Controllers\Api\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Buyer\Order\approveRequest;
use App\Http\Requests\Buyer\Order\cancelRequest;
use App\Http\Requests\Buyer\Order\CloseOrderRequest;
use App\Http\Requests\Buyer\Order\createOrderRequest;
use App\Http\Requests\Buyer\Order\detailsRequest;
use App\Http\Requests\Buyer\Order\edit\editingDetailsRequest;
use App\Http\Requests\Buyer\Order\edit\siteRequest;
use App\Http\Requests\Buyer\Order\edit\suppliersRequest;
use App\Http\Requests\Buyer\Order\editingRequest;
use App\Http\Requests\Buyer\Order\editRequest;
use App\Http\Requests\Buyer\Order\showRequest;
use App\Http\Requests\Buyer\Order\siteOnlyRequest;
use App\Http\Requests\Buyer\Order\locationRequest;
use App\Http\Requests\Buyer\Order\supplierRequest;
use App\Http\Requests\Buyer\Order\updateOrderBidsRequest;
use App\Http\Requests\Buyer\Order\viewOrderBidsRequest;
use App\Http\Resources\Buyer\editingOrderItemsResource;
use App\Http\Resources\Buyer\orderItemResource;
use App\Http\Resources\Buyer\orderResource;
use App\Http\Resources\Buyer\orderSupplierResource;
use App\Http\Resources\Buyer\siteResource;
use App\Http\Resources\Product\CartEquipmentResource;
use App\Http\Resources\Supplier\bidResource;
use App\Http\Resources\Supplier\SupplierResource;
use App\Models\Buyer\Order;
use App\Models\Supplier\Supplier;
use App\Repositories\Eloquent\Buyer\cartRepository;
use App\Repositories\Eloquent\Buyer\orderRepository;
use App\Repositories\Eloquent\Buyer\siteRepository;
use App\Repositories\Eloquent\SettingsRepository;
use App\Repositories\Eloquent\Supplier\supplierRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class OrderController
 * @package App\Http\Controllers\Api\Buyer
 *
 *
 * @SWG\Definition(
 *      definition="suppliers_in_range_object",
 *      @SWG\Property(property="id",type="integer"),
 *      @SWG\Property(property="name",type="string",maxLength=100),
 *      @SWG\Property(property="address",type="string",maxLength=200),
 *      @SWG\Property(property="country",type="objet",ref="#/definitions/country_object"),
 *      @SWG\Property(property="state",type="objet",ref="#/definitions/state_object"),
 *      @SWG\Property(property="city",type="objet",ref="#/definitions/city_object"),
 *      @SWG\Property(property="distance",type="integer",description="Distance in kilometers (optional)")
 * )
 * @SWG\Definition(
 *      definition="cart_equipment_response_object",
 *      @SWG\Property(property="id",type="integer"),
 *      @SWG\Property(property="name",type="string",maxLength=100),
 *      @SWG\Property(property="image",type="string",maxLength=100),
 *      @SWG\Property(property="from",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *      @SWG\Property(property="to",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *      @SWG\Property(property="qty",type="integer",description="Desired quantity of this equipment"),
 *      @SWG\Property(property="order_notes",type="string",description="Notes added by the contractor to this equipment in the order"),
 *      @SWG\Property(property="allow_attachments",type="boolean",description="Suppliers can add attachments to this item")
 * )
 *
 * @SWG\Definition(
 *      definition="cart_equipment_request_object",
 *      @SWG\Property(property="id",type="integer"),
 *      @SWG\Property(property="from",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *      @SWG\Property(property="to",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *      @SWG\Property(property="qty",type="integer",description="Desired quantity of this equipment"),
 *      @SWG\Property(property="notes",type="string",description="Order equipment notes")
 * )
 *
 * @SWG\Definition(
		definition="order_response_object",
 *     @SWG\Property(property="id",type="integer"),
 *     @SWG\Property(property="created_at",type="string"),
 *     @SWG\Property(property="creator",type="object",ref="#/definitions/user_object"),
 *     @SWG\Property(property="destination",type="object",ref="#/definitions/site_object"),
 *     @SWG\Property(property="status",type="string"),
 *     @SWG\Property(property="notes",type="string"),
 *     @SWG\Property(property="edit",type="boolean",description="true if the user can edit this order"),
 *     @SWG\Property(property="cancel",type="boolean",description="true if the user can cancel this order"),
 *     @SWG\Property(property="approve",type="boolean",description="true if the user can approve this order"),
 *     @SWG\Property(property="bids",type="boolean",description="true if the user see the bids of this order"),
 *     @SWG\Property(property="has_bids",type="boolean",description="true if the order has bids"),
 *     @SWG\Property(property="closable",type="boolean",description="true if the order can be closed"),
 *)
 *
 * @SWG\Definition(definition="order_suppliers_response_object",
 *     @SWG\Property(property="status",type="string"),
 *     @SWG\Property(property="id",type="integer"),
 *     @SWG\Property(property="name",type="string",maxLength=100),
 * )
 *
 * @SWG\Definition(definition="bid_item_response_object",
 *     @SWG\Property(property="id",type="integer",description="Bid id"),
 *     @SWG\Property(property="supplier",type="objet",ref="#/definitions/suppliers_in_range_object"),
 *     @SWG\Property(property="amount",type="number",description="request amount for the equipment"),
 *     @SWG\Property(property="status",type="string",description="Order-item bid status"),
 *     @SWG\Property(property="price",type="number",description=""),
 *     @SWG\Property(property="pickup_fee",type="number"),
 *     @SWG\Property(property="delivery_fee",type="number"),
 *     @SWG\Property(property="insurance",type="number",description="insurance value"),
 *     @SWG\Property(property="deliv_date",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *     @SWG\Property(property="return_date",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *     @SWG\Property(property="notes",type="string"),
 * )
 *
 * @SWG\Definition(definition="bid_item_request_object",
*      @SWG\Property(property="id",type="integer",description="Order id item (oid from order_item_response_object)"),
*      @SWG\Property(property="value",type="integer",description="id of the accepted bid for the item")
 *  )
 *
 * @SWG\Definition(definition="order_item_response_object",
 *      @SWG\Property(property="oid",type="integer",description="Order item id"),
 *      @SWG\Property(property="id",type="integer",description="Equipments id"),
 *      @SWG\Property(property="name",type="string",maxLength=100,description="Equipments name"),
 *      @SWG\Property(property="image",type="string",maxLength=100),
 *      @SWG\Property(property="from",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *      @SWG\Property(property="to",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *      @SWG\Property(property="qty",type="integer",description="Desired quantity of this equipment"),
 *      @SWG\Property(property="bids",type="array",items=@SWG\Schema(ref="#/definitions/bid_item_response_object")),
 *      @SWG\Property(property="order_notes",type="string",description="Order item notes"),
 *     )
 *
 * @SWG\Definition(definition="bid_order_item_response_object",
 *      @SWG\Property(property="oid",type="integer",description="Order item id"),
 *      @SWG\Property(property="id",type="integer",description="Equipments id"),
 *      @SWG\Property(property="name",type="string",maxLength=100,description="Equipments name"),
 *      @SWG\Property(property="image",type="string",maxLength=100),
 *      @SWG\Property(property="from",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *      @SWG\Property(property="to",type="string",format="YYYY-MM-DD",default="YYYY-MM-DD"),
 *      @SWG\Property(property="qty",type="integer",description="Desired quantity of this equipment"),
 *      @SWG\Property(property="bids",type="array",items=@SWG\Schema(ref="#/definitions/bid_item_response_object")),
 *      @SWG\Property(property="order_notes",type="string",description="Order item notes"),
 *      @SWG\Property(property="price",type="string",description="Price set by the supplier"),
 *      @SWG\Property(property="status",type="string",description="Per item bid status"),
 *      @SWG\Property(property="pickup_fee",type="integer",description="Per item, Pickup fee set by supplier"),
 *      @SWG\Property(property="delivery_fee",type="integer",description="Per item, Delivery fee set by supplier"),
 *      @SWG\Property(property="insurance",type="integer",description="Per item, Insurance (%) fee set by supplier"),
 *      @SWG\Property(property="deliv_date",type="string",description="Per item, Item delivery date set by supplier"),
 *      @SWG\Property(property="return_date",type="string",description="Per item, Item return date fee set by supplier"),
 *      @SWG\Property(property="notes",type="string",description="Per item, notes set by supplier"),
 *      @SWG\Property(property="allow_attachments",type="boolean",description="Per item, this item allows attachments"),
 *      @SWG\Property(property="attachments",type="array",items=@SWG\Schema(type="string"),description="Per item, attachments urls"),
 *      @SWG\Property(property="accepted_bid",type="boolean",description="Per item, this bid is accepeted"),
 *     )
 *
 */
class OrderController extends Controller
{

	protected $site_repository,$settings_repository,$supplier_repository,
		$cart_repository,$order_repository;
	public function __construct(siteRepository $site_repository,SettingsRepository $settings_repository,
		supplierRepository $supplier_repository,cartRepository $cart_repository,orderRepository $order_repository) {
		$this->site_repository=$site_repository;
		$this->supplier_repository=$supplier_repository;
		$this->settings_repository=$settings_repository;
		$this->cart_repository=$cart_repository;
		$this->order_repository=$order_repository;

	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
	 *
	 * @SWG\Get(
	 *     path="/api/orders",
	 *     summary="list orders current user can access, sorted by creation date",
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
	 *          description="List of orders user can access",
	 *           @SWG\Property(property="data",type="object",ref="#/definitions/order_response_object")
	 *      ),
	 *     @SWG\Response(
	 *          response=400,
	 *          description="something went wrong creating the order",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="error_message")
	 *          )
	 *      ),
	 * )
     */
    public function index()
    {
        $user = Auth::user();
        return orderResource::collection($this->order_repository->accesibleOrders($user)->paginate());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *     path="/api/orders",
     *     summary="creates a new order for the current user, this's the last step of the ordering process, users need to specify quantities and dates for the desired items",
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
     *          description="Order created",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="message")
     *          )
     *      ),
     *     @SWG\Response(
     *          response=400,
     *          description="something went wrong creating the order",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="error_message")
     *          )
     *      ),
     * )
     *
     */
	public function store(createOrderRequest $request)
	{
		$order = $this->order_repository->createFromCart(Auth::user()->cart);
		if(!$order){
			return response()->json(['message'=>'Something went wrong, try again'],400);
		}

		return response()->json(['message'=>'Your order has been placed and qualified suppliers have been advised. You will be notified of bids as they arrive.']);


	}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Buyer\Order  $order
     * @return \Illuminate\Http\Response
     *
     *
     * @SWG\Get(
     *     path="/api/orders/{id}",
     *     summary="return the details of the current order",
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
     *          description ="id of the order that's going to be displayed",
     *          required=true,
     *          type="integer"
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="details of the specified order",
     *           @SWG\Property(property="data",type="object",ref="#/definitions/order_response_object")
     *      ),
     *     @SWG\Response(
     *          response=400,
     *          description="something went wrong creating the order",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="error_message")
     *          )
     *      ),
     * )
     */
    public function show(showRequest $request, Order $order)
    {
        return orderResource::make($order);
    }


	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Buyer\Order  $order
	 * @return \Illuminate\Http\Response
	 *
	 *
	 * @SWG\Get(
	 *     path="/api/orders/{id}/equipments",
	 *     summary="return the details of the current order",
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
	 *          description ="id of the order that's going to be displayed",
	 *          required=true,
	 *          type="integer"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="equipments of the specified order",
	 *           @SWG\Property(property="data",type="array",items=@SWG\Schema(ref="#/definitions/cart_equipment_response_object"))
	 *      ),
	 *     @SWG\Response(
	 *          response=400,
	 *          description="something went wrong creating the order",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="error_message")
	 *          )
	 *      ),
	 * )
	 */
	public function equipments(showRequest $request, Order $order)
	{
		return orderItemResource::collection($order->items);
	}


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Buyer\Order  $order
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *     path="/api/orders/{id}",
     *     summary="finishes the updating process",
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
     *          description ="id of the order that's being updated",
     *          required=true,
     *          type="integer"
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="details of the updated order",
     *           @SWG\Property(property="data",type="object",ref="#/definitions/order_response_object")
     *      ),
     *     @SWG\Response(
     *          response=400,
     *          description="something went wrong updating the order",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="error_message")
     *          )
     *      ),
     * )
     */
    public function update(editRequest $request, Order $order)
    {
    	if(!$order->is_editing){
	        return response()->json(['message'=>__('Order isn\'t being edited') ],400);
        }
        try {
	        return orderResource::make($this->order_repository->finishEditing( $order ));
        }catch (\Exception $exep){
	        return response()->json(['message'=>__($exep->getMessage()) ],400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Buyer\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

	/**
	 * @return \Illuminate\Http\JsonResponse|static
	 *
	 * @SWG\Get(
	 *     path="/api/orders/process/site",
	 *     summary="return the picked site",
	 *     produces={"application/json"},
	 *     tags={"order"},
	 *     @SWG\Parameter(
	 *          in="header",
	 *          name="Authorization",
	 *          description ="the authentication token generated by the app",
	 *          required=true,
	 *          type="string"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="list of usable sites",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",ref="#/definitions/site_object")
	 *          )
	 *      )
	 * )
	 */
	public function location(){
		$user = Auth::user();
		$cart = Auth::user()->cart;
		$site=null;
		if(isset($cart->details['site_id'])){
			$site = $this->site_repository->findOneBy($cart->details['site_id']);
		}
		if(!$site){
			return response()->json(['data'=>null]);
		}
		return siteResource::make($site);
	}

	/**
	 * @param siteOnlyRequest $request
	 *
	 * @return OrderController|\Illuminate\Http\JsonResponse
	 *
	 * @SWG\Post(
	 *     path="/api/orders/process/site",
	 *     summary="stores the specified site as the destination where items will be delivered. this's the first step after filling the shopping cart",
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
	 *          name="site",
	 *          description="id of the site that's going to be used as destination",
	 *          required=true,
	 *          type="integer",
	 *          schema="integer"
	 *     ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="Site stored",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="message")
	 *          )
	 *      ),
	 *     @SWG\Response(
	 *          response=400,
	 *          description="the status of the shopping cart is invalid",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="error_message")
	 *          )
	 *      ),
	 * )
	 */
	public function location_store(siteOnlyRequest $request){

		$cart = Auth::user()->cart;

		$site = $this->site_repository->findOneBy($request->get('site'));
		if(!$site){
			return response()->json(['message'=>'invalid site.'],400);
		}
		$cart->setSite($site->id);
		$cart->save();
		return $this->location();
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 *
	 * @SWG\Get(
	 *     path="/api/orders/process/suppliers/available",
	 *     summary="return the list of suppliers that are in range to the selected site",
	 *     produces={"application/json"},
	 *     tags={"order"},
	 *     @SWG\Parameter(
	 *          in="header",
	 *          name="Authorization",
	 *          description ="the authentication token generated by the app",
	 *          required=true,
	 *          type="string"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="list of suppliers in range",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/suppliers_in_range_object"))
	 *          )
	 *      )
	 * )
	 *
	 */
	public function available_suppliers(Request $request){
		$cart = Auth::user()->cart;

		if ($cart->empty || !isset($cart->details['site_id'])){
			return response()->json(['message'=>'invalid site.'],400);
		}
		$site_id = $cart->details['site_id'];
		$site = $this->site_repository->findOneBy($site_id);
		$radius = $this->settings_repository->getValue('radius_in_km_from_site',100);

		$suppliers = $this->supplier_repository->suppliersInRange($site->lat,$site->lon,$radius,$site->country_id,$cart->items);

		return SupplierResource::collection($suppliers);

	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 *
	 * @SWG\get(
	 *     path="/api/orders/process/suppliers",
	 *     summary="return the list of suppliers that users selected in their orders",
	 *     produces={"application/json"},
	 *     tags={"order"},
	 *     @SWG\Parameter(
	 *          in="header",
	 *          name="Authorization",
	 *          description ="the authentication token generated by the app",
	 *          required=true,
	 *          type="string"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="list of selected suppliers in range",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/suppliers_in_range_object"))
	 *          )
	 *      )
	 * )
	 */
	public function suppliers(Request $request){
		$cart = Auth::user()->cart;

		if ($cart->empty){
			return response()->json(['message'=>'invalid access.'],400);
		}
		$suppliers=collect([]);
		if(isset($cart->details['suppliers'])){
			$old_suppliers=$cart->details['suppliers'];
			$site_id = $cart->details['site_id'];
			$site = $this->site_repository->findOneBy($site_id);
			$radius = $this->settings_repository->getValue('radius_in_km_from_site',100);
			$suppliers = Supplier::inRange($radius,$site->lat,$site->lon,$site->country_id)->whereIn('id',$old_suppliers)->get();
		}

		return SupplierResource::collection($suppliers);

	}


	/**
	 * @param supplierRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 *
	 * @SWG\post(
	 *     path="/api/orders/process/suppliers",
	 *     summary="store in the order the available suppliers that users desired",
	 *     produces={"application/json"},
	 *     tags={"order"},
	 *     @SWG\Parameter(
	 *          in="header",
	 *          name="Authorization",
	 *          description ="the authentication token generated by the app",
	 *          required=true,
	 *          type="string"
	 *      ),
	 *     @SWG\Parameter(
	 *          in="body",
	 *          name="suppliers",
	 *          required=true,
	 *          description="array ids of the suppliers that are going to be used in the order",
	 *          type="array",items=@SWG\Items(ref="integer"),
	 *          schema="array",
	 *          default="[0,1,2,3]"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="stores the desired suppliers and return list of selected suppliers",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/suppliers_in_range_object"))
	 *          )
	 *      )
	 * )
	 */
	public function suppliers_store(supplierRequest $request){
		$cart = Auth::user()->cart;

		if ($cart->empty){
			return response()->json(['message'=>'invalid access.'],400);
		}

		$cart->setSuppliers($request->get('suppliers'));
		$cart->save();
		return $this->suppliers($request);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 *
	 * @SWG\Get(
	 *     path="/api/orders/process/details",
	 *     summary="return the list of equipment in the orders, desired quantities and date of delivery and return for each equipment",
	 *     produces={"application/json"},
	 *     tags={"order"},
	 *     @SWG\Parameter(
	 *          in="header",
	 *          name="Authorization",
	 *          description ="the authentication token generated by the app",
	 *          required=true,
	 *          type="string"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="list of the equipment in the order, quantities and dates",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/cart_equipment_response_object"))
	 *          )
	 *      )
	 * )
	 */
	public function details(){
		$cart = Auth::user()->cart;
		if(!$cart || $cart->empty || !isset($cart->details['suppliers'])){
			return response()->json(['message'=>'invalid access'],400);
		}
		$items = $cart->items;
		return CartEquipmentResource::collection($cart->items);
	}

	/**
	 * @param detailsRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 *
	 * @SWG\Post(
	 *     path="/api/orders/process/details",
	 *     summary="stores the equipment details in the orders, desired quantities and date of delivery and return for each equipment",
	 *     produces={"application/json"},
	 *     tags={"order"},
	 *     @SWG\Parameter(
	 *          in="header",
	 *          name="Authorization",
	 *          description ="the authentication token generated by the app",
	 *          required=true,
	 *          type="string"
	 *      ),
	 *     @SWG\Parameter(
	 *          in="body",
	 *          name="equipments",
	 *          description="desired quantities, delivery and return date for each equipment",
	 *          required=true,
	 *          schema="array",
	 *          type="array",
	 *          ref="#/definitions/cart_equipment_request_object"
	 *          ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="list of the equipment in the order, quantities and dates",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/cart_equipment_response_object"))
	 *          )
	 *      )
	 * )
	 */
	public function details_store(detailsRequest $request){
		$cart = Auth::user()->cart;
		$itemsMap = $cart->items->map(function ($item){
			return $item->id;
		})->toArray();
		$quantities = $request->input('equipments');
		$details=[];
		$sum_qty=0;
		foreach ($quantities as $key => $quantity){
			if (in_array($quantity['id'],$itemsMap)){
				$details[$quantity['id']]=$quantity;
				$sum_qty+=$quantity['qty'];
				unset($details[$quantity['id']]['id']);
			}
		}

		if ($sum_qty==0){
			return response()->json(['message'=>'At leats one equipment must have a quantity greater that 0'],400);
		}

		$this->cart_repository->updateEquipmentDetails($cart->id,$details);
		$cart->stage='final';
		$cart->save();
		return $this->details();
	}





	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Buyer\Order  $order
	 * @return \Illuminate\Http\Response
	 *
	 *
	 * @SWG\Get(
	 *     path="/api/orders/{id}/suppliers",
	 *     summary="return the list of suppliers of the specified order",
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
	 *          description ="id of the order that's going to be displayed",
	 *          required=true,
	 *          type="integer"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="suppliers of the specified order",
	 *           @SWG\Property(property="data",type="array",items=@SWG\Schema(ref="#/definitions/order_suppliers_response_object"))
	 *      ),
	 *     @SWG\Response(
	 *          response=400,
	 *          description="something went wrong creating the order",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="error_message")
	 *          )
	 *      ),
	 * )
	 */
	public function order_suppliers(showRequest $request, Order $order)
	{
		return orderSupplierResource::collection($order->suppliers);
	}

	/**
	 * @param approveRequest $request
	 * @param Order $order
	 *
	 * @return \Illuminate\Http\JsonResponse
	 *
	 *
	 * @SWG\Post(
	 *     path="/api/orders/{id}/approve",
	 *     summary="aproves the order if possible",
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
	 *          description ="id of the order that's going to be approved",
	 *          required=true,
	 *          type="integer"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="confirmation message",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="message")
	 *          )
	 *      ),
	 *     @SWG\Response(
	 *          response=400,
	 *          description="something went wrong approving the order",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="error_message")
	 *          )
	 *      ),
	 * )
	 */
	public function approve(approveRequest $request,Order $order){
		try{
			$order->approve();
			$order->save();
			return response()->json(['message'=>__('Order approved')]);
		}catch(Exception $ex){
			return response()->json(['message'=>'Something went wrong, try again'],400);
		}
	}

	/**
	 * @param cancelRequest $request
	 * @param Order $order
	 *
	 * @return \Illuminate\Http\JsonResponse
	 *
	 *
	 * @SWG\Post(
	 *     path="/api/orders/{id}/cancel",
	 *     summary="cancels the order if possible",
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
	 *          description ="id of the order that's going to be canceled",
	 *          required=true,
	 *          type="integer"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="confirmation message",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="message")
	 *          )
	 *      ),
	 *     @SWG\Response(
	 *          response=400,
	 *          description="something went wrong canceling the order",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="error_message")
	 *          )
	 *      ),
	 * )
	 */
	public function cancel(cancelRequest $request,Order $order){
		try{
			$order->cancel();
			$order->save();
			return response()->json(['message'=>__('Order canceled')]);
		}catch(Exception $ex){
			return response()->json(['message'=>'Something went wrong, try again'],400);
		}
	}


	/**
	 * @param editRequest $request
	 * @param Order $order
	 *
	 * @return \Illuminate\Http\JsonResponse
	 *
	 * @SWG\Post(
	 *     path="/api/orders/{id}/edit/begin",
	 *     summary="starts the order editing process if possible",
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
	 *          description ="id of the order that's going to be edited",
	 *          required=true,
	 *          type="integer"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="confirmation message",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="message")
	 *          )
	 *      ),
	 *     @SWG\Response(
	 *          response=400,
	 *          description="something went wrong starting the order editing process",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="error_message")
	 *          )
	 *      ),
	 * )
	 */
	public function edit_begin(editRequest $request,Order $order){
		if ($order->is_editing){
				return response()->json(['message'=>__('Order is already being edited')],400);
		}
		if ($this->order_repository->beginEditProcess($order)){
			return response()->json(['message'=>__('Order is edit mode now')]);
		}
		return response()->json(['message'=>'Something went wrong, try again'],400);
	}

	/**
	 * @param editRequest $request
	 * @param Order $order
	 *
	 * @return \Illuminate\Http\JsonResponse
	 *
	 * * @SWG\Post(
	 *     path="/api/orders/{id}/edit/cancel",
	 *     summary="cancels the order editing process if possible",
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
	 *          description ="id of the order which edit process is going to be canceled",
	 *          required=true,
	 *          type="integer"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="confirmation message",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="message")
	 *          )
	 *      ),
	 *     @SWG\Response(
	 *          response=400,
	 *          description="something went wrong canceling the order editing process",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="error_message")
	 *          )
	 *      ),
	 * )
	 */
	public function edit_cancel(editRequest $request,Order $order){
		if ($this->order_repository->cancelEditProcess($order)){
			return response()->json(['message'=>__('Order editing cancelled')]);
		}
		return response()->json(['message'=>'Something went wrong, try again'],400);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse|static
	 *
	 * @SWG\Get(
	 *     path="/api/orders/{id}/edit/site",
	 *     summary="return the picked site",
	 *     produces={"application/json"},
	 *     tags={"order"},
	 *     @SWG\Parameter(
	 *          in="header",
	 *          name="Authorization",
	 *          description ="the authentication token generated by the app",
	 *          required=true,
	 *          type="string"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="list of usable sites",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",ref="#/definitions/site_object")
	 *          )
	 *      )
	 * )
	 */
	public function edit_location(editingRequest $request,Order $order){
		$site=$this->order_repository->getEditingSite($order);
		return siteResource::make($site);
	}

	/**
	 * @param siteOnlyRequest $request
	 *
	 * @return OrderController|\Illuminate\Http\JsonResponse
	 *
	 * @SWG\Put(
	 *     path="/api/orders/{id}/edit/site",
	 *     summary="stores the specified site as the destination where items will be delivered. this's the first step after filling the shopping cart",
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
	 *          name="site",
	 *          description="id of the site that's going to be used as destination",
	 *          required=true,
	 *          type="integer",
	 *          schema="integer"
	 *     ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="Site stored",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="message")
	 *          )
	 *      ),
	 *     @SWG\Response(
	 *          response=400,
	 *          description="the status of the shopping cart is invalid",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="error_message")
	 *          )
	 *      ),
	 * )
	 */
	public function edit_location_store(siteRequest $request,Order $order){

		$site = $this->site_repository->findOneBy($request->get('site'));
		if(!$site){
			return response()->json(['message'=>'invalid site.'],400);
		}
		$this->order_repository->setEditingSite($order,$site->id);
		$site=$this->order_repository->getEditingSite($order);
		return siteResource::make($site);
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 *
	 * @SWG\Get(
	 *     path="/api/orders/{id}/edit/suppliers/available",
	 *     summary="return the list of suppliers that are in range to the selected site",
	 *     produces={"application/json"},
	 *     tags={"order"},
	 *     @SWG\Parameter(
	 *          in="header",
	 *          name="Authorization",
	 *          description ="the authentication token generated by the app",
	 *          required=true,
	 *          type="string"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="list of suppliers in range",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/suppliers_in_range_object"))
	 *          )
	 *      )
	 * )
	 *
	 */
	public function edit_available_suppliers(editingRequest $request,Order $order){
		$site=$this->order_repository->getEditingSite($order);
		$radius = $this->settings_repository->getValue('radius_in_km_from_site',100);
		$suppliers = $this->supplier_repository->suppliersInRange($site->lat,$site->lon,$radius,$site->country_id);
		return SupplierResource::collection($suppliers);

	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 *
	 * @SWG\get(
	 *     path="/api/orders/{id}/edit/suppliers",
	 *     summary="return the list of suppliers that users selected in their orders",
	 *     produces={"application/json"},
	 *     tags={"order"},
	 *     @SWG\Parameter(
	 *          in="header",
	 *          name="Authorization",
	 *          description ="the authentication token generated by the app",
	 *          required=true,
	 *          type="string"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="list of selected suppliers in range",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/suppliers_in_range_object"))
	 *          )
	 *      )
	 * )
	 */
	public function edit_suppliers(editingRequest $request,Order $order){

		$suppliers = $this->order_repository->getEditingSuppliers($order);
		return SupplierResource::collection($suppliers);

	}


	/**
	 * @param supplierRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 *
	 * @SWG\Put(
	 *     path="/api/orders/{id}/edit/suppliers",
	 *     summary="store in the order the available suppliers that users desired",
	 *     produces={"application/json"},
	 *     tags={"order"},
	 *     @SWG\Parameter(
	 *          in="header",
	 *          name="Authorization",
	 *          description ="the authentication token generated by the app",
	 *          required=true,
	 *          type="string"
	 *      ),
	 *     @SWG\Parameter(
	 *          in="body",
	 *          name="suppliers",
	 *          required=true,
	 *          description="array ids of the suppliers that are going to be used in the order",
	 *          type="array",items=@SWG\Items(ref="integer"),
	 *          schema="array",
	 *          default="[0,1,2,3]"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="stores the desired suppliers and return list of selected suppliers",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/suppliers_in_range_object"))
	 *          )
	 *      )
	 * )
	 */
	public function edit_suppliers_store(suppliersRequest $request,Order $order){

		$this->order_repository->setEditingSuppliers($order,$request->get('suppliers'));
		$suppliers = $this->order_repository->getEditingSuppliers($order);
		return SupplierResource::collection($suppliers);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 *
	 * @SWG\Get(
	 *     path="/api/orders/{id}/edit/details",
	 *     summary="return the list of equipment in the orders, desired quantities and date of delivery and return for each equipment",
	 *     produces={"application/json"},
	 *     tags={"order"},
	 *     @SWG\Parameter(
	 *          in="header",
	 *          name="Authorization",
	 *          description ="the authentication token generated by the app",
	 *          required=true,
	 *          type="string"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="list of the equipment in the order, quantities and dates",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/cart_equipment_response_object"))
	 *          )
	 *      )
	 * )
	 */
	public function edit_details(editingRequest $request,Order $order){
		$items = $this->order_repository->getEditingItems($order);
		return editingOrderItemsResource::collection($items);
	}

	/**
	 * @param detailsRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 *
	 * @SWG\Put(
	 *     path="/api/orders/{id}/edit/details",
	 *     summary="stores the equipment details in the orders, desired quantities and date of delivery and return for each equipment",
	 *     produces={"application/json"},
	 *     tags={"order"},
	 *     @SWG\Parameter(
	 *          in="header",
	 *          name="Authorization",
	 *          description ="the authentication token generated by the app",
	 *          required=true,
	 *          type="string"
	 *      ),
	 *     @SWG\Parameter(
	 *          in="body",
	 *          name="equipments",
	 *          description="desired quantities, delivery and return date for each equipment",
	 *          required=true,
	 *          schema="array",
	 *          type="array",
	 *          ref="#/definitions/cart_equipment_request_object"
	 *          ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="list of the equipment in the order, quantities and dates",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/cart_equipment_response_object"))
	 *          )
	 *      )
	 * )
	 */
	public function edit_details_store(editingDetailsRequest $request,Order $order){
		$quantities = $request->input('equipments');
		$details=[];
		$sum_qty=0;
		foreach ($quantities as $key => $quantity){
			$details[$quantity['id']]=$quantity;
			$sum_qty+=$quantity['qty'];

		}

		if ($sum_qty==0){
			return response()->json(['message'=>'At leats one equipment must have a quantity greater that 0'],400);
		}

		$this->order_repository->setEditingItems($order,$details);
		$items = $this->order_repository->getEditingItems($order);
		return editingOrderItemsResource::collection($items);
	}


	/**
	 * Display a listing of bids to the order
	 *
	 * @return \Illuminate\Http\Response
	 *
	 * @SWG\Get(
	 *     path="/api/orders/{order}/bids",
	 *     summary="list bids to the current order",
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
	 *          description="List of orders user can access",
	 *           @SWG\Property(property="data",type="object",ref="#/definitions/bid_order_item_response_object")
	 *      ),
	 * )
	 */
	public function bids(viewOrderBidsRequest $request,Order $order){
		$order->load('items.bids');
		return orderItemResource::collection($order->items);
	}

	/**
	 * @param updateOrderBidsRequest $request
	 * @param Order $order
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 *
	 * @SWG\Put(
	 *     path="/api/orders/{order}/bids",
	 *     summary="updates the selected bids for the order",
	 *     produces={"application/json"},
	 *     tags={"order"},
	 *     @SWG\Parameter(
	 *          in="header",
	 *          name="Authorization",
	 *          description ="the authentication token generated by the app",
	 *          required=true,
	 *          type="string"
	 *      ),
	 *     @SWG\Parameter(
	 *          in="body",
	 *          name="bids",
	 *          required=true,
	 *          description="array of bid and order-item ids corresponding to the accepted bids per item",
	 *          @SWG\Schema(
	 *              type="array",
	 *              @SWG\Items(
	 *                  ref="#/definitions/bid_item_request_object",
	 *              )
	 *          )
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="updates the selected bids per order item",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="data",type="array",items=@SWG\Items(ref="#/definitions/cart_equipment_response_object"))
	 *          )
	 *      ),
	 *     @SWG\Response(
	 *          response=400,
	 *          description="something went wrong updating the bids",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="error_message")
	 *          )
	 *      ),
	 * )
	 */
	public function save_bid(updateOrderBidsRequest $request,Order $order){
		try {
			$this->order_repository->updateBids( $order, $request->get( 'bids', [] ) );
			$order->load('items.bids');
			return orderItemResource::collection($order->items);
		}catch (\Error $error){
			return response()->json(['message'=>'Something went wrong, try again'],400);
		}
	}

	/**
	 * @param closeRequest $request
	 * @param Order $order
	 *
	 * @return \Illuminate\Http\JsonResponse
	 *
	 *
	 * @SWG\Post(
	 *     path="/api/orders/{id}/close",
	 *     summary="closes the order if possible",
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
	 *          description ="id of the order that's going to be closed",
	 *          required=true,
	 *          type="integer"
	 *      ),
	 *     @SWG\Response(
	 *          response=200,
	 *          description="confirmation message",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="message")
	 *          )
	 *      ),
	 *     @SWG\Response(
	 *          response=400,
	 *          description="something went wrong approving the order",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(property="error_message")
	 *          )
	 *      ),
	 * )
	 * @throws \Exception
	 */
	public function close(CloseOrderRequest $request,Order $order){
		try{
			$this->order_repository->close($order);
			return response()->json(['message'=>__('Order closed')]);
		}catch(Exception $ex){
			return response()->json(['message'=>'Something went wrong, try again'],400);
		}
	}

}
