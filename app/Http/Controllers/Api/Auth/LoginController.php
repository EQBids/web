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
    public function loginRequestPin(Request $request){

        $request->validate([
            'email' =>  'required|email'
        ]);

        $user = $this->usersRepository->findOneBy($request->get('email'),'email');

//        If the email is no where to be found, redirect him to the sign up page.
        if(!$user){
            return Lresponse()->json(['message'=>'Account doesn\'t exists!']);
         
        }

        $pinNumber = $this->pinRepository->generatePin($user->id,$request->ip());
        try{
            Mail::to($user->email)->send(new PinGenerated($pinNumber));
        }catch(Exception $ex){
            return response()->json(['message'=>'some error has occurred!']);
        }
        return response()->json(['message'=>'The pin was sent successfully!']);
    }

}