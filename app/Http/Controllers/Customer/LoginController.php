<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:customer')->except('logout');
    }

    public function login(){
        return view('customer.login');
    }

    public function attemptLogin(Request $request){
        try{
            $rules = [
                'email' => 'required|email',
                'password' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()) {
                return response()->json(['status' => 1, 'errors'  => $validator->getMessageBag()->toArray()]);
            }

//        Attemt to login
            if (Auth::guard('customer')->attempt(['email' => $request->email , 'password' => $request->password], $request->remember)) {
                return response()->json(['status' => 200, 'message' => 'successfully logged in']);
            }else{
                return response()->json(['status' => 1, 'message' => 'Credentials are wrong']);
            }
        }catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function logout() {
        Auth::guard('customer')->logout();
        return redirect()->route('clogin');
    }
}
