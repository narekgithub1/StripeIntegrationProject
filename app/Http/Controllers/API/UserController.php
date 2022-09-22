<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\MailPodcast;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function userLogin(Request $request)
    {
        $input = $request->all();

        $validation = Validator::make($input, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validation ->fails())
        {
            return response()->json(['error' => $validation ->errors()], 422);
        }

        if (Auth::attempt(['email' => $input['email'],'password'=> $input['password']]))
        {
            $user = Auth::user();

            $token = $user->createToken('MyApp')->accessToken;

            return response()->json(['token' => $token]);
        }
    }

    public function userDetails()
    {
        $user = Auth::guard('api')->user();

        return response()->json(['data' => $user]);
    }
}
