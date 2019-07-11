<?php

namespace App\Http\Controllers\Web\Contractor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Buyer\Office\AddWorkerToOfficeRequest;
use App\Http\Requests\Buyer\Office\CreateOfficeRequest;
use App\Repositories\Interfaces\Buyer\officeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficesController extends Controller
{

    protected $officeRepo,$geoRepo,$cityRepo,$stateRepo,$countryRepo;

    public function __construct(officeRepositoryInterface $officeRepo)

    {
        $this->officeRepo = $officeRepo;
    }

    public function index(){
        
        $offices = $this->officeRepo->findAllWhereUserBelongsTo(Auth::user()->id);
        //print_r($offices);die;
        return view('web.contractor.offices.index',compact('offices'));
    }

    public function edit($id){

        $office = $this->officeRepo->with(['country','state','city'])->findOneBy($id);
        $country = $office->country;
        $state = $office->state;
        $city = $office->city;

        return view('web.contractor.offices.edit',compact('office','country','state','city') );
    }

    public function update(CreateOfficeRequest $request,$id){

        $data = [
            'company_name'  =>  $request->get('location'),
            'country_id'    =>  $request->get('country'),
            'address'       =>  $request->get('address'),
            'state_id'      =>  $request->get('state'),
            'city_id'       =>  $request->get('city'),
            'details'       =>  $request->get('details'),
            'postal_code'   =>  $request->get('zip'),
        ];

	    if($request->hasFile('image')){
		    $data['image']=$request->file('image')->store('contractors/'.$request->user()->id,'public');
	    }
    
	    $this->officeRepo->update($id,$data);
  
        return redirect()->route('contractor.offices.index')->with('notifications',collect([
            [
                'text'=>__("The office was updated successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }


    public function create(){

        return view('web.contractor.offices.create');
    }

    public function store(CreateOfficeRequest $request){

        $data = [
            'company_name'  =>  $request->get('location'),
            'country_id'    =>  $request->get('country'),
            'address'       =>  $request->get('address'),
            'state_id'      =>  $request->get('state'),
            'city_id'       =>  $request->get('city'),
            'details'       =>  $request->get('details'),
            'postal_code'   =>  $request->get('zip'),
            'user_id'       =>  Auth::user()->id,
        ];

        if($request->hasFile('image')){
        	$data['image']=$request->file('image')->store('contractors/'.$request->user()->id,'public');
        }

        $this->officeRepo->create($data);

        return redirect()->route('contractor.offices.index')->with('notifications',collect([
            [
                'text'=>__("The office was created successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }

    public function delete($id){

        $office = $this->officeRepo->findOneBy($id);
        return view('web.contractor.offices.delete',compact('office'));
    }

    public function destroy($id){

        $this->officeRepo->delete($id);

        return redirect()->route('contractor.offices.index')->with('notifications',collect([
            [
                'text'=>__("The office was deleted successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));

    }

    public function workers($id){

        $office = $this->officeRepo->findOneBy($id);
        $workers = $this->officeRepo->findAllWorkersAffiliatedTo($id);
        $eligibleWorkers = $this->officeRepo->findEligibleWorkersForOffice($id,Auth::user());
        return view('web.contractor.offices.workers',compact('office','workers','eligibleWorkers'));
    }

    public function addWorker(AddWorkerToOfficeRequest $request, $id){

        $this->officeRepo->addWorkerToOffice($id,$request->get('eligible_worker'));

        return redirect()->route('contractor.offices.workers',[$id])->with('notifications',collect([
            [
                'text'=>__("The worker was added successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }
}
