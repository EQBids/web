<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Auth\PinGenerated;
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
//        $this->middleware('guest')->except('logout');
        $this->pinRepository = $pinRepository;
        $this->usersRepository = $usersRepository;
        $this->settings_repository=$settings_repository;
    }

    /**
     * View that shows the login form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_login(){
        return view('web.auth.login');
    }


    /**
     * Generates a pin, sends it to the email of the user and redirects to the view to login with it.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginRequestPin(Request $request){

        $request->validate([
            'email' =>  'required|email'
        ]);

        $user = $this->usersRepository->findOneBy($request->get('email'),'email');

//        If the email is no where to be found, redirect him to the sign up page.
        if(!$user){
            return redirect()->route('show_login')->withErrors(['message'=>'Account doesn\'t exists'])
                              ->withInput(['email'])
                             ->with('notifications',collect([
                             	                    [
	                                                    'type'=>'error',
	                                                    'text'=>__('Account doesn\'t exists')
                                                    ]
	                             ])
                             );
        }

        $pinNumber = $this->pinRepository->generatePin($user->id,$request->ip());
        try{
            Mail::to($user->email)->send(new PinGenerated($pinNumber));
        }catch(Exception $ex){
            return redirect()->route('show_login')->withErrors(['message'=>'some error has occurred!']);
        }
        return redirect()->route('login_enter_pin')
                         ->withInput(['email'=>$request->input('email')])->with('notifications',collect([
		        [
			        'type'=>'success',
			        'text'=>__('Pin sent'),
			        'wait'=>10
		        ]
	        ]))->with('pin','1');
    }

    /**
     * View to login with a pin.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function enter_pin(){
        return view('web.auth.login_enter_pin');

    }


    public function logout(){
        Auth::logout();

        return redirect()->to('/');
    }

    public function maxAttempts() {
    	return $this->settings_repository->getValue('num_failed_logins_before_block',5);
    }

    protected function validateLogin( Request $request ) {
    	$request->validate([
		    'pin'   =>  'required',
		    'email' =>  'required|email',
	    ]);
    }

    protected function guard() {
    	return Auth::guard('pin');
    }

    protected function credentials( Request $request ) {
    	return $request->only('email','pin');
    }

    protected function authenticated( Request $request, $user ) {
    	$this->user=$user;
	    if($user->status==User::STATUS_PENDING){
		    $this->usersRepository->changeStatus($user->id,1);
	    }
    }

	protected function redirectTo(){

    	// IF the user had tried to get into an specific url but was found unlogged, we saved the intended url and first
		// redirect to it
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
