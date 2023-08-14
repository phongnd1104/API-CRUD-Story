<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6|confirmed'
        ]);

        if ($validator->fails()){
            $this->message = $validator->messages();
            goto next;
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $this->message = "success";
        $data = [
            'user' => $user,
        ];
        next:
        return $this->responseData($data??[]);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()){
            $this->message = $validator->messages();
            goto next;
        }
        $user = User::where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            $this->message = "Login fail";
            goto next;
        }
        $token = $user->createToken('auth_token')->accessToken;
        $this->message = "success";
        $data = [
            'token' => $token
        ];
        next:
        return $this->responseData($data??[]);
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
        $this->message = "logged out successfully";
        return $this->responseData($data??[]);
    }

}
