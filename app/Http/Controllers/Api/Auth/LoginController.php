<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Auth\PinGenerated;
use App\Http\Resources\Auth\LoginResource;
use App\Repositories\Eloquent\SettingsRepository;
use App\Repositories\Interfaces\pinRepositoryInterface;
use App\Repositories\Interfaces\usersRepositoryInterface;
use App\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $pinRepository;
    protected $usersRepository;
    protected $settings_repository;
    protected $decayMinutes=5; //as requested



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(pinRepositoryInterface $pinRepository,usersRepositoryInterface $usersRepository,SettingsRepository $settings_repository)
    {
        $this->pinRepository = $pinRepository;
        $this->usersRepository = $usersRepository;
        $this->settings_repository=$settings_repository;
    }

    /**
     * Generates a pin, sends it to the email of the user and redirects to the view to login with it.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestPin(Request $request){
        
        $request->validate([
            'email' =>  'required|email'
        ]);

        $user = $this->usersRepository->findOneBy($request->get('email'),'email');

//        If the email is no where to be found, redirect him to the sign up page.
        if(!$user){
            return response()->json(['error'=>'0']);
         
        }

        $pinNumber = $this->pinRepository->generatePin($user->id,$request->ip());
        try{
            Mail::to($user->email)->send(new PinGenerated($pinNumber));
        }catch(Exception $ex){
            return response()->json(['error'=>'10']);
        }
        return response()->json(['success'=>'1']);
    }

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     **/
    /*
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'pin' => request('pin')])){ 
            //$user = Auth::user(); 
            //$success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['message'=>'xx!']);
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
 */


    public function maxAttempts() {
        echo "oi1";
    	return $this->settings_repository->getValue('num_failed_logins_before_block',5);
    }

    protected function validateLogin( Request $request ) {
        echo "oi2";
    	$request->validate([
		    'pin'   =>  'required',
		    'email' =>  'required|email',
	    ]);
    }

    protected function guard() {
        echo "oi3";
    	return Auth::guard('pin');
    }

    protected function credentials( Request $request ) {
        echo "oi4";
    	return $request->only('email','pin');
    }

    protected function authenticated( Request $request, $user ) {
        echo "oi5";
    	$this->user=$user;
	    if($user->status==User::STATUS_PENDING){
		    $this->usersRepository->changeStatus($user->id,1);
	    }
    }

	protected function redirectTo(){
    	// IF the user had tried to get into an specific url but was found unlogged, we saved the intended url and first
        // redirect to it
        echo "oiered";die;
	    $intendedUrl = RequestFacade::session()->get('intendedUrl');
	    if($intendedUrl && $intendedUrl!=route('show_login')){
		    RequestFacade::session()->forget('intendedUrl');
		    return $intendedUrl;
	    }

		//Otherwise, we redirect him based on his role.
	    if($this->user->is_contractor){
		    return route('contractors_dashboard');
	    }
	    elseif ($this->user->hasRole('supplier-superadmin')){
		    return route('suppliers_dashboard');
	    }
	    else
		    return url('/');
    }

    protected function sendLockoutResponse( Request $request ) {
	    throw ValidationException::withMessages([
		    $this->username() => ['You have exceeded the number of permitted failed attempts to log in and your account is now suspended. Please contact us to get your account re-activated.'],
	    ])->status(423);
    }

    //this is implemented here because only is used on this controller
    protected function fireLockoutEvent( Request $request ) {
    	$email = $request->get('email',null);
    	if($email){
    		$user=$this->usersRepository->findOneBy($email,'email');
    		if($user){
    			$this->usersRepository->changeStatus($user->id,User::STATUS_BLOCKED);
		    }
	    }
	    event(new Lockout($request));
    }

}