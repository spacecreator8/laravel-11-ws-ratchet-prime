<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(){
        $users = User::all();
        return inertia('Chat/Contacts', compact('users'));
    }
    public function userChat(User $user){
        $messages = [];
        return inertia('Chat/Chat', compact('user', 'messages'));
    }
}
