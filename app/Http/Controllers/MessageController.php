<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(){
        $users = User::all();
        return inertia('Chat/Contacts', compact('users'));
    }
    public function userChat(User $buddy){
        $user = auth()->user();
        $messages1 = Message::where('sender_id', $user->id)
            ->where('recipient_id',$buddy->id)
            ->orderBy('created_at', 'asc')
            ->get();
        $messages2 = Message::where('sender_id', $buddy->id )
            ->where('recipient_id',$user->id)
            ->orderBy('created_at', 'asc')
            ->get();
        $messages = $messages1->merge($messages2);
        $messages = $messages->sortBy('created_at');

        return inertia('Chat/Chat', compact('user', 'buddy', 'messages'));
    }
    public function storeMessage(Request $request){

    }
}
