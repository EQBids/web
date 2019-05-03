<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\Supplier\supplierRepository;
use Illuminate\Support\Facades\Auth;



class SupplierController extends Controller
{

    protected $supplier_repository;
    public function __construct(supplierRepository $supplier_repository)
    {
    	$this->supplier_repository=$supplier_repository;
    }

    public function dashboard(){

       // if ($order_suppliers = $this->supplier_repository->accesibleOrderInvitations(Auth::user())->count()){
        	return redirect(route('supplier.orders.index'));
       // }
	   // return redirect(route('supplier.offices.index'));

    }


}
