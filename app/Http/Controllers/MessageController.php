<?php

namespace App\Http\Controllers;

use App\Events\SendMessageNotification;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index() {
        return view('message');
    }

    public function openChat(Request $request) {

        $userId = Auth::user()->id;
        $sendMessages = User::with('showSendMessages')->get();
        $friend = User::find($request->id);

        $reservedMessages = Message::where([
            ['getter_id', $userId],
            ['sender_id', $request->id]
        ])
            ->orWhere([
                ['getter_id', $request->id],
                ['sender_id', $userId]
            ])
            ->get()
            ->toArray();

        return response([
            'chatMessages' => $reservedMessages,
            'user' => Auth::user(),
            'friend' => $friend
        ]);
    }

    public function sendMessage(Request $request){

        $authUser = Auth::user()->id;
        $friend = User::find($request->id);
        Message::create([
            'description' => $request->description,
            'sender_id' =>$authUser,
            'getter_id' => $request ->id,
            'status' => 0
        ]);
        event(new SendMessageNotification([
            'message' => $request->description ,
            'sender_id' => $authUser ,
            'getter_id' => $request->id,
            'userName'  =>  Auth::user()->name,
            'friend' => $friend
         ]));
    }
}
