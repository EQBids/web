<?php

namespace App\Http\Controllers\Web\Contractor;

use App\Http\Requests\Buyer\Site\CreateJobSiteRequest;
use App\Http\Requests\Buyer\Site\SiteCDRequest;
use App\Models\Buyer\Site;
use App\Repositories\Interfaces\Buyer\contractorRepositoryInterface;
use App\Repositories\Interfaces\Buyer\siteRepositoryInterface;
use App\Repositories\Interfaces\Geo\cityRepositoryInterface;
use App\Repositories\Interfaces\Geo\CountryRepositoryInterface;
use App\Repositories\Interfaces\Geo\metroRepositoryInterface;
use App\Repositories\Interfaces\Geo\stateRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class siteController extends Controller
{

	protected $site_repository,$country_repository,$state_repository,$city_repository,$contractor_repository,$metro_repository;


	public function __construct(siteRepositoryInterface $site_repository,
								CountryRepositoryInterface $country_repository,
								stateRepositoryInterface $state_repository,
								metroRepositoryInterface $metro_repository,
								cityRepositoryInterface $city_repository,
								contractorRepositoryInterface $contractor_repository
								) {
		$this->site_repository=$site_repository;
		$this->country_repository=$country_repository;
		$this->state_repository=$state_repository;
		$this->city_repository=$city_repository;
		$this->contractor_repository=$contractor_repository;
		$this->metro_repository=$metro_repository;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$contractor = Auth::user()->contractor;
    	if($contractor){
		    $sites = $this->site_repository->findAllBy($contractor->id,'contractor_id');
	    }else{
    		$sites=null;
	    }

    	return view('web.contractor.job_sites.index',compact('sites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = $this->country_repository->findAll();
        $states = $this->state_repository->findAllBy($countries->first()->id,'country_id');
        $cities = $this->city_repository->findAllBy($states->first()->id,'state_id');
        $contractors = $this->contractor_repository->findAllBy(Auth::user()->id,'user_id');

        return view('web.contractor.job_sites.create',compact(
            'countries',
            'states',
            'cities',
            'contractors'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateJobSiteRequest $request)
    {
        $siteData = [
            'city_id'           =>  $request->get('city'),
            'country_id'        =>  $request->get('country'),
            'metro_id'          =>  $request->get('metro'),
            'state_id'          =>  $request->get('state'),
            'zip'               =>  $request->get('zip'),
            'address'           =>  $request->get('address'),
            'nickname'          =>  $request->get('nickname'),
            'name'              =>  $request->get('name'),
            'phone'             =>  $request->get('phone'),
            'contact'           =>  $request->get('contact'),
            'contractor_id'     =>  $request->get('contractor'),
        ];
        $siteData['details']=[
            'special_instructions' =>  $request->get('special_instructions'),
        ];

        $siteData['user_id'] = Auth::user()->id;
        $this->site_repository->create($siteData);

        //TODO: make it redirect back to the calling interface.
        return redirect()->route('contractor.sites.index')->with('notifications',collect([
            [
                'text'=>__('The job site was created successfully'),
                'type'=>'success',
                'wait'=>10
            ]
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        return view('web.contractor.job_sites.show')->with(compact('site'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$site = Site::findOrFail($id);

	    $viewData=compact('contractors','site');
	    $country = $this->country_repository->findOneBy(old('country',$site->country_id));
	    $state = $this->state_repository->findOneBy(old('state',$site->state_id));
	    $metro = $this->metro_repository->findOneBy(old('metro',$site->metro_id));
	    $city = $this->city_repository->findOneBy(old('city',$site->city_id));

	    if($country){
		    $viewData['country']=$country;
	    }

	    if($state){
		    $viewData['state']=$state;
	    }

	    if($metro){
		    $viewData['metro']=$metro;
	    }

	    if($city){
		    $viewData['city']=$city;
	    }
	    return view('web.contractor.job_sites.edit',$viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SiteCDRequest $request, $id)
    {
    	$site = Site::findOrFail($id);
        $data = $request->all();
	    $data['city_id']=$request->input('city');
	    $data['metro_id']=$request->input('metro');
	    $data['state_id']=$request->input('state');
	    $data['country_id']=$request->input('country');
	    $data['contractor_id']=$request->input('contractor');

	    if(!$request->get('contractor')){
		    $data['contractor_id']=Auth::user()->contractor->id;
	    }
	    $data['details']=[
		    'special_instructions' =>  $request->get('special_instructions'),
	    ];
	    $this->site_repository->updateBy($data,$id);
	    return redirect(route('contractor.sites.edit',[$id]))->with('notifications',collect([
	    	[
	    		'type'=>'success',
			    'text'=>'Site updated'
		    ]
	    ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $site = Site::findOrFail($id);
        $this->site_repository->delete($id);
        return redirect(route('contractor.sites.index'));
    }

    public function delete($id){
		$site = Site::findOrFail($id);
		return view('web.contractor.job_sites.delete')->with(compact('site'));
    }
}
