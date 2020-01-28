<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Validators\ValidatesAuthenticationRequests;

class UserController extends Controller
{
	use ValidatesAuthenticationRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Register a new user and return if successful.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registration(Request $request) {
		$this->validateRegister($request);

        $email = User::createUser([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        return $this->respond(['confirmation' => 'sent', 
            'email' => $email->email, 
            'code' => $email->activate_code], 200);
    }

    public function activate(Request $request) {
        $user = User::activate($request->input('code'));

        return $this->respond(['activated' => $user ? ($user->is_active == 1) : false], 200);
    }

    public function auth(Request $request) {
        $user = User::auth($request->input('email'), $request->input('password'));

        if ($user) {
            return $this->respond(['api_token' => $user->api_token], 200);
        } else {
            return $this->respond(['msg' => 'auth failed'], 400);
        }
    }

    public function get(Request $request) {
        return User::getByEmail($request->input('email'));
    }
}
