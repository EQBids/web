<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Buyer\Order;
use App\Repositories\Eloquent\Buyer\orderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{


	protected $order_repository;
	public function __construct(orderRepository $order_repository) {
		$this->order_repository=$order_repository;
	}

	public function index(Request $request){

		$orders = $this->order_repository->with(['site.contractor'])->findAll();
		return view('web.admin.orders.index')->with(compact('orders'));
	}

	public function show(Request $request,Order $order){

		return view('web.admin.orders.show')->with(compact('order'));
	}

	public function delete(Request $request,Order $order){
		if($order->status!=Order::STATUS_CANCELLED){
			return view('web.admin.orders.delete')->with(compact('order'));
		}
		return redirect(route('admin.orders.index'));

	}

	public function destroy(Request $request,Order $order){
		if($order && $order->id){
			if ($this->order_repository->delete($order->id)) {
				return redirect(route('admin.orders.index'))->with( 'notifications', collect( [
					[
						'text' => 'Order sucessfully removed',
						'type' => 'success',
						'wait' => 10
					]
				] ) );
			}else{
				return redirect()->back()->with( 'notifications', collect( [
					[
						'text' => 'Error removing the order',
						'type' => 'error'
					]
				] ) );
			}
		}

	}
}
