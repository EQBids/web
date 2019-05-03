<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Buyer\Site\CreateJobSiteRequest;
use App\Repositories\Eloquent\Buyer\orderRepository;
use App\Repositories\Eloquent\Buyer\siteRepository;
use App\Repositories\Interfaces\Buyer\contractorRepositoryInterface;
use App\Repositories\Interfaces\Buyer\siteRepositoryInterface;
use App\Repositories\Interfaces\Geo\cityRepositoryInterface;
use App\Repositories\Interfaces\Geo\CountryRepositoryInterface;
use App\Repositories\Interfaces\Geo\metroRepositoryInterface;
use App\Repositories\Interfaces\Geo\stateRepositoryInterface;
use App\Traits\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractorController extends Controller
{
    use Source;
    protected $cityRepo,$stateRepo,$countryRepo,$metroRepo;
    protected $contractorRepo,$siteRepo;
    protected $order_repository;
    public function __construct(cityRepositoryInterface $city, stateRepositoryInterface $state, CountryRepositoryInterface $country,
                                metroRepositoryInterface $metro,contractorRepositoryInterface $contractor,siteRepositoryInterface $site, orderRepository $order_repository)
    {
        $this->cityRepo = $city;
        $this->stateRepo = $state;
        $this->countryRepo = $country;
        $this->metroRepo = $metro;
        $this->contractorRepo = $contractor;
        $this->siteRepo = $site;
        $this->order_repository=$order_repository;
    }

    public function dashboard(){
	    $orders = $this->order_repository->accesibleOrders(Auth::user())->count();
	    if($orders){
	    	return redirect(route('contractor.orders.index'));
	    }
	    return redirect(route('contractor.equipment.index'));
    }

    /**
     * View to display the job site creation form.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create_job_site(){

        $this->rememberSource(route('contractors_dashboard'));
        $countries = $this->countryRepo->findAll();
        $states = $this->stateRepo->findAllBy($countries->first()->id,'country_id');
        $cities = $this->cityRepo->findAllBy($states->first()->id,'state_id');
        $contractors = $this->contractorRepo->findAllBy(Auth::user()->id,'user_id');

        return view('web.contractor.job_sites.new',compact(
            'countries',
                    'states',
                        'cities',
                        'contractors'
        ));
    }

}
