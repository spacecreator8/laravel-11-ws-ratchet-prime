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

        // Получаем сообщения от пользователя к другу и от друга к пользователю
        $messages = Message::where(function ($query) use ($user, $buddy) {
            $query->where('sender_id', $user->id)
                ->where('recipient_id', $buddy->id);
        })
            ->orWhere(function ($query) use ($user, $buddy) {
                $query->where('sender_id', $buddy->id)
                    ->where('recipient_id', $user->id);
            })
            ->orderBy('created_at') // Сортируем по дате
            ->get();

        return inertia('Chat/Chat', compact('user', 'buddy', 'messages'));
    }
    public function storeMessage(Request $request){
        $data = $request->all();
        Message::create([
            'sender_id'=> $data['sender'],
            'recipient_id'=> $data['recipient'],
            'content'=> $data['content'],
        ]);
//        return redirect()->route('main.chat', $data['recipient']);
    }
}
