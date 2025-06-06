<?php

namespace App\Http\Controllers;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Routing\Controller;
use App\Models\User;


use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function index()
{
    $messages = Message::with('user')->latest()->take(20)->get()->reverse();
    return view('chat', compact('messages'));
}

public function send(Request $request)
{
    $message = Message::create([
        'user_id' => auth()->id(),
        'content' => $request->input('message'),
    ]);

    broadcast(new MessageSent($message->load('user')))->toOthers();
    
    
    // Optionally, you can return the message as a response

    return response()->json(['success' => true]);
}
    //
}