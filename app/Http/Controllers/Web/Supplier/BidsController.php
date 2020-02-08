<?php

namespace App\Http\Controllers\Web\Supplier;

use App\Http\Requests\Supplier\Bids\approveBidRequest;
use App\Http\Requests\Supplier\Bids\cancelBidRequest;
use App\Http\Requests\Supplier\Bids\createBidRequest;
use App\Http\Requests\Supplier\Bids\editableBidRequest;
use App\Http\Requests\Supplier\Bids\showBidRequest;
use App\Http\Requests\Supplier\Bids\updateBidRequest;
use App\Models\Buyer\Order;
use App\Models\Supplier\Bid;
use App\Repositories\Eloquent\SettingsRepository;
use App\Repositories\Eloquent\Supplier\bidRepository;
use App\Repositories\Eloquent\Supplier\supplierRepository;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Interfaces\Product\inventoryRepositoryInterface;



class BidsController extends Controller
{

	protected $inventoryRepo;
	protected $bid_repository,$supplier_repository, $settings_repository;

	public function __construct(SettingsRepository $settings_repository, bidRepository $bid_repository,supplierRepository $supplier_repository,inventoryRepositoryInterface $inventoryRepo) {
		$this->bid_repository=$bid_repository;
		$this->supplier_repository=$supplier_repository;
		$this->settings_repository = $settings_repository;
        $this->inventoryRepo = $inventoryRepo;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $bids = $this->bid_repository->accesibleBids(Auth::user());
	    return view('web.supplier.bids.index')->with(compact('bids'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Order $order)
    {
		$user = Auth::user();
		$supplier=$order->suppliers()->whereIn('id',$user->suppliers->pluck('id'))->first();

		$supplierId = Auth::user()->suppliers()->first()->id;

        $hasInventories = $this->inventoryRepo->hasInventories($supplierId);
		$equipmentIds = $this->inventoryRepo->findEquipmentIdsBySupplier($supplierId);
		
		$settings = $this->settings_repository->findAll()->whereIn('name', 'market_place_fee')->first();
		$marketPlaceFee = $settings['value'];

		$insurance = $this->bid_repository->getInsurance();
	
	
        return view('web.supplier.bids.create')->with(compact('order','equipmentIds', 'marketPlaceFee', 'insurance'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(createBidRequest $request)
    {
		
    	$user = Auth::user();
    	$order = Order::find($request->get('order_id'));
	    $supplier=$order->suppliers()->whereIn('id',$user->suppliers->pluck('id'))->first();

	    $files = Storage::files('bids/'.$order->id.'/'.$supplier->id);

		$data = $request->only(['order_id','equipments']);

		$data['supplier_id']=$supplier->id;
		
		
    	$data['details']=[
    		'notes'=>$request->get('notes')
		];
		


	    foreach ($files as $index=>$file){
		    $files[$index]=basename($file);
	    }


	    foreach ($data['equipments'] as &$equipment) {
	    	if(isset($equipment['attachments'])) {
	    		$new_attachments=[];
	    		foreach ($equipment['attachments'] as &$attachment){
					if(in_array($attachment,$files) && !in_array($attachment,$new_attachments)){
						array_push($new_attachments,$attachment);
					}
			    }

			    $equipment['attachments']=$new_attachments;
		    }
	    }

    	try{
			$settings = $this->settings_repository->findAll()->whereIn('name', 'market_place_fee')->first();
			
		    $bid = $this->bid_repository->create_bid($data,$settings['value'] );
		    return redirect(route('supplier.bids.index'))->with('notifications',collect([
				    [
					    'type'=>'success',
					    'text'=>__('Bid created successfully')
				    ]
			    ])
		    );
	    }catch(\Error $ex){
    		return redirect()->back()->withErrors(['error'=>$ex->getMessage()]);
	    }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(showBidRequest $request, Bid $bid)
    {
		$settings = $this->settings_repository->findAll()->whereIn('name', 'market_place_fee')->first();
		$fee = $settings["value"];
	
    	return view('web.supplier.bids.show')->with(compact('bid', 'fee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(editableBidRequest $request,Bid $bid)
    {
	    return view('web.supplier.bids.edit')->with(compact('bid'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateBidRequest $request, Bid $bid)
    {

	    $data = $request->only(['equipments']);
	    $data['details']=$bid->details;
		$data['details']['notes']=$request->get('notes');

	    $files = Storage::files('bids/'.$bid->order_id.'/'.$bid->supplier_id);
	    foreach ($files as $index=>$file){
	    	$files[$index]=basename($file);
	    }
	    foreach ($data['equipments'] as &$equipment) {
		    if(isset($equipment['attachments'])) {
			    $new_attachments=[];
			    foreach ($equipment['attachments'] as &$attachment){
				    if(in_array($attachment,$files) && !in_array($attachment,$new_attachments)){
					    array_push($new_attachments,$attachment);
				    }
			    }

			    $equipment['attachments']=$new_attachments;
		    }
	    }

	    try{
		    $bid = $this->bid_repository->updateBy($data,$bid->id);
		    return redirect(route('supplier.bids.index'))->with('notifications',collect([
				    [
					    'type'=>'success',
					    'text'=>__('Bid created successfully'),
					    'wait'=>10,
				    ]
			    ])
		    );
	    }catch(\Error $ex){
		    return redirect()->back()->withErrors(['error'=>$ex->getMessage()]);
	    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(cancelBidRequest $request,Bid $bid)
    {
        try{
        	$this->bid_repository->delete($bid->id);
	        return redirect(route('supplier.bids.index'))->with('notifications',collect([
			        [
				        'type'=>'success',
				        'text'=>__('Bid cancelled')
			        ]
		        ])
	        );
        }catch (\Error $error){
	        return redirect()->back()->with('notifications',collect([
		        [
			        'text'=>__("You are not authorized to cancel this bid"),
			        'type'=>'error',
			        'wait'=>10,
		        ]
	        ]));
        }
    }


    public function cancel(cancelBidRequest $request,Bid $bid){
	    return view('web.supplier.bids.cancel')->with(compact('bid'));
    }

	public function approve(approveBidRequest $request,Bid $bid)
	{
		try{
			$this->bid_repository->approve($bid->id);
			return redirect(route('supplier.bids.index'))->with('notifications',collect([
					[
						'type'=>'success',
						'text'=>__('Bid enabled'),
						'wait'=>10,
					]
				])
			);
		}catch (\Error $error){
			return redirect()->back()->with('notifications',collect([
				[
					'text'=>__("You are not authorized to cancel this bid"),
					'type'=>'error',
					'wait'=>10,
				]
			]));
		}
	}


	public function approval(approveBidRequest $request,Bid $bid){
		return view('web.supplier.bids.approval')->with(compact('bid'));
	}

	public function store_attachment(Request $request,Order $order)
	{

		$user = Auth::user();

		if(!$order || !$user){
			return response()->json([
				'message' => 'Invalid data'
			], 400);
		}

		$suppler =$order->suppliers()->whereIn('id',$user->suppliers->pluck('id'))->first();

		$files = $request->file('file');
		if(!$suppler || !$files){
			return response()->json([
				'message' => 'Invalid data'
			], 400);
		}

		if (!is_array($files)) {
			$files = [$files];
		}

		$names=[];
		for ($i = 0; $i < count($files); $i++) {
			$file = $files[$i];
			$name = str_random(10).'_'.$file->getClientOriginalName();

			Storage::disk('local')->putFileAs('bids/'.$order->id.'/'.$suppler->id,$file,$name);
			array_push($names,$name);

		}
		return response()->json([
			'message' => 'Image saved Successfully',
			'names'=>$names,
		], 200);
	}

	public function destroy_attachment(Request $request,Order $order)
	{
		$filename = $request->id;

		$user = Auth::user();
		$suppler =$order->suppliers()->whereIn('id',$user->suppliers->pluck('id'))->first();


		$files = Storage::files('bids/'.$order->id.'/'.$suppler->id);
		foreach ($files as $file){
			if (ends_with($file,$filename)){
				Storage::delete($file);
			}
		}

		return response()->json(['message' => 'File successfully deleted'], 200);
	}

	public function show_attachment(Request $request,Bid $bid,$attachment){
    	$filename = 'bids/'.$bid->order_id.'/'.$bid->supplier_id.'/'.$attachment;
    	if (Storage::exists($filename)){
		    $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
			return response()->file($storagePath.$filename);
		}
		abort(404);
	}

	public function closing(showBidRequest $request,Bid $bid){
		$settings = $this->settings_repository->findAll()->whereIn('name', 'market_place_fee')->first();
		$fee = $settings["value"];
    	return view('web.supplier.bids.close')->with(compact('bid', 'fee'));
	}

	public function close(showBidRequest $request, Bid $bid){
		
		$image = $request->file('image');
        $imageName = $image->hashName();
		$bid['contract'] = $imageName;
		
        $imageName=$request->file('image')->store('suppliers','public');
        
        $destinationPath = public_path('storage/suppliers');
        $image->move($destinationPath, $imageName);
        $data['details'] = [
        	'image'=>Storage::url($imageName),
	        'description'=>htmlentities(clean($request->get('description'))),
	        'excerpt'=>htmlentities(clean($request->get('excerpt'))),
        ];
		
		try {
			$this->bid_repository->close( $bid);
			return redirect(route('supplier.bids.index'))->with('notifications',collect([
				[
					'type'=>'success',
					'text'=>'Bid Confirmed'
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
