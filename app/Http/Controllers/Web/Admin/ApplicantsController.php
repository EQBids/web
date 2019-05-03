<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Applicant\ApplicantExistsRequest;
use App\Http\Requests\Applicant\ApplicantRequest;
use App\Repositories\Interfaces\usersRepositoryInterface;
use App\User;
use Illuminate\Http\Request;

class ApplicantsController extends Controller
{
    protected $usersRepo;
    public function __construct(usersRepositoryInterface $usersRepo)
    {
        $this->usersRepo = $usersRepo;
    }

    public function index(){

        $users = $this->usersRepo->findApplicants();
        return view('web.admin.applicants.index',compact('users'));
    }

    public function view(ApplicantExistsRequest $request, $id){

        $user = $this->usersRepo->findApplicantBy($id);
        if(!$user){
        	return redirect(route('admin.applicants.index'));
        }
        $country = $user->country;
        $state = $user->state;
        $city = $user->city;
        return view('web.admin.applicants.view',compact('user','country','state','city'));
    }

    public function accept(ApplicantRequest $request,$id){

        $data = [
            'first_name'    =>  $request->get('first_name'),
            'last_name'     =>  $request->get('last_name'),
            'country_id'    =>  $request->get('country'),
            'state_id'      =>  $request->get('state'),
            'city_id'       =>  $request->get('city'),
            'phone'         =>  $request->get('phone'),
            'status'        =>  User::STATUS_ACTIVE,
        ];

        $settings = [
            'company_name'      =>  $request->get('company_name'),
            'postal_code'       =>  $request->get('postal_code'),
            'secondary_phone'   =>  $request->get('secondary_phone'),
            'address'           =>  $request->get('address'),
            'company_position'  =>  $request->get('company_position'),
        ];

        $data['settings'] = $settings;

        if($this->usersRepo->acceptApplicant($data,$id)){
	        return redirect()->route('admin.applicants.index')->with('notifications',collect([
		        [
			        'text'=>__("The applicant was accepted successfully"),
			        'type'=>'success',
			        'wait'=>10,
		        ]
	        ]));
        }else{
	        return redirect()->back()->with('notifications',collect([
		        [
			        'text'=>__("Something went wrong approving the user"),
			        'type'=>'error',
			        'wait'=>10,
		        ]
	        ]));
        }


    }

    public function reject(ApplicantExistsRequest $request,$id){

        $this->usersRepo->rejectApplicant($id);

        return redirect()->route('admin.applicants.index')->with('notifications',collect([
            [
                'text'=>__("The applicant was rejected successfully"),
                'type'=>'success',
                'wait'=>10,
            ]
        ]));
    }

	public function destroy(ApplicantExistsRequest $request,$id){

		$this->usersRepo->destroy($id);

		return redirect()->route('admin.applicants.index')->with('notifications',collect([
			[
				'text'=>__("The applicant was deleted successfully"),
				'type'=>'success',
				'wait'=>10,
			]
		]));
	}
}
