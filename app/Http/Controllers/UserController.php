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

        User::createUser([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        return $this->respond(['confirmation' => 'sent'], 200);
    }
}
