<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/18/18
 * Time: 11:28 AM
 */

namespace App\Http\Controllers\Web\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller {

	use AuthenticatesUsers;



	function redirectPath() {
		return route('admin.dashboard');
	}

	public function __construct() {
		$this->middleware('guest')->except('logout');
	}

	function showLoginForm() {
		return view('web.admin.auth.login');
	}

	function logout( Request $request ) {
		$this->guard()->logout();

		$request->session()->invalidate();

		return redirect(route('admin.login_form'));
	}
}