<?php

namespace App\Http\Controllers;
use App\Http\Middleware\Authenticate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {

    }
    public function myProfile(){
        $user = Auth::user();
        return view('userProfile', compact('user'));
    }

    public function register(Request $request){

            try {
                $validator = Validator::make($request->toArray(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:users|max:25',
                    'psw' => 'required|min:6',
                    'psw-repeat' => 'required|min:6',
                ]);

                if ($validator->fails()) {
                    ($errors = $validator->errors());
                }

                $data = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'remember_token' =>  $request->_token,
                    'email_verified_at' =>0

                ];

                $user = User::create($data);
                return redirect('/userProfile');
//                return view('userProfile',compact('user'));
//                return response()->json($user, 201);
            } catch (\Exception $e) {
                return response()->json([
                    $errors ], 400);
            }
    }

    public function showUsers() {
        $authUserId = Auth::user()->id;
        $users = User::where('id', '!=', $authUserId)->get();

        return view('users', compact(['users']));
    }
}
