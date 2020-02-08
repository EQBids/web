<?php

namespace App\Http\Controllers\Web\Contractor;

use function App\Http\buildAddress;
use App\Http\Controllers\Controller;
use App\Models\Supplier\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{

    public function __construct(){}

    public function index(){
        
        $suppliers = Supplier::where('status', 1)->get();
        return view('web.contractor.suppliers.index', compact('suppliers'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      
        $supplier = Supplier::where('id', $id)->first();
        return view('web.contractor.suppliers.show')->with('supplier', $supplier);
    }

}
