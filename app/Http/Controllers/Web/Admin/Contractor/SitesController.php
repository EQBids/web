<?php

namespace App\Http\Controllers\Web\Admin\Contractor;

use App\Http\Requests\Buyer\Site\SiteCDRequest;
use App\Http\Requests\Buyer\Site\SiteDeleteRequest;
use App\Models\Buyer\Site;
use App\Repositories\Eloquent\Geo\metroRepository;
use App\Repositories\Interfaces\Buyer\contractorRepositoryInterface;
use App\Repositories\Interfaces\Buyer\siteRepositoryInterface;
use App\Repositories\Interfaces\Geo\cityRepositoryInterface;
use App\Repositories\Interfaces\Geo\CountryRepositoryInterface;
use App\Repositories\Interfaces\Geo\stateRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SitesController extends Controller
{

	protected $site_repository,$contractor_repository,$country_repository,$state_repository,$metro_repository,$city_repository;

	public function __construct(siteRepositoryInterface $site_repository,
								contractorRepositoryInterface $contractor_repository,
								CountryRepositoryInterface $country_repository,
								stateRepositoryInterface $state_repository,
								metroRepository $metro_repository,
								cityRepositoryInterface $city_repository
	) {
		$this->site_repository=$site_repository;
		$this->contractor_repository=$contractor_repository;
		$this->country_repository=$country_repository;
		$this->state_repository=$state_repository;
		$this->metro_repository=$metro_repository;
		$this->city_repository=$city_repository;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$sites = $this->site_repository->with(['city','state','contractor'])->findAll(['id','nickname','name','city_id','state_id','contractor_id']);
		return view('web.admin.sites.index')->with(compact('sites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$contractors = $this->contractor_repository->findAll(['id','company_name']);

	    $viewData=compact('contractors','countries','states','cities');
		$country = $this->country_repository->findOneBy(old('country',null));
	    $state = $this->state_repository->findOneBy(old('state',null));
	    $metro = $this->metro_repository->findOneBy(old('metro',null));
	    $city = $this->city_repository->findOneBy(old('city',null));

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

        return view('web.admin.sites.create')->with($viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SiteCDRequest $request)
    {
	    $data = $request->all();
	    $data['city_id']=$request->input('city');
	    $data['metro_id']=$request->input('metro');
	    $data['state_id']=$request->input('state');
	    $data['country_id']=$request->input('country');
	    $data['contractor_id']=$request->input('contractor');

	    $data['details']=[
		    'special_instructions' =>  $request->get('special_instructions'),
	    ];
	    $this->site_repository->create($data);
	    return redirect(route('admin.sites.index'))->with('notifications',collect([
	        [
	        	'type'=>'success',
		        'text'=>'Site created successfully'
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
        return view('web.admin.sites.show')->with(compact('site'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
	    $contractors = $this->contractor_repository->findAll(['id','company_name']);

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

	    return view('web.admin.sites.edit')->with($viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SiteCDRequest $request, Site $site)
    {
	    $data = $request->all();
	    $data['city_id']=$request->input('city');
	    $data['metro_id']=$request->input('metro');
	    $data['state_id']=$request->input('state');
	    $data['country_id']=$request->input('country');
	    $data['contractor_id']=$request->input('contractor');

	    $data['details']=[
		    'special_instructions' =>  $request->get('special_instructions'),
	    ];
	    $this->site_repository->updateBy($data,$site->id);
	    return redirect(route('admin.sites.edit',[$site->id]))->with('notifications',collect([
	    	[
				'type'=>'success',
			    'text'=>__('Site updated')
		    ]
	    ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SiteDeleteRequest $request,Site $site)
    {
        $this->site_repository->delete($site->id);
        return redirect(route('admin.sites.index'))->with('notifications',collect([
        	[
        		'type'=>'success',
		        'text'=>'Site deleted'
	        ]
        ]));
    }

    public function delete(SiteDeleteRequest $request,Site $site){
    	return view('web.admin.sites.delete')->with(compact('site'));
    }
}
