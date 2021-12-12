<?php

namespace App\Http\Controllers;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;


class passportAuthController extends Controller
{
    /**
     * handle user registration request
     */
    public function register(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',
            'phone' => 'required|string|min:11|max:11'
        ]);
        $user= User::create([
            'name' =>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'password'=>bcrypt($request->password)
        ]);

        $access_token = $user->createToken('passport')->accessToken;
        event(new Registered($user));
        return response()->json(['token'=>$access_token, 'message'=>'Please Verify your account through the email sent to you'],201);
    }

    /**
     * login user to our application
     */
    public function login(Request $request){
        $login_credentials=[
            'email'=>$request->email,
            'password'=>$request->password,
        ];
        if(auth()->attempt($login_credentials)){
            $user_login_token= auth()->user()->createToken('passport')->accessToken;
            return response()->json(['token' => $user_login_token], 200);
        }
        else{
            return response()->json(['error' => 'UnAuthorised Access'], 401);
        }
    }

    /**
     * This method returns authenticated user details
     */
    public function authenticatedUserDetails(){
        //returns details
        return response()->json(['authenticated-user' => auth()->user()], 200);
    }
}