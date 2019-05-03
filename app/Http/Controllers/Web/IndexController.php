<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Product\equipmentRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\Contact\ContactRequest;
use Illuminate\Support\Facades\Auth;
use function Swagger\scan;
use App\Mail\Contact\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Buzz\LaravelGoogleCaptcha\CaptchaFacade as Captcha;

class IndexController extends Controller
{
	protected $equipment_repository;
    public function __construct(equipmentRepositoryInterface $equipment_repository)
    {
		$this->equipment_repository=$equipment_repository;
    }

    public function index(){
    	/*try{
		    $slides = array_diff(scandir(getcwd().'/images/slider'),array('..', '.'));
	    }catch (Exception $exception){
    		$slides=[];
	    }

	    $slides=array_map(function ($item){
	    	return '/images/slider/'.$item;
	    },$slides);

    	return view('web.index')->with(compact('slides'));*/

    	$equipments = $this->equipment_repository->with('categories')->paginate(3);
    	return view('web.main')->with(compact('equipments'));

	}
	
	public function contact(){
    	return view('web.contact.index');
	}

    public function process_contact_form(Request $request){
		$rules = (new ContactRequest)->rules();
		$validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
			$request->flash();
			return redirect()->back()->withInput()->withErrors($validator);
		}
        $data = [
            'name'    =>  $request->get('name'),
            'email'     =>  $request->get('email'),
            'company'    =>  $request->get('company'),
            'phoneday'      =>  $request->get('telephone-day'),
            'phonenight'       =>  $request->get('telephone-night'),
            'message'         =>  $request->get('message'),
		];
		Mail::to(env('CONTACT_EMAIL'))->send(new ContactMessage($data));
		if (Mail::failures()){
			$request->flash();
			return redirect()->back()->with('error', ['true']);
		}else
			return redirect()->back()->with('success', ['true']);
	}
}
