<?php

namespace App\Http\Controllers\Web\Supplier;

use App\Http\Requests\Supplier\Orders\rejectOrder;
use App\Models\Buyer\Order;
use App\Repositories\Eloquent\Buyer\orderRepository;
use App\Repositories\Eloquent\Supplier\supplierRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class orderController extends Controller
{
	protected $order_repository,$supplier_repository;

	public function __construct(orderRepository $order_repository,supplierRepository $supplier_repository) {
		$this->order_repository=$order_repository;
		$this->supplier_repository=$supplier_repository;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $order_suppliers = $this->supplier_repository->accesibleOrderInvitations(Auth::user());
	    return view('web.supplier.orders.index')->with( ['order_suppliers' => $order_suppliers ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('web.supplier.orders.show')->with(compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(rejectOrder $request, Order $order)
    {
    	$user = Auth::user();
    	foreach ($user->suppliers as $supplier){
		    if ($this->supplier_repository->rejectOrderInvitations($supplier,$order)){
		    	return redirect(route('supplier.orders.index'))->with('notifications',collect([
				    [
					    'text'=>__("The Order was successfully rejected"),
					    'type'=>'success',
					    'wait'=>10,
				    ]
			    ]));
		    }
	    }
	    return redirect()->back()->with('notifications',collect([
		    [
			    'text'=>__("You are not authorized to reject this order"),
			    'type'=>'error',
			    'wait'=>10,
		    ]
	    ]));


    }

	public function delete(rejectOrder $request, Order $order)
	{
		return view('web.supplier.orders.cancel')->with(compact('order'));
	}
}
