<?php

namespace App\Http\Controllers\Auth;

use function App\Http\buildAddress;
use App\Http\Controllers\Controller;
use function App\Http\getEmailsFromCommaList;
use App\Http\Requests\Signup\SignupRequest;
use App\Http\Resources\Buyer\industryResource;
use App\Mail\Signup\ContractorSignedUp;
use App\Mail\Signup\SupplierSignedUp;
use App\Mail\Signup\WelcomeMessage;
use App\Models\Industry;
use App\Repositories\Interfaces\Geo\cityRepositoryInterface;
use App\Repositories\Interfaces\Geo\CountryRepositoryInterface;
use App\Repositories\Interfaces\Geo\stateRepositoryInterface;
use App\Repositories\Interfaces\geocodingRepositoryInterface;
use App\Repositories\Interfaces\roleRepositoryInterface;
use App\Repositories\Interfaces\settingsRepositoryInterface;
use App\Repositories\Interfaces\usersRepositoryInterface;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SignupController extends Controller
{
    protected $countryRepo,$stateRepo,$cityRepo,$usersRepo;
    protected $roleRepo,$geoRepo;
    protected $settingsRepo;

    public function __construct(CountryRepositoryInterface $cRepo,
                                stateRepositoryInterface $sRepo,
                                cityRepositoryInterface $ctRepo,
                                usersRepositoryInterface $usersRepo,
                                roleRepositoryInterface $roleRepo,
                                geocodingRepositoryInterface $geoRepo,
                                settingsRepositoryInterface $settingsRepo
    )
    {
        $this->countryRepo = $cRepo;
        $this->stateRepo = $sRepo;
        $this->cityRepo = $ctRepo;
        $this->usersRepo = $usersRepo;
        $this->roleRepo = $roleRepo;
        $this->geoRepo = $geoRepo;
        $this->settingsRepo = $settingsRepo;
    }

    /**
     * Displays the view of the signup form for the contractor.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function signup_contractor(){

        $countries = $this->countryRepo->findAll();
        $states = $this->stateRepo->findByCountry($countries->first()->id);
        $cities = $this->cityRepo->findByState($states->first()->id);
	    $industries=Industry::query()->topOnly()->get();
	    $sub_industries = Industry::query()->get();
	    $sub_industries=industryResource::collection($sub_industries);
	    $sub_industries=$sub_industries->groupBy('parent_id');

        return view('web.signup.signup_contractor',compact(
            'countries',
                    'states',
                        'cities',
                    'industries',
                    'sub_industries'
        ));
    }

    /**
     * Display the view of the signup form for the supplier.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function signup_supplier(){

        $countries = $this->countryRepo->findAll();
        $states = $this->stateRepo->findByCountry($countries->first()->id);
        $cities = $this->cityRepo->findByState($states->first()->id);
        return view('web.signup.signup_supplier',compact(
            'countries',
            'states',
            'cities'
        ));
    }

    /**
     * Signs up a new User (contractor or supplier).
     * @param Request $request
     */
    public function signup(SignupRequest $request){
    	$roleName = $request->get('role');

//        If some user wants to play smart by modifying the hidden input in the signup forms, we send him back.
	    if($roleName != 'contractor' && $roleName != 'supplier'){
            return redirect()->back();
        }


        $city = $this->cityRepo->findOneBy($request->get('city'),'id');
        $state = $this->stateRepo->findOneBy($request->get('state'));
        $country = $this->countryRepo->findOneBy($request->get('country'));
		$fullAddress = buildAddress($request->get('address'),$country->name,$state->name,$city->name);

        $coordinates = $this->geoRepo->getAddressCoordinates($fullAddress,$city);
        $userData = [
            'first_name'    =>  $request->get('first_name'),
            'last_name'     =>  $request->get('last_name'),
            'country_id'    =>  $request->get('country'),
            'state_id'      =>  $request->get('state'),
            'city_id'       =>  $request->get('city'),
            'phone'         =>  $request->get('phone'),
            'email'         =>  $request->get('email'),
            'status'        =>  4   //Status "On Approval"
        ];

        if($roleName === 'contractor'){

            $role = $this->roleRepo->findOneBy('contractor-superadmin','name');
            $contractorData = [

                'company_name'      =>  $request->get('company_name'),
                'address'           =>  $request->get('address'),
                'details'           =>  array(),
                'lat'               =>  $coordinates->getLatitude(),
                'lon'               =>  $coordinates->getLongitude(),
                'country_id'    =>  $request->get('country'),
                'state_id'      =>  $request->get('state'),
                'city_id'       =>  $request->get('city'),
	            'industry_id'      =>  $request->get('sub_industry',$request->get('industry'))
            ];
            $userData['contractor'] = $contractorData;
            $userData['status']=User::STATUS_ACTIVE;

            $mailsToNotify = $this->settingsRepo->findOneBy('contractor_signup_notification_emails','name');
        }
        elseif ($roleName === 'supplier'){

            $role = $this->roleRepo->findOneBy('supplier-superadmin','name');

            $supplierData = [
                'details'       =>  array(),
                'status'        =>  4,  //status "On Approval"
                'name'          =>  $request->get('company_name'),
                'address'       =>  $request->get('address'),
                'lat'           =>  $coordinates->getLatitude(),
                'lon'           =>  $coordinates->getLongitude(),
                'country_id'    =>  $request->get('country'),
                'state_id'      =>  $request->get('state'),
                'city_id'       =>  $request->get('city'),
            ];

            $userData['supplier'] = $supplierData;

            $mailsToNotify = $this->settingsRepo->findOneBy("supplier_signup_notification_emails",'name');
        }

        $jsonData = [
            'company_name'      =>  $request->get('company_name'),
            'postal_code'       =>  $request->get('postal_code'),
            'address'           =>  $request->get('address'),
        ];


        $userData['settings'] = $jsonData;


        $user = $this->usersRepo->createWithRole($userData,$role);



//        If there are any emails to notify in the corresponding setting option, we send all of them.
        if(isset($mailsToNotify)){

            $emails = getEmailsFromCommaList($mailsToNotify->value);

            foreach($emails as $email){

                //TODO: put the mails into a queue.
                if($roleName == 'contractor'){

                    Mail::to($email)->send(new ContractorSignedUp());
                }
                elseif($roleName == 'supplier'){
                    Mail::to($email)->send(new SupplierSignedUp());
                }
            }
        }
	    if ($roleName === 'supplier'){
		    return redirect()->route('supplier_on_approval');
	    }else{
        	return redirect()->route('show_login',['signup'=>true]);
	    }

    }

    /**
     * Displays a view to let the user now we sent him a confirmation email.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function signup_email_sent(){

        return view('web.signup.signup_confirmation_email');
    }

    public function supplier_on_approval(){
    	return view('web.signup.supplier_in_review');
    }
}
