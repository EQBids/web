<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 2/28/18
 * Time: 9:10 PM
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PinRequest;
use App\Mail\Auth\PinGenerated;
use App\Models\Security\Pin;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Repositories\Eloquent\PinRepository;

/**
 *
 * Class PinController
 * @package App\Http\Controllers\Auth
 *
 *
 *
 *
 *
 */
class PinController  extends Controller {

	protected $pin_repository;

	public function __construct(PinRepository $pin_repository) {

		$this->pin_repository=$pin_repository;
	}


	/**
	 *
	 * @SWG\Post(
	 *      path="/oauth/pin",
	 *      summary="Generates a new pin of the user identified with the supplied email",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          in="body",
	 *          name="email",
	 *          description="the email of the user that will get the pin",
	 *          required=true,
	 *     @SWG\Schema(type="string",format="email")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="a confirmation message will be returned, the generated pin will be send to the specified email"
	 *      )
	 * )
	 * @param PinRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function generatePin(PinRequest $request){
		$email=$request->input('email');
		$device_id=$request->ip();
		$user = User::where('email',$request->input('email'))->first();
		if ($user){
			$code=$this->pin_repository->generatePin($user->id,$device_id);
			Mail::to($email)->send(new PinGenerated($code));
			return response()->json(['message'=>'Pin sended to '.$email,'pin'=>$code]);
		}else{
			return response()->json(['message'=>'No user found'],404);
		}

	}

}