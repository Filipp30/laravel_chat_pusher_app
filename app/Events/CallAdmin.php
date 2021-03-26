<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class CallAdmin
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }


    public function broadcastOn()
    {
        return new PrivateChannel('my-channel');
    }
}