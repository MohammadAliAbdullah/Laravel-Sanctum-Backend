<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $status_code= 200; // successfully

    public function register(Request $request)
    {
        // $validator = $this->validator($request->all())->validate();
        $validator = Validator::make($request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'], // , 'unique:users'
                'password' => ['required', 'string', 'min:4'],
            ]
        );
        if($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "Please Input Valid Data", "errors" => $validator->errors()]);
        }
        $user_status = User::where("email", $request->email)->first();
        if(!is_null($user_status)) {
           return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! email already registered"]);
        }

        $user = $this->create($request->all());
        if(!is_null($user)) {
            $this->guard()->login($user);
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Registration completed successfully", "data" => $user]);
        }else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Failed to register"]);
        }
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     * @author Mohammad Ali Abdullah .. 
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    protected function guard()
    {
        return Auth::guard();
    }
    /**
     * method public
    * @author Mohammad Ali Abdullah
    * @date 01-01-2021.
    */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                "email"             =>          "required|email",
                "password"          =>          "required"
            ]
        );
        // check validation email and password .. 
        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_error" => $validator->errors()]);
        }
        // check user email validation ..
        $email_status = User::where("email", $request->email)->first();
        if(!is_null($email_status)) {
            // check user password validation ..
            // ---- first try -----
            // $password_status    =   User::where("email", $request->email)->where("password", Hash::check($request->password))->first();
            // if password is correct ..
            // ---- first try -----
            // if(!is_null($password_status)) {
            if(Hash::check($request->password, $email_status->password)) {
                $credentials = $request->only('email', 'password');
                if (Auth::attempt($credentials)) {
                    // Authentication passed ..
                    $authuser = auth()->user();
                    return response()->json(["status" => $this->status_code, "success" => true, "message" => "You have logged in successfully", "data" => $authuser]);
                }
            }else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Incorrect password."]);
            }
        }else{
            return response()->json(["status" => "failed", "success" => false, "message" => "Email doesnt exist."]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logged Out'], 200);
    }
}

