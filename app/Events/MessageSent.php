<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message_id;
    public $content;
    public $image;
    public $sender_id;
    public $receiver_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data = [])
    {
        $this->message_id = $data['message_id'];
        $this->content = $data['content'];
        $this->image = $data['image'];
        $this->sender_id = $data['sender_id'];
        $this->receiver_id = $data['receiver_id'];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('message.' . $this->receiver_id);
    }
}
