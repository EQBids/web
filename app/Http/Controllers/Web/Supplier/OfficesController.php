<?php

namespace App\Http\Controllers\Web\Supplier;

use function App\Http\buildAddress;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Office\SupplierOfficeRequest;
use App\Repositories\Interfaces\Geo\cityRepositoryInterface;
use App\Repositories\Interfaces\Geo\CountryRepositoryInterface;
use App\Repositories\Interfaces\Geo\stateRepositoryInterface;
use App\Repositories\Interfaces\geocodingRepositoryInterface;
use App\Repositories\Interfaces\Product\officeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficesController extends Controller
{
    protected $officeRepo,$cityRepo,$stateRepo,$countryRepo,$geoRepo;
    public function __construct(officeRepositoryInterface $officeRepo,
                                cityRepositoryInterface $cityRepo,
                                stateRepositoryInterface $stateRepo,
                                CountryRepositoryInterface $countryRepo,
                                geocodingRepositoryInterface $geoRepo)
    {
        $this->officeRepo = $officeRepo;
        $this->cityRepo = $cityRepo;
        $this->stateRepo = $stateRepo;
        $this->countryRepo = $countryRepo;
        $this->geoRepo = $geoRepo;
    }

    public function index(){
       
        $offices = $this->officeRepo->offices(Auth::user());
        return view('web.supplier.offices.index',compact('offices'));
    }

    public function create(){
        return view('web.supplier.offices.create');
    }

    public function store(SupplierOfficeRequest $request){
    
        $data = [
            'name'          =>  $request->get('location'),
            'address'       =>  $request->get('address'),
            'country_id'    =>  $request->get('country'),
            'state_id'      =>  $request->get('state'),
            'city_id'       =>  $request->get('city'),
            'status'        =>  1,
        ];

        $data['details']['notes'] = $request->get('notes');

        $city = $this->cityRepo->findOneBy($request->get('city'));
        $state = $this->stateRepo->findOneBy($request->get('state'));
        $country = $this->countryRepo->findOneBy($request->get('country'));

        $fullAddress = buildAddress($request->get('address'),$country->name,$state->name,$city->name);

        $coordinates = $this->geoRepo->getAddressCoordinates($fullAddress,$city);

        $data['lat'] = $coordinates->getLatitude();
        $data['lon'] = $coordinates->getLongitude();


        if($request->hasFile('image')){
            $data['details']['image'] = $request->file('image')->store('suppliers/'.$request->user()->id,'public');
        }

        $this->officeRepo->createAndAddUser($data,Auth::user()->id);

        return redirect()->route('supplier.offices.index')->with('notifications',collect([
            [
                'text'=>__("The office was created successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }

    public function edit($id){

        $office = $this->officeRepo->findOneBy($id);
        $city = $office->city;
        $country = $office->country;
        $state = $office->state;

        return view('web.supplier.offices.edit',compact('office','country','state','city'));
    }

    public function update(SupplierOfficeRequest $request, $id){

        $data = [
            'name'          =>  $request->get('location'),
            'address'       =>  $request->get('address'),
            'country_id'    =>  $request->get('country'),
            'state_id'      =>  $request->get('state'),
            'city_id'       =>  $request->get('city'),
        ];

        $data['details']['notes'] = $request->get('notes');

        $city = $this->cityRepo->findOneBy($request->get('city'));
        $state = $this->stateRepo->findOneBy($request->get('state'));
        $country = $this->countryRepo->findOneBy($request->get('country'));

        $fullAddress = buildAddress($request->get('address'),$country->name,$state->name,$city->name);

        $coordinates = $this->geoRepo->getAddressCoordinates($fullAddress,$city);

        $data['lat'] = $coordinates->getLatitude();
        $data['lon'] = $coordinates->getLongitude();

        $office = $this->officeRepo->findOneBy($id);

        if($request->hasFile('image')){

//            If it had an image before
            if(isset($office->details['image'])){
                $image = $office->details['image'];
                //Deleting the image.
                unlink(storage_path("app/public/".$image));
            }

            $data['details']['image'] = $request->file('image')->store('suppliers/'.$request->user()->id,'public');
        }

        $this->officeRepo->updateBy($data,$id);

        return redirect()->route('supplier.offices.index')->with('notifications',collect([
            [
                'text'=>__("The office was updated successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }

    public function delete($id){

        $office = $this->officeRepo->findOneBy($id);
        return view('web.supplier.offices.delete',compact('office'));
    }

    public function destroy($id){

        $this->officeRepo->delete($id);

        return redirect()->route('supplier.offices.index')->with('notifications',collect([
            [
                'text'=>__("The office was deleted successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }
}
