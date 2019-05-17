<?php

namespace App\Http\Controllers\Web\Contractor;

use App\Http\Requests\Buyer\Order\approveRequest;
use App\Http\Requests\Buyer\Order\createOrderRequest;
use App\Http\Requests\Buyer\Order\detailsRequest;
use App\Http\Requests\Buyer\Order\edit\editingDetailsRequest;
use App\Http\Requests\Buyer\Order\edit\siteRequest;
use App\Http\Requests\Buyer\Order\edit\suppliersRequest;
use App\Http\Requests\Buyer\Order\editingRequest;
use App\Http\Requests\Buyer\Order\editRequest;
use App\Http\Requests\Buyer\Order\locationRequest;
use App\Http\Requests\Buyer\Order\showRequest;
use App\Http\Requests\Buyer\Order\supplierRequest;
use App\Http\Requests\Buyer\Order\updateOrderBidsRequest;
use App\Http\Requests\Buyer\Order\viewOrderBidsRequest;
use App\Http\Requests\Supplier\Bids\updateBidRequest;
use App\Models\Buyer\Cart;
use App\Models\Buyer\Order;
use App\Repositories\Eloquent\Geo\CountryRepository;
use App\Repositories\Eloquent\Geo\stateRepository;
use App\Repositories\Eloquent\SettingsRepository;
use App\Repositories\Eloquent\Supplier\supplierRepository;
use App\Repositories\Interfaces\Buyer\cartRepositoryInterface;
use App\Repositories\Interfaces\Buyer\orderRepositoryInterface;
use App\Repositories\Interfaces\Buyer\siteRepositoryInterface;
use App\Repositories\Interfaces\Geo\cityRepositoryInterface;
use App\Repositories\Interfaces\Supplier\supplierRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class orderController extends Controller
{
	protected $order_repository,$cart_repository,$site_repository,$city_repository,$supplier_repository;
	protected $settings_repository,$country_repository,$state_repository;
	public function __construct(orderRepositoryInterface $order_repository,
								cartRepositoryInterface $cart_repository,
								siteRepositoryInterface $site_repository,
								cityRepositoryInterface $city_repository,
								supplierRepository $supplier_repository,
								SettingsRepository $settings_repository,
								CountryRepository $country_repository,
								stateRepository $state_repository
								) {
		$this->order_repository=$order_repository;
		$this->cart_repository=$cart_repository;
		$this->site_repository=$site_repository;
		$this->city_repository=$city_repository;
		$this->supplier_repository=$supplier_repository;
		$this->settings_repository=$settings_repository;
		$this->state_repository=$state_repository;
		$this->country_repository=$country_repository;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		
		$orders = $this->order_repository->accesibleOrders(Auth::user())->findAll();
		return view('web.contractor.orders.index')->with(compact('orders'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(createOrderRequest $request)
    {

        $order = $this->order_repository->createFromCart(Auth::user()->cart);
        if(!$order){
	        return redirect()->back()->with('notifications',collect([
			        [
				        'text'=>'Something went wrong, try again',
				        'type'=>'error',
			        ]
		        ]
	        ));
        }

        return view('web.contractor.orders.confirmation')->with('notifications',collect([
        	[
        		'text'=>'Order created',
		        'type'=>'success',
	        ]
        ]
        ));


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $order
     * @return \Illuminate\Http\Response
     */
    public function show(showRequest $request,Order $order)
    {
    	return view('web.contractor.orders.show')->with(compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(editRequest $request,Order $order)
    {
	    if ($order->is_editing){
		    return redirect(route('contractor.orders.edit.site',[$order->id]));
	    }
	    return view('web.contractor.orders.edit')->with(compact('order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(editingRequest $request, Order $order)
    {
	    if(!$order->is_editing){
		    return redirect()->back()->with('notifications',collect([
				    [
					    'text'=>'Order isn\'t being edited',
					    'type'=>'error',
				    ]
			    ]
		    ));
	    }
	    try {
		    $this->order_repository->finishEditing( $order );
		    return redirect(route('contractor.orders.index'))->with('notifications',collect([
				    [
					    'text'=>'Order updated',
					    'type'=>'success',
				    ]
			    ]
		    ));
	    }catch (\Exception $exep){
		    return redirect()->back()->with('notifications',collect([
				    [
					    'text'=>'Something went wrong, try again',
					    'type'=>'error',
				    ]
			    ]
		    ));
	    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
	    try{
		    $order->cancel();
		    $order->save();
		    return redirect(route('contractor.orders.index'))->with('notifications',collect([
				    [
					    'text'=>'Order canceled',
					    'type'=>'success',
				    ]
			    ]
		    ));
	    }catch(Exception $ex){
		    return redirect()->back()->with('notifications',collect([
				    [
					    'text'=>'Something went wrong, try again',
					    'type'=>'error',
				    ]
			    ]
		    ));
	    }
    }

    public function cart(){
    	$cart = Auth::user()->cart;
    	
	    return view('web.contractor.orders.cart')->with(compact('cart'));
    }

    public function location(){
    	$user = Auth::user();
	    $cart = Auth::user()->cart;
    	if($user->hasAnyRol(['contractor-superadmin','contractor-admin'])){
    		$sites = $user->ownedSites;
	    }else{
    		$sites = $user->sites;
	    }
	    if(isset($cart->details['site_id'])){
    		$site_id = $cart->details['site_id'];
	    }


	    $country = $this->country_repository->findOneBy(old('country',null));
	    $state = $this->state_repository->findOneBy(old('state',null));
	    $city = $this->city_repository->findOneBy(old('city',null));

	    if($country){
		    $viewData['country']=$country;
	    }

	    if($state){
		    $viewData['state']=$state;
	    }

	    if($city){
		    $viewData['city']=$city;
	    }

    	return view('web.contractor.orders.process.location')->with(compact('state','sites','site_id','country','city','state'));
    }

    public function location_store(locationRequest $request){

    	$cart = Auth::user()->cart;

    	if($request->has('new')){
		    $data = $request->all();
		    $data['city_id']=$request->input('city');
		    $data['metro_id']=$request->input('metro');
		    $data['state_id']=$request->input('state');
		    $data['country_id']=$request->input('country');
		    $data['contractor_id']=Auth::user()->contractor->id;
			$data['user_id']=Auth::user()->id;
		    $data['details']=[
			    'special_instructions' =>  $request->get('special_instructions'),
		    ];
		    $site=$this->site_repository->create($data);
		    $cart->setSite($site->id);
		    $cart->save();
	    }elseif($request->has('site') && $request->get('site')){
		    $site = $this->site_repository->findOneBy($request->get('site'));
		    if(!$site){
			    return Redirect::back()->withErrors([
				    'site'=>__('Invalid site')
			    ]);
		    }
		    $cart->setSite($site->id);
		    $cart->save();
	    }else{
		    return Redirect::back()->withErrors([
			    ''=>__('Invalid data')
		    ]);
	    }
	    return redirect()->to(route('contractor.orders.process.suppliers'));

    }

    public function suppliers(Request $request){
    	$cart = Auth::user()->cart;

    	if ($cart->empty || !isset($cart->details['site_id'])){
		    return redirect()->back()->with('notifications',collect([
			    [
				    'type'=>'error',
				    'text'=>'invalid access'
			    ]
		    ]));
	    }
	    $site_id = $cart->details['site_id'];
    	$site = $this->site_repository->findOneBy($site_id);
    	$radius = $this->settings_repository->getValue('radius_in_km_from_site',100);
    	$suppliers = $this->supplier_repository->suppliersInRange($site->lat,$site->lon,$radius,$site->country_id,$cart->items);
		if(isset($cart->details['suppliers'])){
			$old_suppliers=$cart->details['suppliers'];
		}
	    return view('web.contractor.orders.process.suppliers')->with(compact('suppliers','old_suppliers'));

    }

    public function suppliers_store(supplierRequest $request){
	    $cart = Auth::user()->cart;

	    if ($cart->empty){
		    return redirect()->back()->with('notifications',collect([
			    [
				    'type'=>'error',
				    'text'=>'invalid access'
			    ]
		    ]));
	    }

	    $cart->setSuppliers($request->get('suppliers'));
	    $cart->save();
	    return redirect()->to(route('contractor.orders.process.details'));
    }

    public function details(){
        $cart = Auth::user()->cart;
        if(!$cart || $cart->empty || !isset($cart->details['suppliers'])){
	        return redirect(route('contractor.equipment.index'))->with('notifications',collect([
		        [
			        'type'=>'error',
			        'text'=>'invalid access'
		        ]
	        ]));
        }
        return view('web.contractor.orders.process.details')->with(compact('cart'));
    }

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
    	    	return redirect()->back()->withErrors([
    	    		'equipments'=>'At leats one equipment must have a quantity greater that 0'
		        ])->withInput();
	        }

	        $this->cart_repository->updateEquipmentDetails($cart->id,$details);
    	    $cart->stage='final';
    	    $cart->save();
			return redirect(route('contractor.orders.process.final'));
    }

    public function final(){
    	$cart = Auth::user()->cart;
    	if(!$cart || $cart->stage!='final'){
    		return redirect()->back()->with('notifications',collect([
    			[
    				'type'=>'error',
				    'text'=>'Invalid cart state'
			    ]
		    ]));
	    }
		$site = $this->site_repository->findOneBy($cart->details['site_id']);
    	$_suppliers = $cart->details['suppliers'];
	    $radius = $this->settings_repository->getValue('radius_in_km_from_site',100);
	    $suppliers = $this->supplier_repository->suppliersInRange($site->lat,$site->lon,$radius,$site->country_id)->whereIn('id',$_suppliers);
	    return view('web.contractor.orders.process.final')->with(compact('cart','site','suppliers'));
    }

    public function approval(showRequest $request,Order $order){
	    return view('web.contractor.orders.approval')->with(compact('order'));
    }

	public function approve(approveRequest $request,Order $order){
		try{
			$order->approve();
			$order->save();
			return redirect(route('contractor.orders.index'))->with('notifications',collect([
					[
						'text'=>'Order approved',
						'type'=>'success',
					]
				]
			));
		}catch(Exception $ex){
			return redirect()->back()->with('notifications',collect([
					[
						'text'=>'Something went wrong, try again',
						'type'=>'error',
					]
				]
			));
		}
	}

	public function delete(showRequest $request,Order $order){
		return view('web.contractor.orders.cancel')->with(compact('order'));
	}

	public function edit_begin(editRequest $request,Order $order){
		if ($order->is_editing){
			return redirect()->back()->withErrors(['message'=>__('Order is already being edited')]);
		}
		if ($this->order_repository->beginEditProcess($order)){
			return redirect(route('contractor.orders.edit.site',[$order->id]));
		}
		return redirect()->back()->withErrors(['message'=>'Something went wrong, try again']);
	}

	public function edit_location(editingRequest $request,Order $order){

		$user = Auth::user();
		if($user->hasAnyRol(['contractor-superadmin','contractor-admin'])){
			$sites = $user->ownedSites;
		}else{
			$sites = $user->sites;
		}

		$country = $this->country_repository->findOneBy(old('country',null));
		$state = $this->state_repository->findOneBy(old('state',null));
		$city = $this->city_repository->findOneBy(old('city',null));

		if($country){
			$viewData['country']=$country;
		}

		if($state){
			$viewData['state']=$state;
		}

		if($city){
			$viewData['city']=$city;
		}
		$site=$this->order_repository->getEditingSite($order);

		return view('web.contractor.orders.edit.location')->with(compact('state','sites','site','country','city','order'));
		
	}

	public function edit_location_store(siteRequest $request,Order $order){

		if($request->has('new')){
			$data = $request->all();
			$data['city_id']=$request->input('city');
			$data['metro_id']=$request->input('metro');
			$data['state_id']=$request->input('state');
			$data['country_id']=$request->input('country');
			$data['contractor_id']=Auth::user()->contractor->id;
			$data['user_id']=Auth::user()->id;
			$data['details']=[
				'special_instructions' =>  $request->get('special_instructions'),
			];
			$site=$this->site_repository->create($data);
			$this->order_repository->setEditingSite($order,$site->id);
		}elseif($request->has('site') && $request->get('site')){
			$site = $this->site_repository->findOneBy($request->get('site'));
			if(!$site){
				return Redirect::back()->withErrors([
					'site'=>__('Invalid site')
				]);
			}
			$this->order_repository->setEditingSite($order,$site->id);
		}else{
			return Redirect::back()->withErrors([
				''=>__('Invalid data')
			]);
		}
		return redirect()->to(route('contractor.orders.edit.suppliers',[$order->id]));

	}


	public function edit_suppliers(editingRequest $request,Order $order){

		$site = $this->order_repository->getEditingSite($order);
		$radius = $this->settings_repository->getValue('radius_in_km_from_site',100);
		$suppliers = $this->supplier_repository->suppliersInRange($site->lat,$site->lon,$radius,$site->country_id,$order->items->pluck('equipment'));
		$old_suppliers = $this->order_repository->getEditingSuppliers($order);
		return view('web.contractor.orders.edit.suppliers')
			->with(compact('suppliers','old_suppliers','order'));



	}


	public function edit_suppliers_store(suppliersRequest $request,Order $order){

		$this->order_repository->setEditingSuppliers($order,$request->get('suppliers'));
		return redirect()->to(route('contractor.orders.edit.details',[$order->id]));
	}

	public function edit_details(editingRequest $request,Order $order){
		$items = $this->order_repository->getEditingItems($order);
		$editing_suppliers = $this->order_repository->getEditingSuppliers($order);
		if($editing_suppliers->count()==0){
			return redirect()->back()->with('notifications',collect([
				[
					'type'=>'error',
					'text'=>'invalid access'
				]
			]));
		}
		return view('web.contractor.orders.edit.details')->with(compact('order','items'));

	}


	public function edit_details_store(editingDetailsRequest $request,Order $order){
		$quantities = $request->input('equipments');
		$details=[];
		$sum_qty=0;
		foreach ($quantities as $key => $quantity){
			$details[$quantity['id']]=$quantity;
			$sum_qty+=$quantity['qty'];
		}

		if ($sum_qty==0){
			return redirect()->back()->withErrors(['message'=>'At leats one equipment must have a quantity greater that 0']);
		}
		$this->order_repository->setEditingItems($order,$details);
		return redirect(route('contractor.orders.edit.final',[$order->id]));
	}

	public function edit_final(editingRequest $request,Order $order){

		$editing_suppliers = $this->order_repository->getEditingSuppliers($order);
		if(!$editing_suppliers->count()){
			return redirect()->back()->with('notifications',collect([
				[
					'type'=>'error',
					'text'=>'No suppliers selected'
				]
			]));
		}

		$site = $this->order_repository->getEditingSite($order);
		$suppliers = $editing_suppliers->pluck('id');
		$radius = $this->settings_repository->getValue('radius_in_km_from_site',100);
		$suppliers = $this->supplier_repository->suppliersInRange($site->lat,$site->lon,$radius,$site->country_id)->whereIn('id',$suppliers);
		$items = $this->order_repository->getEditingItems($order);
		return view('web.contractor.orders.edit.final')->with(compact('order','site','suppliers','items'));
	}

	public function bids(viewOrderBidsRequest $request,Order $order){
    	$order->load('items.bids');
		return view('web.contractor.orders.bids')->with(compact('order'));
	}

	public function save_bid(updateOrderBidsRequest $request,Order $order){
    	try {
		    $this->order_repository->updateBids( $order, $request->get( 'bids', [] ) );
		    return redirect()->back()->with('notifications',collect([
			    [
				    'type'=>'success',
				    'text'=>'Bids updated'
			    ]
		    ]));
	    }catch (\Error $error){
		    return redirect()->back()->with('notifications',collect([
			    [
				    'type'=>'error',
				    'text'=>'something went wrong'
			    ]
		    ]));
	    }
	}

	public function closing(Request $request,Order $order){
    	$order->load('items.bids');
    	return view('web.contractor.orders.close')->with(compact('order'));
	}

	public function close(Request $request,Order $order){
		try {
			$this->order_repository->close( $order);
			return redirect(route('contractor.orders.index'))->with('notifications',collect([
				[
					'type'=>'success',
					'text'=>'Order Closed'
				]
			]));
		}catch (\Error $error){
			return redirect()->back()->with('notifications',collect([
				[
					'type'=>'error',
					'text'=>'something went wrong'
				]
			]));
		}
	}


}
