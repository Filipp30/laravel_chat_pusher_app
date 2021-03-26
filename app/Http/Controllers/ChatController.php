<?php

namespace App\Http\Controllers;
use App\Events\CallAdmin;
use App\Events\NewMessage;
use App\Models\Chat;
use App\Models\ChatWaitingList;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getMessages(){
        $chat = new Chat();
        $chat = DB::select('SELECT users.name,chats.created_at AS time,chats.message
         FROM chats INNER JOIN users ON chats.user_id = users.id');
        return $chat;

//      Model->user :hasMany and Model->Chat:belongsTo =
//        $user_id = auth()->user()->id;
//        $user = User::find($user_id);
//        return $user->chat_messages;
    }
    public function addMessage(Request $request_data){
        $user = Auth::user();
        $user_name = $user->name;
        $user_message = $request_data->get('input_message');
        $time_stamp = gmdate("Y m d H:i:s");
        try {
            event(new NewMessage($user_name,$user_message,$time_stamp));
        }catch (BroadcastException $broadcastException){
            return $broadcastException;
        }
        $chat = new Chat();
        $chat->session = 8888;
        $chat->user_id = $user->id;
        $chat->message = $user_message;
        $chat->save();
    }


    //call admin for chat
    //if get answer (generate session)
    // else time out return false , no answer
    public function call_admin_for_chat(){
        $user = Auth::user();
        try {
            $call = new ChatWaitingList();
            $call->user_id = $user->id;
            $call->session = time();
            $call->save();
            event(new CallAdmin());
            return 'call_admin:Chat waiting list + event was called';
        }catch (Exception|BroadcastException $exception){
            return $exception;
        }
    }
}
