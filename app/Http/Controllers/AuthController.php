<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Categorey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;


class AuthController extends BaseController
{
    public function registerPharmacist(Request $request){

        $validator = Validator::make($request->all(),
        [
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
        ]);
        if($validator->fails())
        {
            return $this->sendError('Please validate error',$validator->errors()->toArray());
        }
        $user_check = User::where("email", "=", $request->email)->first();
        if(isset($user_check->id))
        {
            return $this->sendError('this email is already in use');
        }

        // create data
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = "pharmacist";
          
        $user->save();
        $token = Auth::login($user);
        return $this->loginAndRegisterResponse($user,'User registered successfully',$token);


    }
    public function login(Request $request)
    {
        $rules = [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ];

        $input['email'] = $request->input('email');
        $input['password'] = $request->input('password');

        $validator = Validator::make($input, $rules);

        if($validator->fails()) {
            return $this->sendError('pleas check vlidate',$validator->errors()->toArray());
        }

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return $this->sendError('pleas check your auth', ['error'=>'unauthorised']);
        }

        $user = Auth::user();
        return $this->loginAndRegisterResponse($user,'User login successfully',$token);
    }
    public function profile()
    {
        return $this->sendResponse2(auth()->user(),'this is user profile');
    }
    public function logout()
    {
        Auth::logout();
        return $this->sendResponseWithJustMessage('the user is logged out');
    }
}
